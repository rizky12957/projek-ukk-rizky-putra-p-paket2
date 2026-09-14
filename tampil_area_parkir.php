<?php

// Memanggil file koneksi.php
// File ini digunakan untuk menghubungkan PHP dengan database
require_once '../koneksi.php';

// Mengambil semua data dari tabel tb_area_parkir
$query = mysqli_query($koneksi, "SELECT * FROM tb_area_parkir");

?>

<!DOCTYPE html>
<html>

<head>

    <!-- Judul halaman yang muncul di tab browser -->
    <title>Data Area Parkir</title>

</head>

<body>

    <!-- Judul halaman -->
    <h2>Data Area Parkir</h2>

    <!-- Tombol untuk menuju halaman tambah area parkir -->
    <a href="tambah_area_parkir.php">+ Tambah Area</a>

    <br><br>

    <!-- Membuat tabel untuk menampilkan data -->
    <table border="1" cellpadding="8" cellspacing="0">

        <!-- Baris untuk judul kolom -->
        <tr>

            <!-- Kolom ID area -->
            <th>ID Area</th>

            <!-- Kolom nama area -->
            <th>Nama Area</th>

            <!-- Kolom kapasitas -->
            <th>Kapasitas</th>

            <!-- Kolom jumlah yang sudah terisi -->
            <th>Terisi</th>

            <!-- Kolom jumlah slot yang masih tersedia -->
            <th>Sisa Slot</th>

            <!-- Kolom untuk tombol edit dan hapus -->
            <th>Aksi</th>

        </tr>

        <?php

        // Mengambil data dari database satu per satu
        while ($data = mysqli_fetch_assoc($query)) {

            // Menghitung jumlah slot yang masih tersedia
            // Rumus: kapasitas - terisi
            $sisa = $data['kapasitas'] - $data['terisi'];

            ?>

            <!-- Membuat baris untuk setiap data area parkir -->
            <tr>

                <!-- Menampilkan ID area -->
                <td><?= $data['id_area']; ?></td>

                <!-- Menampilkan nama area -->
                <td><?= $data['nama_area']; ?></td>

                <!-- Menampilkan kapasitas area -->
                <td><?= $data['kapasitas']; ?></td>

                <!-- Menampilkan jumlah slot yang sudah terisi -->
                <td><?= $data['terisi']; ?></td>

                <!-- Menampilkan hasil perhitungan sisa slot -->
                <td><?= $sisa; ?></td>

                <td>

                    <!--
                    Tombol Edit.
                    ID area dikirim menggunakan GET.
                    Contoh:
                    edit_area.php?id=1
                -->
                    <a href="edit_area_parkir.php?id=<?= $data['id_area']; ?>">
                        Edit
                    </a>

                    |

                    <!--
                    Tombol Hapus.
                    ID area dikirim ke file hapus_area_parkir.php.
                    confirm() digunakan untuk meminta konfirmasi
                    sebelum data benar-benar dihapus.
                -->
                    <a href="hapus_area_parkir.php?id=<?= $data['id_area']; ?>"
                        onclick="return confirm('Yakin ingin menghapus data ini?')">
                        Hapus
                    </a>

                </td>

            </tr>

            <?php

            // Mengakhiri perulangan while
        }

        ?>

        <!-- Menutup tabel -->
    </table>

</body>

</html>