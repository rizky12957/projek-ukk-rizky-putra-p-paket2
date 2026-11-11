<?php

// Memanggil koneksi database
require_once '../koneksi.php';

// Mengecek apakah ID dikirim melalui URL
// Contoh: hapus_log.php?id=1
if (isset($_GET['id'])) {

    // Mengambil ID dari URL
    $id = $_GET['id'];

    // Menghapus data berdasarkan ID log
    $query = mysqli_query(
        $koneksi,
        "DELETE FROM tb_log_aktivitas WHERE id_log='$id'"
    );

    // Jika berhasil
    if ($query) {

        // Kembali ke halaman tampil log
        header("Location: tampil_log.php");
        exit;

    } else {

        // Jika gagal
        echo "Gagal menghapus log: " . mysqli_error($koneksi);
    }

} else {

    // Jika ID tidak dikirim
    echo "ID log tidak ditemukan.";
}

?>