<?php

// Memanggil koneksi database
require_once '../koneksi.php';

?>

<!DOCTYPE html>
<html>

<head>

    <!-- Judul halaman -->
    <title>Tambah Area Parkir</title>

</head>

<body>

    <!-- Judul halaman -->
    <h2>Tambah Area Parkir</h2>

    <!--
        Form digunakan untuk memasukkan data area parkir.

        method="POST" berarti data dikirim menggunakan POST.

        action="simpan_area_parkir.php" berarti data akan
        dikirim ke file simpan_area_parkir.php.
    -->
    <form method="POST" action="simpan_area_parkir.php">

        <!-- Input nama area -->
        <label>Nama Area</label><br>

        <!--
            name="nama_area" digunakan agar PHP dapat
            mengambil data dengan $_POST['nama_area'].
        -->
        <input type="text" name="nama_area" required>

        <br><br>

        <!-- Input kapasitas area parkir -->
        <label>Kapasitas</label><br>

        <!--
            type="number" digunakan untuk memasukkan angka.
        -->
        <input type="number" name="kapasitas" required>

        <br><br>

        <!-- Input jumlah slot yang sudah terisi -->
        <label>Terisi</label><br>

        <!--
            name="terisi" digunakan agar PHP dapat
            mengambil data dengan $_POST['terisi'].
        -->
        <input type="number" name="terisi" required>

        <br><br>

        <!-- Tombol untuk menyimpan data -->
        <button type="submit">Simpan</button>

        <!-- Tombol untuk kembali ke halaman data -->
        <a href="tampil_area_parkir.php">Kembali</a>

    </form>

</body>

</html>