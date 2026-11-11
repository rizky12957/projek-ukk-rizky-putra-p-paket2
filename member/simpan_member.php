<?php

// =========================================================
// SESSION DAN KONEKSI DATABASE
// =========================================================

// Memulai session jika belum aktif.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Memastikan pengguna sudah login.
if (!isset($_SESSION['login'])) {
    header("Location: ../index.php");
    exit;
}

// Memanggil koneksi database.
require_once '../koneksi.php';

// =========================================================
// MENGECEK FORM
// =========================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: tambah_member.php");
    exit;
}

// =========================================================
// MENGAMBIL ID USER DARI AKUN YANG LOGIN
// =========================================================

// ID user tidak diambil dari form agar tidak bisa diganti
// dengan memilih atau mengirim ID pengguna lain.
$id_user = $_SESSION['id_user'] ?? null;

// Memastikan ID user tersedia.
if (!$id_user) {
    die(
        "ID user tidak ditemukan. " .
        "Pastikan proses login menyimpan id_user ke session."
    );
}

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

// id_user diisi otomatis dari session login.
// id_area diisi NULL karena tidak dipilih pada form.
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

// Menyiapkan query.
$stmt = mysqli_prepare($koneksi, $query);

if (!$stmt) {
    die("Gagal menyiapkan query: " . mysqli_error($koneksi));
}

// Menghubungkan data dengan query.
// Enam parameter: lima string dan satu integer.
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

    // Menutup statement sebelum pindah halaman.
    mysqli_stmt_close($stmt);

    // Kembali ke halaman data member.
    header("Location: tampil_member.php?pesan=berhasil");
    exit;

} else {

    // Menampilkan pesan jika penyimpanan gagal.
    $error = mysqli_stmt_error($stmt);
    mysqli_stmt_close($stmt);

    die("Gagal menyimpan data member: " . htmlspecialchars($error));
}

?>