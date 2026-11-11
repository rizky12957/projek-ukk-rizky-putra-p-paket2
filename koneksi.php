<?php

// =========================================================
// TIMEZONE
// =========================================================

// Mengatur waktu PHP ke waktu Indonesia Barat (WIB)
date_default_timezone_set('Asia/Jakarta');


// =========================================================
// SESSION
// =========================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// =========================================================
// KONEKSI DATABASE
// =========================================================

$host = "localhost";
$user = "root";
$pass = "";
$db = "xiirpl1_rizky_putra_paket2";


// Membuat koneksi ke database
$koneksi = mysqli_connect(
    $host,
    $user,
    $pass,
    $db
);


// =========================================================
// CEK KONEKSI
// =========================================================

if (!$koneksi) {

    die(
        "Koneksi database gagal: "
        . mysqli_connect_error()
    );
}


// =========================================================
// TIMEZONE DATABASE MYSQL
// =========================================================

// Mengatur timezone MySQL menjadi UTC+7 (WIB)
mysqli_query(
    $koneksi,
    "SET time_zone = '+07:00'"
);

?>