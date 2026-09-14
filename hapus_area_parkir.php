<?php

// Memanggil file koneksi database
require_once '../koneksi.php';


// Mengambil ID dari URL
// Contoh:
// hapus_area.php?id=3
$id = $_GET['id'];


// Menghapus data dari tabel tb_area_parkir
// berdasarkan id_area yang diterima
$query = mysqli_query(
    $koneksi,
    "DELETE FROM tb_area_parkir WHERE id_area='$id'"
);


// Mengecek apakah data berhasil dihapus
if ($query) {

    // Jika berhasil, kembali ke halaman data area parkir
    header("Location: tampil_area_parkir.php");

    // Menghentikan proses PHP
    exit;

} else {

    // Jika gagal menghapus
    echo "Data gagal dihapus";

}

?>