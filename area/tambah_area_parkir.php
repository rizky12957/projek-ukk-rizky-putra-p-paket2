<?php

// Memanggil koneksi database
require_once '../koneksi.php';

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Area Parkir</title>

    <!-- Memanggil CSS utama -->
    <link rel="stylesheet" href="../style.css">

</head>

<body>

    <div class="form-container">

        <h2>Tambah Area Parkir</h2>

        <p class="form-subtitle">
            Tambahkan area parkir baru ke dalam sistem.
        </p>

        <!-- Data dikirim ke proses penyimpanan area -->
        <form method="POST" action="simpan_area_parkir.php">

            <!-- Nama Area -->
            <div class="form-group">

                <label for="nama_area">
                    Nama Area <span class="required">*</span>
                </label>

                <input type="text" id="nama_area" name="nama_area" placeholder="Contoh: Area A" required>

            </div>

            <!-- Kapasitas Maksimal -->
            <div class="form-group">

                <label for="kapasitas">
                    Kapasitas <span class="required">*</span>
                </label>

                <input type="number" id="kapasitas" name="kapasitas" min="1" placeholder="Contoh: 50" required>

                <span class="form-help">
                    Masukkan jumlah maksimal kendaraan yang dapat ditampung.
                </span>

            </div>

            <!-- Tombol -->
            <div class="form-actions">

                <a href="tampil_area_parkir.php" class="btn-kembali">
                    Kembali
                </a>

                <button type="submit" class="btn-simpan">
                    Simpan Area
                </button>

            </div>

        </form>

    </div>

</body>

</html>