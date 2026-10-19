<?php

// Memanggil koneksi database
require_once '../koneksi.php';

// Mengecek apakah form dikirim menggunakan POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Mengambil ID user dari form
    $id_user = $_POST['id_user'];

    // Mengambil aktivitas dari form
    $aktivitas = $_POST['aktivitas'];

    /*
        id_log tidak perlu dimasukkan karena
        kolom id_log menggunakan AUTO_INCREMENT.

        waktu_aktivitas juga tidak perlu dimasukkan karena
        database sudah memberikan waktu secara otomatis.
    */

    // Query untuk menambahkan log aktivitas
    $query = "INSERT INTO tb_log_aktivitas
              (id_user, aktivitas)
              VALUES (?, ?)";

    // Menyiapkan query
    $stmt = mysqli_prepare($koneksi, $query);

    // Menghubungkan data dengan query
    mysqli_stmt_bind_param(
        $stmt,
        "is",
        $id_user,
        $aktivitas
    );

    // Menjalankan query
    if (mysqli_stmt_execute($stmt)) {

        // Jika berhasil, kembali ke halaman log
        header("Location: tampil_log.php");
        exit;

    } else {

        // Jika gagal, tampilkan pesan error
        echo "Gagal menyimpan log: " . mysqli_error($koneksi);
    }
}
?>