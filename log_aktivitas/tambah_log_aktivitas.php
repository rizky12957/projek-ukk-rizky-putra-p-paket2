<?php

// Memanggil koneksi database
require_once '../koneksi.php';

// Mengambil data user dari tb_user
// Data ini digunakan untuk membuat dropdown user
$query_user = mysqli_query(
    $koneksi,
    "SELECT * FROM tb_user"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Log Aktivitas</title>
</head>

<body>

    <h2>Tambah Log Aktivitas</h2>

    <!-- Form mengirim data ke simpan_log.php -->
    <form action="simpan_log.php" method="POST">

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

        <!-- Mengisi aktivitas yang dilakukan -->
        <label>Aktivitas</label><br>

        <textarea name="aktivitas" rows="4" cols="40" placeholder="Contoh: Menambahkan data member" required></textarea>

        <br><br>

        <!--
        waktu_aktivitas tidak perlu diisi.
        Database sudah memiliki DEFAULT current_timestamp()
        sehingga waktu akan otomatis menggunakan waktu saat data dibuat.
    -->

        <!-- Tombol simpan -->
        <button type="submit" name="simpan">
            Simpan
        </button>

    </form>

    <br>

    <!-- Kembali ke halaman tampil -->
    <a href="tampil_log.php">Kembali</a>

</body>

</html>