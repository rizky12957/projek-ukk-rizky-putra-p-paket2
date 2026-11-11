<?php

// Memanggil koneksi database
require_once 'koneksi.php';

// Memulai session jika belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Mengecek apakah form login dikirim
if (!isset($_POST['login'])) {

    // Jika file dibuka langsung,
    // kembali ke login
    header("Location: login.php");
    exit;
}

// Mengambil username
$username = $_POST['username'];

// Mengambil password
$password = $_POST['password'];

// Mencari user berdasarkan username
$query = mysqli_query(
    $koneksi,
    "SELECT * FROM tb_user
     WHERE username='$username'
     LIMIT 1"
);

// Mengambil data user
$user = mysqli_fetch_assoc($query);

// Mengecek username
if (!$user) {

    $_SESSION['error_login'] = "Username tidak ditemukan.";

    header("Location: login.php");
    exit;
}

// Mengecek password
if ($password != $user['password']) {

    $_SESSION['error_login'] = "Password salah.";

    header("Location: login.php");
    exit;
}

// Mengecek status akun
if ($user['status_aktif'] != 1) {

    $_SESSION['error_login'] = "Akun tidak aktif.";

    header("Location: login.php");
    exit;
}

/*
    Jika semua pemeriksaan berhasil,
    simpan data user ke session.
*/

$_SESSION['login'] = true;

$_SESSION['id_user'] = $user['id_user'];

$_SESSION['nama_lengkap'] = $user['nama_lengkap'];

$_SESSION['username'] = $user['username'];

$_SESSION['role'] = $user['role'];

// Mengarahkan ke dashboard
header("Location: dashboard.php");
exit;

?>