<?php

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
// MENGECEK ID PARKIR
// =========================================================

if (!isset($_GET['id']) || (int) $_GET['id'] <= 0) {

    echo "
        <script>
            alert('ID parkir tidak ditemukan.');
            window.location='tampil_parkir.php';
        </script>
    ";

    exit;
}


// =========================================================
// MENGAMBIL ID PARKIR
// =========================================================

$id_parkir = (int) $_GET['id'];


// =========================================================
// MENGAMBIL DATA KENDARAAN
// =========================================================

$query = mysqli_query(
    $koneksi,
    "SELECT
        p.*,
        m.nama_member,
        a.nama_area,
        t.tarif_per_jam,
        u.nama_lengkap

    FROM tb_parkir p

    LEFT JOIN tb_member m
        ON p.id_member = m.id_member

    LEFT JOIN tb_area_parkir a
        ON p.id_area = a.id_area

    LEFT JOIN tb_tarif t
        ON p.id_tarif = t.id_tarif

    LEFT JOIN tb_user u
        ON p.id_user = u.id_user

    WHERE p.id_parkir = '$id_parkir'

    LIMIT 1"
);


// =========================================================
// CEK QUERY
// =========================================================

if (!$query) {

    die(
        "Query gagal: " .
        mysqli_error($koneksi)
    );
}


// =========================================================
// MENGAMBIL DATA PARKIR
// =========================================================

$parkir = mysqli_fetch_assoc($query);


// =========================================================
// MENGECEK DATA PARKIR
// =========================================================

if (!$parkir) {

    echo "
        <script>
            alert('Data parkir tidak ditemukan.');
            window.location='tampil_parkir.php';
        </script>
    ";

    exit;
}


// =========================================================
// WAKTU KELUAR
// =========================================================

// Waktu ini hanya ditampilkan pada halaman checkout.
// Waktu yang benar-benar disimpan ke database
// akan dibuat kembali di simpan_checkout.php.

date_default_timezone_set('Asia/Jakarta');

$waktu_keluar = date('Y-m-d H:i:s');

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Checkout Kendaraan - Sistem Parkir</title>

    <!-- CSS utama -->
    <link rel="stylesheet" href="http://localhost/parkir/style.css?v=2">

</head>

<body>

    <div class="container">


        <!-- =====================================================
         HEADER
    ====================================================== -->

        <div class="page-header">

            <div>

                <h2>Checkout Kendaraan</h2>

                <p class="page-description">
                    Proses kendaraan keluar dari area parkir.
                </p>

            </div>


            <a href="tampil_parkir.php" class="kembali">
                ← Kembali
            </a>

        </div>


        <!-- =====================================================
         INFORMASI KENDARAAN
    ====================================================== -->

        <div class="data">

            <p>
                <strong>ID Parkir:</strong>
                <?= (int) $parkir['id_parkir']; ?>
            </p>


            <p>
                <strong>Plat Nomor:</strong>
                <?= htmlspecialchars($parkir['plat_nomor']); ?>
            </p>


            <p>
                <strong>Jenis Kendaraan:</strong>
                <?= htmlspecialchars($parkir['jenis_kendaraan']); ?>
            </p>


            <p>
                <strong>Warna:</strong>
                <?= htmlspecialchars($parkir['warna_kendaraan']); ?>
            </p>


            <p>
                <strong>Member:</strong>
                <?= htmlspecialchars(
                    $parkir['nama_member'] ?? '-'
                ); ?>
            </p>


            <p>
                <strong>Area Parkir:</strong>
                <?= htmlspecialchars(
                    $parkir['nama_area'] ?? '-'
                ); ?>
            </p>


            <p>
                <strong>Waktu Masuk:</strong>
                <?= htmlspecialchars(
                    $parkir['waktu_masuk']
                ); ?>
            </p>


            <p>
                <strong>Tarif / Jam:</strong>
                Rp
                <?= number_format(
                    $parkir['tarif_per_jam'] ?? 0,
                    0,
                    ',',
                    '.'
                ); ?>
            </p>


            <p>
                <strong>Waktu Keluar:</strong>
                <?= $waktu_keluar; ?>
            </p>

        </div>


        <!-- =====================================================
         FORM CHECKOUT
    ====================================================== -->

        <!--
        checkout.php dan simpan_checkout.php
        berada di folder yang sama, yaitu parkir.

        Jadi action cukup:
        simpan_checkout.php
    -->

        <form action="simpan_checkout.php" method="POST"
            onsubmit="return confirm('Yakin kendaraan ini akan checkout?');">

            <!--
            ID PARKIR

            ID ini akan dikirim ke simpan_checkout.php
            untuk mencari kendaraan yang akan checkout.
        -->

            <input type="hidden" name="id_parkir" value="<?= (int) $parkir['id_parkir']; ?>">


            <!-- =================================================
             TOMBOL CHECKOUT
        ================================================== -->

            <button type="submit" name="checkout">
                Checkout
            </button>

        </form>


        <br>


        <a href="tampil_parkir.php" class="kembali">
            ← Kembali ke Data Parkir
        </a>


    </div>

</body>

</html>