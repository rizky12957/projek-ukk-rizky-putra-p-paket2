<?php

// Memanggil file koneksi database
require_once '../koneksi.php';

// Mengecek apakah ID dikirim melalui URL
// Contoh: hapus_tarif.php?id=1
if (isset($_GET['id'])) {

    // Mengambil ID tarif dari URL
    $id = $_GET['id'];

    // Menghapus data tarif berdasarkan ID
    $query = mysqli_query(
        $koneksi,
        "DELETE FROM tb_tarif WHERE id_tarif='$id'"
    );

    // Mengecek apakah data berhasil dihapus
    if ($query) {

        // Jika berhasil, kembali ke halaman tampil tarif
        header("Location: tampil_tarif.php");
        exit;

    } else {

        // Jika gagal, tampilkan pesan error
        echo "Data gagal dihapus: " . mysqli_error($koneksi);
    }
}

?>