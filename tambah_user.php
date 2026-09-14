<?php
// Memanggil file koneksi.php
require_once '../koneksi.php';

// Cek apakah tombol submit dengan name="tambah" sudah ditekan
if (isset($_POST['tambah'])) {
    // Menangkap input dari form HTML
    $nama_lengkap = $_POST['nama_lengkap'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $status_aktif = $_POST['status_aktif'];

    // Perintah SQL untuk memasukkan data baru
    $query = "INSERT INTO tb_user (nama_lengkap, username, password, role, status_aktif) 
              VALUES ('$nama_lengkap', '$username', '$password', '$role', '$status_aktif')";

    // Eksekusi query ke database
    if (mysqli_query($koneksi, $query)) {
        // Jika berhasil, alihkan kembali ke halaman utama
        header("Location: tampil_user.php");
        exit();
    } else {
        // Menampilkan pesan error jika query gagal
        echo "Gagal menambah data: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Tambah User</title>
</head>

<body>

    <h2>Form Tambah User</h2>

    <!-- Link kembali ke halaman utama -->
    <a href="tampil_user.php">Kembali</a>
    <br><br>

    <!-- Form pengiriman data menggunakan method POST -->
    <form action="tambah_user.php" method="POST">
        <table border="0" cellpadding="5">
            <tr>
                <td>Nama Lengkap</td>
                <td>: <input type="text" name="nama_lengkap" required></td>
            </tr>
            <tr>
                <td>Username</td>
                <td>: <input type="text" name="username" required></td>
            </tr>
            <tr>
                <td>Password</td>
                <td>: <input type="password" name="password" required></td>
            </tr>
            <tr>
                <td>Role</td>
                <td>:
                    <select name="role" required>
                        <option value="admin">Admin</option>
                        <option value="petugas">Petugas</option>
                        <option value="owner">Owner</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Status Aktif</td>
                <td>:
                    <select name="status_aktif" required>
                        <option value="1">1 (Aktif)</option>
                        <option value="0">0 (Non-aktif)</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><button type="submit" name="tambah">Simpan Data</button></td>
            </tr>
        </table>
    </form>

</body>

</html>