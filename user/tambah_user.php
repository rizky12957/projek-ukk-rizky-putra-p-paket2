<?php

// =========================================================
// KONEKSI DATABASE
// =========================================================

// Memanggil file koneksi.php
require_once '../koneksi.php';


// =========================================================
// PROSES TAMBAH USER
// =========================================================

// Cek apakah tombol submit dengan name="tambah" sudah ditekan
if (isset($_POST['tambah'])) {

    // Menangkap input dari form HTML
    $nama_lengkap = $_POST['nama_lengkap'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $status_aktif = $_POST['status_aktif'];

    // Perintah SQL untuk memasukkan data baru
    $query = "INSERT INTO tb_user
              (nama_lengkap, username, password, role, status_aktif)
              VALUES
              ('$nama_lengkap', '$username', '$password', '$role', '$status_aktif')";

    // Eksekusi query ke database
    if (mysqli_query($koneksi, $query)) {

        // Jika berhasil, kembali ke halaman data user
        header("Location: tampil_user.php");
        exit();

    } else {

        // Menampilkan pesan error jika query gagal
        echo "Gagal menambah data: " . mysqli_error($koneksi);
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah User</title>

    <!-- Memanggil CSS utama -->
    <link rel="stylesheet" href="../style.css">

</head>

<body>

    <!-- Container utama form -->
    <div class="form-container">

        <!-- Judul -->
        <h2>Tambah User</h2>

        <!-- Keterangan -->
        <p class="form-subtitle">
            Tambahkan akun pengguna baru ke dalam sistem parkir.
        </p>


        <!-- Form tambah user -->
        <form action="tambah_user.php" method="POST">


            <!-- Nama Lengkap -->
            <div class="form-group">

                <label for="nama_lengkap">
                    Nama Lengkap <span class="required">*</span>
                </label>

                <input type="text" id="nama_lengkap" name="nama_lengkap" placeholder="Masukkan nama lengkap" required>

            </div>


            <!-- Username -->
            <div class="form-group">

                <label for="username">
                    Username <span class="required">*</span>
                </label>

                <input type="text" id="username" name="username" placeholder="Masukkan username" required>

            </div>


            <!-- Password -->
            <div class="form-group">

                <label for="password">
                    Password <span class="required">*</span>
                </label>

                <input type="password" id="password" name="password" placeholder="Masukkan password" required>

            </div>


            <!-- Role -->
            <div class="form-group">

                <label for="role">
                    Role <span class="required">*</span>
                </label>

                <select id="role" name="role" required>

                    <option value="">
                        -- Pilih Role --
                    </option>

                    <option value="admin">
                        Admin
                    </option>

                    <option value="petugas">
                        Petugas
                    </option>

                    <option value="owner">
                        Owner
                    </option>

                </select>

            </div>


            <!-- Status Aktif -->
            <div class="form-group">

                <label for="status_aktif">
                    Status Aktif <span class="required">*</span>
                </label>

                <select id="status_aktif" name="status_aktif" required>

                    <option value="1">
                        Aktif
                    </option>

                    <option value="0">
                        Non-aktif
                    </option>

                </select>

            </div>


            <!-- Tombol -->
            <div class="form-actions">

                <!-- Tombol kembali -->
                <a href="tampil_user.php" class="btn-kembali">
                    Kembali
                </a>


                <!-- Tombol simpan -->
                <button type="submit" name="tambah" class="btn-simpan">
                    Simpan User
                </button>

            </div>

        </form>

    </div>

</body>

</html>