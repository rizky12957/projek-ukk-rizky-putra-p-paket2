```php
<?php

// Memanggil file koneksi database
require_once '../koneksi.php';

// Mengambil data nama area yang dikirim dari form
$nama_area = $_POST['nama_area'];

// Mengambil data kapasitas yang dikirim dari form
$kapasitas = $_POST['kapasitas'];

// Mengambil data jumlah yang sudah terisi
$terisi = $_POST['terisi'];

// Menyimpan data ke tabel tb_area_parkir
$query = mysqli_query(
    $koneksi,
    "INSERT INTO tb_area_parkir
    (nama_area, kapasitas, terisi)
    VALUES
    ('$nama_area', '$kapasitas', '$terisi')"
);

// Mengecek apakah proses penyimpanan berhasil
if ($query) {

    // Jika berhasil, kembali ke halaman tampil_area_parkir.php
    header("Location: tampil_area_parkir.php");

    // Menghentikan proses PHP
    exit;

} else {

    // Jika gagal, tampilkan pesan error
    echo "Data gagal disimpan: " . mysqli_error($koneksi);

}

?>