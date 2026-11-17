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
// TIMEZONE
// =========================================================

date_default_timezone_set('Asia/Jakarta');


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

$query = "
    SELECT
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

    WHERE p.id_parkir = ?

    LIMIT 1
";

$stmt = mysqli_prepare($koneksi, $query);

if (!$stmt) {
    die("Query gagal: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, "i", $id_parkir);
mysqli_stmt_execute($stmt);

$hasil = mysqli_stmt_get_result($stmt);
$parkir = mysqli_fetch_assoc($hasil);

mysqli_stmt_close($stmt);


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
// CEK SUDAH CHECKOUT ATAU BELUM
// =========================================================

$stmt_cek = mysqli_prepare(
    $koneksi,
    "SELECT id_transaksi FROM tb_transaksi WHERE id_parkir = ? LIMIT 1"
);

mysqli_stmt_bind_param($stmt_cek, "i", $id_parkir);
mysqli_stmt_execute($stmt_cek);

$hasil_cek = mysqli_stmt_get_result($stmt_cek);
$sudah_checkout = mysqli_num_rows($hasil_cek) > 0;

mysqli_stmt_close($stmt_cek);

if ($sudah_checkout) {

    echo "
        <script>
            alert('Kendaraan ini sudah melakukan checkout.');
            window.location='tampil_parkir.php';
        </script>
    ";

    exit;
}


// =========================================================
// WAKTU KELUAR SEMENTARA
// =========================================================

$waktu_keluar = date('Y-m-d H:i:s');


// =========================================================
// MENGHITUNG ESTIMASI DURASI
// =========================================================

$waktu_masuk_timestamp = strtotime($parkir['waktu_masuk']);
$waktu_keluar_timestamp = strtotime($waktu_keluar);

$selisih = max(
    0,
    $waktu_keluar_timestamp - $waktu_masuk_timestamp
);

$durasi_jam = max(1, ceil($selisih / 3600));


// =========================================================
// MENGHITUNG ESTIMASI BIAYA
// =========================================================

$tarif = (int) ($parkir['tarif_per_jam'] ?? 0);

$estimasi_biaya = $durasi_jam * $tarif;

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Checkout Kendaraan - Sistem Parkir</title>

    <!-- CSS utama -->
    <link rel="stylesheet" href="../style.css?v=10">

</head>

<body>

    <div class="container">

        <!-- HEADER -->
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


        <!-- INFORMASI KENDARAAN -->
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
                <?= htmlspecialchars($parkir['nama_member'] ?? '-'); ?>
            </p>

            <p>
                <strong>Area Parkir:</strong>
                <?= htmlspecialchars($parkir['nama_area'] ?? '-'); ?>
            </p>

            <p>
                <strong>Waktu Masuk:</strong>
                <?= htmlspecialchars($parkir['waktu_masuk']); ?>
            </p>

            <p>
                <strong>Tarif / Jam:</strong>
                Rp <?= number_format($tarif, 0, ',', '.'); ?>
            </p>

            <p>
                <strong>Waktu Keluar:</strong>
                <?= htmlspecialchars($waktu_keluar); ?>
            </p>

        </div>


        <!-- ESTIMASI PEMBAYARAN -->
        <div class="data">

            <h3>Estimasi Pembayaran</h3>

            <p>
                <strong>Durasi Parkir:</strong>
                <?= $durasi_jam; ?> jam
            </p>

            <p>
                <strong>Estimasi Total Biaya:</strong>
                Rp <?= number_format($estimasi_biaya, 0, ',', '.'); ?>
            </p>

            <small>
                Estimasi dapat berubah mengikuti waktu checkout sebenarnya.
            </small>

        </div>


        <!-- FORM CHECKOUT -->
        <form action="simpan_checkout.php" method="POST"
            onsubmit="return confirm('Yakin kendaraan ini akan checkout?');">

            <input type="hidden" name="id_parkir" value="<?= (int) $parkir['id_parkir']; ?>">

            <button type="submit" name="checkout">
                Checkout Sekarang
            </button>

        </form>

        <br>

        <a href="tampil_parkir.php" class="kembali">
            ← Kembali ke Data Parkir
        </a>

    </div>

</body>

</html>