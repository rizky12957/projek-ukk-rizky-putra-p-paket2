<?php
// Memanggil file koneksi.php
require_once '../koneksi.php';

// Menangkap parameter ID yang dikirim dari URL
$id_user = $_GET['id'];

// Ambil data user yang sesuai dengan ID dari database untuk mengisi awal form
$res = mysqli_query($koneksi, "SELECT * FROM tb_user WHERE id_user='$id_user'");
$data = mysqli_fetch_assoc($res);

// Cek apakah tombol submit dengan name="edit" sudah ditekan
if (isset($_POST['edit'])) {
    // Menangkap inputan baru dari form
    $id_user_update = $_POST['id_user'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $status_aktif = $_POST['status_aktif'];

    // Perintah SQL untuk memperbarui data berdasarkan ID
    $query_update = "UPDATE tb_user SET 
                        nama_lengkap='$nama_lengkap', 
                        username='$username', 
                        password='$password', 
                        role='$role', 
                        status_aktif='$status_aktif' 
                     WHERE id_user='$id_user_update'";

    // Eksekusi query update
    if (mysqli_query($koneksi, $query_update)) {
        // Alihkan kembali ke tampil_user.php setelah berhasil
        header("Location: tampil_user.php");
        exit();
    } else {
        echo "Gagal mengedit data: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit User</title>
</head>

<body>

    <h2>Form Edit User</h2>

    <a href="tampil_user.php">Kembali</a>
    <br><br>

    <form action="edit_user.php?id=<?= $id_user; ?>" method="POST">
        <!-- Input hidden untuk menyimpan ID user yang tidak boleh diubah user -->
        <input type="hidden" name="id_user" value="<?= $data['id_user']; ?>">

        <table border="0" cellpadding="5">
            <tr>
                <td>Nama Lengkap</td>
                <td>: <input type="text" name="nama_lengkap" value="<?= $data['nama_lengkap']; ?>" required></td>
            </tr>
            <tr>
                <td>Username</td>
                <td>: <input type="text" name="username" value="<?= $data['username']; ?>" required></td>
            </tr>
            <tr>
                <td>Password</td>
                <td>: <input type="text" name="password" value="<?= $data['password']; ?>" required></td>
            </tr>
            <tr>
                <td>Role</td>
                <td>:
                    <!-- Pengecekan ternary untuk menentukan pilihan terpilih (selected) -->
                    <select name="role" required>
                        <option value="admin" <?= $data['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                        <option value="petugas" <?= $data['role'] == 'petugas' ? 'selected' : ''; ?>>Petugas</option>
                        <option value="owner" <?= $data['role'] == 'owner' ? 'selected' : ''; ?>>Owner</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Status Aktif</td>
                <td>:
                    <select name="status_aktif" required>
                        <option value="1" <?= $data['status_aktif'] == 1 ? 'selected' : ''; ?>>1 (Aktif)</option>
                        <option value="0" <?= $data['status_aktif'] == 0 ? 'selected' : ''; ?>>0 (Non-aktif)</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><button type="submit" name="edit">Simpan Perubahan</button></td>
            </tr>
        </table>
    </form>

</body>

</html>