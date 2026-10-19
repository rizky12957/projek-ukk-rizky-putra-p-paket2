<?php

// Memanggil koneksi database
require_once '../koneksi.php';

// Mengecek apakah form dikirim menggunakan POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Mengambil data dari form
    $nama_member = $_POST['nama_member'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $no_hp = $_POST['no_hp'];
    $tanggal_daftar = $_POST['tanggal_daftar'];
    $status_aktif = $_POST['status_aktif'];

    // Jika user tidak dipilih, maka nilainya NULL
    $id_user = !empty($_POST['id_user']) ? $_POST['id_user'] : NULL;

    // Jika area tidak dipilih, maka nilainya NULL
    $id_area = !empty($_POST['id_area']) ? $_POST['id_area'] : NULL;

    // Query untuk memasukkan data member
    $query = "INSERT INTO tb_member
        (nama_member, jenis_kelamin, no_hp, tanggal_daftar, status_aktif, id_user, id_area)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

    // Menyiapkan query
    $stmt = mysqli_prepare($koneksi, $query);

    // Menghubungkan data dengan query
    mysqli_stmt_bind_param(
        $stmt,
        "ssssssi",
        $nama_member,
        $jenis_kelamin,
        $no_hp,
        $tanggal_daftar,
        $status_aktif,
        $id_user,
        $id_area
    );

    // Menjalankan query
    if (mysqli_stmt_execute($stmt)) {

        // Jika berhasil, kembali ke halaman tampil member
        header("Location: tampil_member.php");
        exit;

    } else {

        // Jika gagal, tampilkan pesan error
        echo "Gagal menyimpan data: " . mysqli_error($koneksi);
    }
}
?>