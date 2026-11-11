<?php

// =========================================================
// SESSION
// =========================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// =========================================================
// TIMEZONE
// =========================================================

date_default_timezone_set('Asia/Jakarta');


// =========================================================
// KONEKSI DATABASE
// =========================================================

require_once '../koneksi.php';


// =========================================================
// CEK LOGIN
// =========================================================

if (!isset($_SESSION['login'])) {

    header("Location: ../login.php");
    exit;
}


// =========================================================
// CEK REQUEST
// =========================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    die("Akses tidak valid.");
}


// =========================================================
// MENGAMBIL DATA
// =========================================================

// Mengambil ID parkir dari form checkout
$id_parkir = isset($_POST['id_parkir'])
    ? (int) $_POST['id_parkir']
    : 0;


// Mengambil ID user dari session
$id_user = isset($_SESSION['id_user'])
    ? (int) $_SESSION['id_user']
    : 0;


// =========================================================
// VALIDASI
// =========================================================

if ($id_parkir <= 0) {

    die("ID parkir tidak ditemukan.");
}


if ($id_user <= 0) {

    die("ID user tidak ditemukan.");
}


// =========================================================
// MULAI TRANSAKSI DATABASE
// =========================================================

mysqli_begin_transaction($koneksi);


try {

    // =====================================================
    // MENGAMBIL DATA PARKIR
    // =====================================================

    $query = "
        SELECT
            p.id_parkir,
            p.id_area,
            p.plat_nomor,
            p.waktu_masuk,
            p.id_tarif,
            p.jenis_kendaraan,
            t.tarif_per_jam

        FROM tb_parkir p

        LEFT JOIN tb_tarif t
            ON p.id_tarif = t.id_tarif

        WHERE p.id_parkir = ?

        FOR UPDATE
    ";


    $stmt = mysqli_prepare($koneksi, $query);


    if (!$stmt) {

        throw new Exception(
            "Query data parkir gagal disiapkan."
        );
    }


    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $id_parkir
    );


    mysqli_stmt_execute($stmt);


    $hasil = mysqli_stmt_get_result($stmt);


    $data = mysqli_fetch_assoc($hasil);


    mysqli_stmt_close($stmt);


    // =====================================================
    // CEK DATA PARKIR
    // =====================================================

    if (!$data) {

        throw new Exception(
            "Data parkir tidak ditemukan."
        );
    }


    // =====================================================
    // MENGAMBIL ID AREA
    // =====================================================

    $id_area = (int) $data['id_area'];


    if ($id_area <= 0) {

        throw new Exception(
            "Area parkir kendaraan tidak ditemukan."
        );
    }


    // =====================================================
    // MENGECEK TARIF
    // =====================================================

    $tarif_per_jam = (int) $data['tarif_per_jam'];


    if ($tarif_per_jam <= 0) {

        throw new Exception(
            "Tarif kendaraan tidak ditemukan."
        );
    }


    // =====================================================
    // WAKTU MASUK
    // =====================================================

    $waktu_masuk = $data['waktu_masuk'];


    // =====================================================
    // WAKTU KELUAR OTOMATIS
    // =====================================================

    $waktu_keluar = date('Y-m-d H:i:s');


    // =====================================================
    // MENGHITUNG DURASI
    // =====================================================

    $timestamp_masuk = strtotime($waktu_masuk);

    $timestamp_keluar = strtotime($waktu_keluar);


    $selisih =
        $timestamp_keluar -
        $timestamp_masuk;


    if ($selisih < 0) {

        $selisih = 0;
    }


    // =====================================================
    // MENGHITUNG DURASI JAM
    // =====================================================

    $durasi_jam = ceil(
        $selisih / 3600
    );


    // Minimal 1 jam
    if ($durasi_jam < 1) {

        $durasi_jam = 1;
    }


    // =====================================================
    // MENGHITUNG TOTAL BIAYA
    // =====================================================

    $biaya_total =
        $durasi_jam *
        $tarif_per_jam;


    // =====================================================
    // CEK SUDAH CHECKOUT ATAU BELUM
    // =====================================================

    $query_cek = "
        SELECT id_transaksi

        FROM tb_transaksi

        WHERE id_parkir = ?

        LIMIT 1

        FOR UPDATE
    ";


    $stmt_cek = mysqli_prepare(
        $koneksi,
        $query_cek
    );


    if (!$stmt_cek) {

        throw new Exception(
            "Query pengecekan transaksi gagal."
        );
    }


    mysqli_stmt_bind_param(
        $stmt_cek,
        "i",
        $id_parkir
    );


    mysqli_stmt_execute($stmt_cek);


    $hasil_cek =
        mysqli_stmt_get_result($stmt_cek);


    if (mysqli_num_rows($hasil_cek) > 0) {

        mysqli_stmt_close($stmt_cek);

        throw new Exception(
            "Kendaraan ini sudah melakukan checkout."
        );
    }


    mysqli_stmt_close($stmt_cek);


    // =====================================================
    // MENGUNCI DATA AREA
    // =====================================================

    $query_area = "
        SELECT
            nama_area,
            kapasitas,
            terisi

        FROM tb_area_parkir

        WHERE id_area = ?

        FOR UPDATE
    ";


    $stmt_area = mysqli_prepare(
        $koneksi,
        $query_area
    );


    if (!$stmt_area) {

        throw new Exception(
            "Query area parkir gagal disiapkan."
        );
    }


    mysqli_stmt_bind_param(
        $stmt_area,
        "i",
        $id_area
    );


    mysqli_stmt_execute($stmt_area);


    $hasil_area =
        mysqli_stmt_get_result($stmt_area);


    $area =
        mysqli_fetch_assoc($hasil_area);


    mysqli_stmt_close($stmt_area);


    // =====================================================
    // CEK AREA
    // =====================================================

    if (!$area) {

        throw new Exception(
            "Data area parkir tidak ditemukan."
        );
    }


    // =====================================================
    // CEK TERISI
    // =====================================================

    if ((int) $area['terisi'] <= 0) {

        throw new Exception(
            "Jumlah kendaraan pada area sudah 0."
        );
    }


    // =====================================================
    // SIMPAN TRANSAKSI
    // =====================================================

    $query_insert = "
        INSERT INTO tb_transaksi
        (
            id_parkir,
            waktu_keluar,
            durasi_jam,
            biaya_total,
            id_user
        )

        VALUES
        (
            ?,
            ?,
            ?,
            ?,
            ?
        )
    ";


    $stmt_insert = mysqli_prepare(
        $koneksi,
        $query_insert
    );


    if (!$stmt_insert) {

        throw new Exception(
            "Query transaksi gagal disiapkan."
        );
    }


    mysqli_stmt_bind_param(
        $stmt_insert,
        "isiii",
        $id_parkir,
        $waktu_keluar,
        $durasi_jam,
        $biaya_total,
        $id_user
    );


    if (!mysqli_stmt_execute($stmt_insert)) {

        throw new Exception(
            "Gagal menyimpan transaksi: " .
            mysqli_stmt_error($stmt_insert)
        );
    }


    // =====================================================
    // MENGAMBIL ID TRANSAKSI
    // =====================================================

    $id_transaksi =
        mysqli_insert_id($koneksi);


    mysqli_stmt_close($stmt_insert);


    // =====================================================
    // CEK ID TRANSAKSI
    // =====================================================

    if ($id_transaksi <= 0) {

        throw new Exception(
            "ID transaksi tidak berhasil dibuat."
        );
    }


    // =====================================================
    // MENGURANGI TERISI AREA
    // =====================================================

    $query_update_area = "
        UPDATE tb_area_parkir

        SET terisi = terisi - 1

        WHERE id_area = ?

        AND terisi > 0
    ";


    $stmt_update = mysqli_prepare(
        $koneksi,
        $query_update_area
    );


    if (!$stmt_update) {

        throw new Exception(
            "Query update slot gagal disiapkan."
        );
    }


    mysqli_stmt_bind_param(
        $stmt_update,
        "i",
        $id_area
    );


    if (!mysqli_stmt_execute($stmt_update)) {

        throw new Exception(
            "Gagal mengurangi jumlah slot."
        );
    }


    if (
        mysqli_stmt_affected_rows(
            $stmt_update
        ) <= 0
    ) {

        mysqli_stmt_close($stmt_update);

        throw new Exception(
            "Kapasitas area tidak berubah."
        );
    }


    mysqli_stmt_close($stmt_update);


    // =====================================================
    // COMMIT
    // =====================================================

    mysqli_commit($koneksi);


    // =====================================================
    // REDIRECT KE STRUK
    // =====================================================

    /*
        File ini berada di:

        parkir/simpan_checkout.php

        Sedangkan struk berada di:

        transaksi/cetak_struk.php

        Jadi harus naik satu folder
        menggunakan ../transaksi/
    */

    header(
        "Location: ../transaksi/cetak_struk.php?id=" .
        $id_transaksi
    );

    exit;


} catch (Exception $e) {


    // =====================================================
    // ROLLBACK
    // =====================================================

    mysqli_rollback($koneksi);


    echo "
        <script>

            alert(" .
        json_encode(
            $e->getMessage()
        ) .
        ");

            window.history.back();

        </script>
    ";

    exit;
}

?>