<?php

// =========================================================
// SESSION DAN KONEKSI DATABASE
// =========================================================

// Memanggil koneksi database.
// File koneksi.php juga mengaktifkan session.
require_once '../koneksi.php';


// =========================================================
// CEK LOGIN
// =========================================================

if (!isset($_SESSION['login'])) {
    header("Location: ../index.php");
    exit;
}


// =========================================================
// MENGECEK FORM
// =========================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: tambah_member.php");
    exit;
}


// =========================================================
// MENGAMBIL ID USER DARI SESSION
// =========================================================

$id_user = $_SESSION['id_user'] ?? null;

if (!$id_user) {
    die("ID user tidak ditemukan. Silakan login kembali.");
}

$id_user = (int) $id_user;


// =========================================================
// MENGAMBIL DATA DARI FORM
// =========================================================

$nama_member = trim($_POST['nama_member'] ?? '');
$jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
$no_hp = trim($_POST['no_hp'] ?? '');
$tanggal_daftar = $_POST['tanggal_daftar'] ?? date('Y-m-d');
$status_aktif = $_POST['status_aktif'] ?? 'aktif';


// =========================================================
// VALIDASI DATA
// =========================================================

if (
    $nama_member === '' ||
    !in_array($jenis_kelamin, ['L', 'P'], true) ||
    !in_array($status_aktif, ['aktif', 'suspended'], true)
) {
    die("Data member tidak valid. Silakan periksa kembali formulir.");
}


// Memastikan format tanggal benar.
$tanggal = DateTime::createFromFormat('!Y-m-d', $tanggal_daftar);

if (!$tanggal || $tanggal->format('Y-m-d') !== $tanggal_daftar) {
    die("Tanggal daftar tidak valid.");
}


// =========================================================
// MENYIMPAN DATA MEMBER
// =========================================================

$query = "INSERT INTO tb_member
    (
        nama_member,
        jenis_kelamin,
        no_hp,
        tanggal_daftar,
        status_aktif,
        id_user,
        id_area
    )
    VALUES (?, ?, ?, ?, ?, ?, NULL)";

$stmt = mysqli_prepare($koneksi, $query);

if (!$stmt) {
    die("Gagal menyiapkan query: " . mysqli_error($koneksi));
}


// Menghubungkan data dengan query.
mysqli_stmt_bind_param(
    $stmt,
    "sssssi",
    $nama_member,
    $jenis_kelamin,
    $no_hp,
    $tanggal_daftar,
    $status_aktif,
    $id_user
);


// =========================================================
// MENJALANKAN QUERY
// =========================================================

if (mysqli_stmt_execute($stmt)) {

    // Menutup statement setelah penyimpanan berhasil.
    mysqli_stmt_close($stmt);


    // =====================================================
    // LOG AKTIVITAS OTOMATIS
    // =====================================================

    // Mencatat nama member yang baru ditambahkan.
    $aktivitas = "Menambahkan member baru bernama " . $nama_member;

    // Menyimpan aktivitas ke tb_log_aktivitas.
    catatAktivitas($koneksi, $aktivitas);


    // =====================================================
    // REDIRECT
    // =====================================================

    header("Location: tampil_member.php?pesan=berhasil");
    exit;

} else {

    // Mengambil pesan kesalahan.
    $error = mysqli_stmt_error($stmt);

    mysqli_stmt_close($stmt);

    die("Gagal menyimpan data member: " . htmlspecialchars($error));
}

?>