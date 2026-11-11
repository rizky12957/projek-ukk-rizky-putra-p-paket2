<?php

// Memanggil koneksi database
require_once '../koneksi.php';

// Mengecek apakah form dikirim menggunakan POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Mengambil data dari form
    $id_parkir = $_POST['id_parkir'];
    $waktu_keluar = $_POST['waktu_keluar'];
    $durasi_jam = $_POST['durasi_jam'];
    $biaya_total = $_POST['biaya_total'];
    $id_user = $_POST['id_user'];

    // Query untuk menambahkan transaksi
    $query = "INSERT INTO tb_transaksi
        (id_parkir, waktu_keluar, durasi_jam, biaya_total, id_user)
        VALUES (?, ?, ?, ?, ?)";

    // Menyiapkan query
    $stmt = mysqli_prepare($koneksi, $query);

    // Menghubungkan data dengan query
    mysqli_stmt_bind_param(
        $stmt,
        "isidi",
        $id_parkir,
        $waktu_keluar,
        $durasi_jam,
        $biaya_total,
        $id_user
    );

    // Menjalankan query
    if (mysqli_stmt_execute($stmt)) {

        // Jika berhasil, kembali ke halaman transaksi
        header("Location: tampil_transaksi.php");
        exit;

    } else {

        // Jika gagal
        echo "Gagal menyimpan transaksi: " . mysqli_error($koneksi);
    }
}

?>