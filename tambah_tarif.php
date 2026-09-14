<?php

// Memanggil koneksi database
require_once '../koneksi.php';

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <!-- Judul halaman -->
    <title>Tambah Tarif</title>
</head>

<body>

    <!-- Judul halaman -->
    <h2>Tambah Data Tarif</h2>

    <!--
        Form digunakan untuk memasukkan data tarif.
        Data akan dikirim ke simpan_tarif.php
        menggunakan method POST.
    -->
    <form action="simpan_tarif.php" method="POST">

        <!-- Memilih jenis kendaraan -->
        <label>Jenis Kendaraan</label><br>

        <select name="jenis_kendaraan" required>

            <!-- Pilihan awal -->
            <option value="">
                -- Pilih Jenis Kendaraan --
            </option>

            <!-- Pilihan motor -->
            <option value="motor">
                Motor
            </option>

            <!-- Pilihan mobil -->
            <option value="mobil">
                Mobil
            </option>

        </select>

        <br><br>


        <!--
            Input tarif parkir per jam.
            Contoh:
            5000 berarti tarif Rp5.000 per jam.
        -->
        <label>Tarif Per Jam</label><br>

        <input type="number" name="tarif_per_jam" min="0" placeholder="Masukkan tarif" required>

        <br><br>


        <!--
            Tombol Simpan.
            Ketika ditekan, data akan dikirim
            ke file simpan_tarif.php.
        -->
        <button type="submit" name="simpan">
            Simpan
        </button>

    </form>

    <br>

    <!--
        Tombol/link untuk kembali
        ke halaman tampil tarif.
    -->
    <a href="tampil_tarif.php">
        Kembali
    </a>

</body>

</html>