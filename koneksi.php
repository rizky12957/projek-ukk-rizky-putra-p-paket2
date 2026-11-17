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
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Mengatur karakter database
mysqli_set_charset($koneksi, "utf8mb4");


// =========================================================
// TIMEZONE DATABASE MYSQL
// =========================================================

mysqli_query($koneksi, "SET time_zone = '+07:00'");


// =========================================================
// FUNGSI LOG AKTIVITAS OTOMATIS
// =========================================================

function catatAktivitas($koneksi, $aktivitas)
{
    // Pastikan pengguna sudah login
    if (!isset($_SESSION['id_user'])) {
        return false;
    }

    // Mengambil ID pengguna yang sedang login
    $id_user = (int) $_SESSION['id_user'];

    // Menyiapkan query penyimpanan log
    $query = mysqli_prepare(
        $koneksi,
        "INSERT INTO tb_log_aktivitas
        (id_user, aktivitas, waktu_aktivitas)
        VALUES (?, ?, NOW())"
    );

    // Memeriksa apakah query berhasil disiapkan
    if (!$query) {
        error_log("Gagal menyiapkan log aktivitas: " . mysqli_error($koneksi));
        return false;
    }

    // Mengikat parameter
    mysqli_stmt_bind_param($query, "is", $id_user, $aktivitas);

    // Menjalankan query
    $hasil = mysqli_stmt_execute($query);

    // Mencatat kesalahan jika penyimpanan gagal
    if (!$hasil) {
        error_log("Gagal menyimpan log aktivitas: " . mysqli_stmt_error($query));
    }

    mysqli_stmt_close($query);

    return $hasil;
}

?>