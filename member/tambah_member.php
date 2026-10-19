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

// Mengambil nama pengguna yang sedang login.
$nama_user = $_SESSION['nama_lengkap']
    ?? $_SESSION['username']
    ?? 'Pengguna';

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Member</title>

    <!-- Memanggil CSS utama -->
    <link rel="stylesheet" href="../style.css">
</head>

<body>

    <!-- CONTAINER FORM -->
    <div class="form-container">

        <h2>Tambah Member</h2>

        <p class="form-subtitle">
            Tambahkan data member baru ke dalam sistem parkir.
        </p>

        <!-- FORM TAMBAH MEMBER -->
        <form action="simpan_member.php" method="POST">

            <!-- NAMA MEMBER -->
            <div class="form-group">
                <label for="nama_member">
                    Nama Member <span class="required">*</span>
                </label>

                <input type="text" id="nama_member" name="nama_member" placeholder="Masukkan nama member"
                    maxlength="100" required>
            </div>

            <!-- JENIS KELAMIN -->
            <div class="form-group">
                <label for="jenis_kelamin">
                    Jenis Kelamin <span class="required">*</span>
                </label>

                <select id="jenis_kelamin" name="jenis_kelamin" required>
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>

            <!-- NOMOR HP -->
            <div class="form-group">
                <label for="no_hp">No HP</label>

                <input type="text" id="no_hp" name="no_hp" placeholder="Contoh: 081234567890" maxlength="20">
            </div>

            <!-- TANGGAL DAFTAR -->
            <div class="form-group">
                <label for="tanggal_daftar">
                    Tanggal Daftar <span class="required">*</span>
                </label>

                <input type="date" id="tanggal_daftar" name="tanggal_daftar" value="<?= date('Y-m-d'); ?>" required>
            </div>

            <!-- STATUS MEMBER -->
            <div class="form-group">
                <label for="status_aktif">
                    Status Aktif <span class="required">*</span>
                </label>

                <select id="status_aktif" name="status_aktif" required>
                    <option value="aktif">Aktif</option>
                    <option value="suspended">Suspended</option>
                </select>
            </div>

            <!-- PETUGAS OTOMATIS DARI SESSION LOGIN -->
            <div class="form-group">
                <label for="nama_user">
                    Petugas yang Mendaftarkan
                </label>

                <input type="text" id="nama_user" value="<?= htmlspecialchars($nama_user); ?>" readonly>

                <span class="form-help">
                    Otomatis mengikuti akun yang sedang login.
                </span>
            </div>

            <!-- TOMBOL -->
            <div class="form-actions">

                <a href="tampil_member.php" class="btn-kembali">
                    Kembali
                </a>

                <button type="submit" name="simpan" class="btn-simpan">
                    Simpan Member
                </button>

            </div>

        </form>

    </div>

</body>

</html>