<?php
// Memanggil file koneksi.php
require_once '../koneksi.php';

// Mengecek apakah ada parameter ID yang dikirimkan via URL
if (isset($_GET['id'])) {
    // Menangkap ID user yang akan dihapus
    $id_user = $_GET['id'];

    // Perintah SQL untuk menghapus baris data berdasarkan ID
    $query = "DELETE FROM tb_user WHERE id_user='$id_user'";

    // Eksekusi query hapus
    if (mysqli_query($koneksi, $query)) {
        // Jika berhasil dihapus, redirect ke halaman tampil_user.php
        header("Location: tampil_user.php");
        exit();
    } else {
        echo "Gagal menghapus data: " . mysqli_error($koneksi);
    }
} else {
    // Jika diakses langsung tanpa membawa ID, kembalikan ke halaman utama
    header("Location: tampil_user.php");
    exit();
}
?>