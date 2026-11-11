<?php

// Memanggil koneksi database
require_once '../koneksi.php';

// Mengambil data parkir yang BELUM memiliki transaksi
// NOT EXISTS digunakan supaya id_parkir yang sudah ada di tb_transaksi tidak muncul
$query_parkir = mysqli_query(
    $koneksi,
    "SELECT * FROM tb_parkir p
     WHERE NOT EXISTS (
         SELECT 1
         FROM tb_transaksi t
         WHERE t.id_parkir = p.id_parkir
     )"
);

// Mengambil data user untuk dropdown
$query_user = mysqli_query(
    $koneksi,
    "SELECT * FROM tb_user"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Transaksi</title>
</head>

<body>

    <h2>Tambah Transaksi</h2>

    <!-- Form mengirim data ke simpan_transaksi.php -->
    <form action="simpan_transaksi.php" method="POST">

        <!-- Memilih data parkir -->
        <label>Parkir</label><br>

        <select name="id_parkir" required>

            <option value="">-- Pilih Kendaraan --</option>

            <?php while ($parkir = mysqli_fetch_assoc($query_parkir)) { ?>

                <!--
                    Menampilkan kendaraan yang belum memiliki transaksi
                    sehingga tidak terjadi Duplicate entry pada id_parkir
                -->
                <option value="<?= $parkir['id_parkir']; ?>">
                    <?= $parkir['plat_nomor']; ?>
                    -
                    <?= $parkir['jenis_kendaraan']; ?>
                </option>

            <?php } ?>

        </select>

        <br><br>

        <!-- Input waktu keluar -->
        <label>Waktu Keluar</label><br>

        <input type="datetime-local" name="waktu_keluar" required>

        <br><br>

        <!-- Input durasi parkir -->
        <label>Durasi (Jam)</label><br>

        <input type="number" name="durasi_jam" min="1" required>

        <br><br>

        <!-- Input biaya total -->
        <label>Biaya Total</label><br>

        <input type="number" name="biaya_total" min="0" placeholder="Contoh: 10000" required>

        <br><br>

        <!-- Memilih user -->
        <label>User</label><br>

        <select name="id_user" required>

            <option value="">-- Pilih User --</option>

            <?php while ($user = mysqli_fetch_assoc($query_user)) { ?>

                <option value="<?= $user['id_user']; ?>">
                    <?= $user['nama_lengkap']; ?>
                </option>

            <?php } ?>

        </select>

        <br><br>

        <!-- Tombol simpan -->
        <button type="submit" name="simpan">
            Simpan
        </button>

    </form>

    <br>

    <!-- Kembali ke halaman transaksi -->
    <a href="tampil_transaksi.php">Kembali</a>

</body>

</html>