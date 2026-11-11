<?php

// Memanggil koneksi database
require_once '../koneksi.php';

// Mengecek apakah ID member dikirim melalui URL
// Contoh: hapus_member.php?id=1
if (isset($_GET['id'])) {

    // Mengambil ID dari URL
    $id = $_GET['id'];

    // Menghapus data member berdasarkan ID
    $query = mysqli_query(
        $koneksi,
        "DELETE FROM tb_member WHERE id_member='$id'"
    );

    // Jika berhasil dihapus
    if ($query) {

        // Kembali ke halaman data member
        header("Location: tampil_member.php");
        exit;

    } else {

        // Jika gagal menghapus
        echo "Gagal menghapus data: " . mysqli_error($koneksi);
    }

} else {

    // Jika ID tidak dikirim
    echo "ID member tidak ditemukan.";
}
?>