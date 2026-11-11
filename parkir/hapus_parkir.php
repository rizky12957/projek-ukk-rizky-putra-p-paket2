<?php

// Memanggil koneksi database
require_once '../koneksi.php';

// Mengecek apakah ID dikirim melalui URL
// Contoh: hapus_parkir.php?id=1
if (isset($_GET['id'])) {

    // Mengambil ID dari URL
    $id = $_GET['id'];

    // Menghapus data parkir berdasarkan ID
    $query = mysqli_query(
        $koneksi,
        "DELETE FROM tb_parkir WHERE id_parkir='$id'"
    );

    // Jika berhasil dihapus
    if ($query) {

        // Kembali ke halaman tampil parkir
        header("Location: tampil_parkir.php");
        exit;

    } else {

        // Jika gagal
        echo "Gagal menghapus data: " . mysqli_error($koneksi);
    }

} else {

    // Jika ID tidak ada
    echo "ID parkir tidak ditemukan.";
}
?>