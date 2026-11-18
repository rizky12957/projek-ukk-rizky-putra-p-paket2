<?php

// =========================================================
// KONEKSI DATABASE
// =========================================================

require_once '../koneksi.php';


// =========================================================
// CEK LOGIN
// =========================================================

if (!isset($_SESSION['login']) || !isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}


// =========================================================
// CEK REQUEST POST
// =========================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Akses tidak valid.");
}


// =========================================================
// MENGAMBIL DATA DARI FORM
// =========================================================

$id_parkir = (int) ($_POST['id_parkir'] ?? 0);
$waktu_keluar = trim($_POST['waktu_keluar'] ?? '');
$durasi_jam = (int) ($_POST['durasi_jam'] ?? 0);
$biaya_total = (float) ($_POST['biaya_total'] ?? 0);


// =========================================================
// MENGAMBIL ID USER DARI SESSION
// =========================================================

$id_user = (int) $_SESSION['id_user'];


// =========================================================
// VALIDASI DATA
// =========================================================

if (
    $id_parkir <= 0 ||
    empty($waktu_keluar) ||
    $durasi_jam <= 0 ||
    $biaya_total < 0 ||
    $id_user <= 0
) {
    die("Data transaksi tidak lengkap atau tidak valid.");
}


// =========================================================
// MULAI TRANSAKSI DATABASE
// =========================================================

mysqli_begin_transaction($koneksi);

try {

    // =====================================================
    // MENGAMBIL DATA PARKIR
    // =====================================================

    $query_parkir = "
        SELECT
            p.id_parkir,
            p.plat_nomor,
            p.jenis_kendaraan,
            m.nama_member,
            tr.tarif_per_jam
        FROM tb_parkir p
        LEFT JOIN tb_member m
            ON p.id_member = m.id_member
        LEFT JOIN tb_tarif tr
            ON p.id_tarif = tr.id_tarif
        WHERE p.id_parkir = ?
        FOR UPDATE
    ";

    $stmt_parkir = mysqli_prepare($koneksi, $query_parkir);

    if (!$stmt_parkir) {
        throw new Exception("Query data parkir gagal disiapkan.");
    }

    mysqli_stmt_bind_param($stmt_parkir, "i", $id_parkir);
    mysqli_stmt_execute($stmt_parkir);

    $hasil_parkir = mysqli_stmt_get_result($stmt_parkir);
    $parkir = mysqli_fetch_assoc($hasil_parkir);

    mysqli_stmt_close($stmt_parkir);


    // =====================================================
    // CEK DATA PARKIR
    // =====================================================

    if (!$parkir) {
        throw new Exception("Data parkir tidak ditemukan.");
    }

    // Biaya satu kali parkir: tidak dikalikan jumlah jam.
    $biaya_total = (float) $parkir['tarif_per_jam'];
    if ($biaya_total <= 0) {
        throw new Exception("Tarif kendaraan tidak ditemukan.");
    }


    // =====================================================
    // CEK TRANSAKSI SUDAH ADA ATAU BELUM
    // =====================================================

    $stmt_cek = mysqli_prepare(
        $koneksi,
        "SELECT id_transaksi FROM tb_transaksi WHERE id_parkir = ? LIMIT 1"
    );

    if (!$stmt_cek) {
        throw new Exception("Query pengecekan transaksi gagal.");
    }

    mysqli_stmt_bind_param($stmt_cek, "i", $id_parkir);
    mysqli_stmt_execute($stmt_cek);

    $hasil_cek = mysqli_stmt_get_result($stmt_cek);

    if (mysqli_num_rows($hasil_cek) > 0) {
        mysqli_stmt_close($stmt_cek);
        throw new Exception("Kendaraan ini sudah memiliki transaksi.");
    }

    mysqli_stmt_close($stmt_cek);


    // =====================================================
    // MENYIMPAN TRANSAKSI
    // =====================================================

    $query = "
        INSERT INTO tb_transaksi
        (
            id_parkir,
            waktu_keluar,
            durasi_jam,
            biaya_total,
            id_user
        )
        VALUES (?, ?, ?, ?, ?)
    ";

    $stmt = mysqli_prepare($koneksi, $query);

    if (!$stmt) {
        throw new Exception("Query transaksi gagal disiapkan.");
    }

    mysqli_stmt_bind_param(
        $stmt,
        "isidi",
        $id_parkir,
        $waktu_keluar,
        $durasi_jam,
        $biaya_total,
        $id_user
    );

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception(
            "Gagal menyimpan transaksi: " .
            mysqli_stmt_error($stmt)
        );
    }


    // =====================================================
    // MENGAMBIL ID TRANSAKSI
    // =====================================================

    $id_transaksi = mysqli_insert_id($koneksi);

    mysqli_stmt_close($stmt);


    // =====================================================
    // CATAT LOG AKTIVITAS OTOMATIS
    // =====================================================

    $aktivitas = "Menambahkan transaksi kendaraan dengan plat nomor " .
        $parkir['plat_nomor'] .
        ", member " .
        ($parkir['nama_member'] ?? '-') .
        ", ID transaksi " .
        $id_transaksi .
        ", durasi " .
        $durasi_jam .
        " jam, total biaya Rp " .
        number_format($biaya_total, 0, ',', '.');

    if (!catatAktivitas($koneksi, $aktivitas)) {
        throw new Exception("Gagal menyimpan log aktivitas.");
    }


    // =====================================================
    // SIMPAN SEMUA PERUBAHAN
    // =====================================================

    mysqli_commit($koneksi);


    // =====================================================
    // BERHASIL
    // =====================================================

    header("Location: tampil_transaksi.php?pesan=berhasil");
    exit;


} catch (Exception $e) {

    // Membatalkan transaksi jika terjadi kesalahan
    mysqli_rollback($koneksi);

    echo "
        <script>
            alert(" .
        json_encode($e->getMessage()) .
        ");
            window.history.back();
        </script>
    ";

    exit;
}

?>