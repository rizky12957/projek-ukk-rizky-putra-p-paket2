<?php

// =========================================================
// SESSION
// =========================================================

// Memulai session jika belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// =========================================================
// KONEKSI DATABASE
// =========================================================

// Memanggil koneksi database
require_once '../koneksi.php';


// =========================================================
// CEK LOGIN
// =========================================================

if (!isset($_SESSION['login']) || !isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}


// =========================================================
// TIMEZONE
// =========================================================

date_default_timezone_set('Asia/Jakarta');


// =========================================================
// MENGECEK DATA FORM
// =========================================================

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Mengambil data dari form
    $id_member = (int) ($_POST['id_member'] ?? 0);
    $id_area = (int) ($_POST['id_area'] ?? 0);
    $plat_nomor = trim($_POST['plat_nomor'] ?? '');
    $warna_kendaraan = trim($_POST['warna_kendaraan'] ?? '');
    $jenis_kendaraan = $_POST['jenis_kendaraan'] ?? '';
    $id_tarif = (int) ($_POST['id_tarif'] ?? 0);

    // Mengambil ID akun yang sedang login
    $id_user = (int) ($_SESSION['id_user'] ?? 0);


    // =====================================================
    // VALIDASI DATA
    // =====================================================

    if (
        $id_member <= 0 ||
        $id_area <= 0 ||
        empty($plat_nomor) ||
        empty($warna_kendaraan) ||
        empty($jenis_kendaraan) ||
        $id_tarif <= 0 ||
        $id_user <= 0
    ) {
        echo "
            <script>
                alert('Data parkir belum lengkap.');
                window.history.back();
            </script>
        ";
        exit;
    }


    // =====================================================
    // MULAI TRANSAKSI DATABASE
    // =====================================================

    mysqli_begin_transaction($koneksi);

    try {

        // =================================================
        // CEK MEMBER
        // =================================================

        $query_member = "
            SELECT id_member, nama_member
            FROM tb_member
            WHERE id_member = ?
            AND status_aktif = 'aktif'
            FOR UPDATE
        ";

        $stmt_member = mysqli_prepare($koneksi, $query_member);

        if (!$stmt_member) {
            throw new Exception("Query member gagal disiapkan.");
        }

        mysqli_stmt_bind_param($stmt_member, "i", $id_member);
        mysqli_stmt_execute($stmt_member);

        $hasil_member = mysqli_stmt_get_result($stmt_member);
        $member = mysqli_fetch_assoc($hasil_member);

        mysqli_stmt_close($stmt_member);

        if (!$member) {
            throw new Exception("Member tidak ditemukan atau tidak aktif.");
        }


        // =================================================
        // CEK MEMBER SUDAH PARKIR ATAU BELUM
        // =================================================

        $query_cek_parkir = "
            SELECT p.id_parkir
            FROM tb_parkir p
            LEFT JOIN tb_transaksi t
                ON p.id_parkir = t.id_parkir
            WHERE p.id_member = ?
            AND t.id_transaksi IS NULL
            LIMIT 1
        ";

        $stmt_cek = mysqli_prepare($koneksi, $query_cek_parkir);

        if (!$stmt_cek) {
            throw new Exception("Query pengecekan parkir gagal.");
        }

        mysqli_stmt_bind_param($stmt_cek, "i", $id_member);
        mysqli_stmt_execute($stmt_cek);

        $hasil_cek = mysqli_stmt_get_result($stmt_cek);

        if (mysqli_num_rows($hasil_cek) > 0) {
            mysqli_stmt_close($stmt_cek);

            throw new Exception(
                "Member tersebut masih mempunyai kendaraan yang sedang parkir."
            );
        }

        mysqli_stmt_close($stmt_cek);


        // =================================================
        // CEK AREA PARKIR
        // =================================================

        $query_area = "
            SELECT id_area, nama_area, kapasitas, terisi
            FROM tb_area_parkir
            WHERE id_area = ?
            FOR UPDATE
        ";

        $stmt_area = mysqli_prepare($koneksi, $query_area);

        if (!$stmt_area) {
            throw new Exception("Query area parkir gagal disiapkan.");
        }

        mysqli_stmt_bind_param($stmt_area, "i", $id_area);
        mysqli_stmt_execute($stmt_area);

        $hasil_area = mysqli_stmt_get_result($stmt_area);
        $area = mysqli_fetch_assoc($hasil_area);

        mysqli_stmt_close($stmt_area);

        if (!$area) {
            throw new Exception("Area parkir tidak ditemukan.");
        }


        // =================================================
        // CEK KAPASITAS AREA
        // =================================================

        $kapasitas = (int) $area['kapasitas'];
        $terisi = (int) $area['terisi'];

        if ($terisi >= $kapasitas) {
            throw new Exception(
                "Area " . $area['nama_area'] .
                " sudah penuh. Tidak ada slot tersedia."
            );
        }


        // =================================================
        // SIMPAN DATA PARKIR
        // =================================================

        $query_parkir = "
            INSERT INTO tb_parkir
            (
                id_member,
                id_area,
                plat_nomor,
                warna_kendaraan,
                jenis_kendaraan,
                id_tarif,
                waktu_masuk,
                id_user
            )
            VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)
        ";

        $stmt_parkir = mysqli_prepare($koneksi, $query_parkir);

        if (!$stmt_parkir) {
            throw new Exception(
                "Query simpan parkir gagal: " . mysqli_error($koneksi)
            );
        }

        mysqli_stmt_bind_param(
            $stmt_parkir,
            "iisssii",
            $id_member,
            $id_area,
            $plat_nomor,
            $warna_kendaraan,
            $jenis_kendaraan,
            $id_tarif,
            $id_user
        );

        if (!mysqli_stmt_execute($stmt_parkir)) {
            throw new Exception(
                "Gagal menyimpan data parkir: " .
                mysqli_stmt_error($stmt_parkir)
            );
        }

        mysqli_stmt_close($stmt_parkir);


        // =================================================
        // UPDATE JUMLAH TERISI AREA
        // =================================================

        $query_update_area = "
            UPDATE tb_area_parkir
            SET terisi = terisi + 1
            WHERE id_area = ?
            AND terisi < kapasitas
        ";

        $stmt_update_area = mysqli_prepare($koneksi, $query_update_area);

        if (!$stmt_update_area) {
            throw new Exception("Query update slot gagal disiapkan.");
        }

        mysqli_stmt_bind_param($stmt_update_area, "i", $id_area);

        if (!mysqli_stmt_execute($stmt_update_area)) {
            throw new Exception("Gagal memperbarui jumlah slot.");
        }

        if (mysqli_stmt_affected_rows($stmt_update_area) <= 0) {
            mysqli_stmt_close($stmt_update_area);
            throw new Exception("Slot area parkir sudah penuh.");
        }

        mysqli_stmt_close($stmt_update_area);


        // =================================================
        // CATAT LOG AKTIVITAS OTOMATIS
        // =================================================

        $aktivitas = "Mencatat kendaraan masuk dengan plat nomor " .
            $plat_nomor .
            ", milik member " .
            $member['nama_member'] .
            ", ke area " .
            $area['nama_area'];

        // Log disimpan menggunakan akun yang sedang login
        if (!catatAktivitas($koneksi, $aktivitas)) {
            throw new Exception("Gagal menyimpan log aktivitas.");
        }


        // =================================================
        // SIMPAN SEMUA PERUBAHAN
        // =================================================

        mysqli_commit($koneksi);


        // =================================================
        // BERHASIL
        // =================================================

        echo "
            <script>
                alert(" .
            json_encode(
                "Kendaraan berhasil masuk ke " .
                $area['nama_area'] .
                ". Slot terisi bertambah 1."
            ) .
            ");
                window.location='tampil_parkir.php';
            </script>
        ";

        exit;

    } catch (Exception $e) {

        // Membatalkan semua perubahan jika terjadi kesalahan
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
}

?>