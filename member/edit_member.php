<?php

// Memanggil koneksi database
require_once '../koneksi.php';

/*
    Jika tombol Simpan ditekan,
    data akan diproses menggunakan POST.
*/
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Mengambil ID member
    $id_member = $_POST['id_member'];

    // Mengambil data dari form
    $nama_member = $_POST['nama_member'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $no_hp = $_POST['no_hp'];
    $tanggal_daftar = $_POST['tanggal_daftar'];
    $status_aktif = $_POST['status_aktif'];

    // Mengambil id_user
    $id_user = !empty($_POST['id_user']) ? $_POST['id_user'] : NULL;

    // Mengambil id_area
    $id_area = !empty($_POST['id_area']) ? $_POST['id_area'] : NULL;

    // Query untuk mengubah data member
    $query = "UPDATE tb_member SET
        nama_member = ?,
        jenis_kelamin = ?,
        no_hp = ?,
        tanggal_daftar = ?,
        status_aktif = ?,
        id_user = ?,
        id_area = ?
        WHERE id_member = ?";

    // Menyiapkan query
    $stmt = mysqli_prepare($koneksi, $query);

    // Menghubungkan data dengan query
    mysqli_stmt_bind_param(
        $stmt,
        "ssssssii",
        $nama_member,
        $jenis_kelamin,
        $no_hp,
        $tanggal_daftar,
        $status_aktif,
        $id_user,
        $id_area,
        $id_member
    );

    // Menjalankan query
    if (mysqli_stmt_execute($stmt)) {

        // Jika berhasil, kembali ke halaman data member
        header("Location: tampil_member.php");
        exit;

    } else {

        echo "Gagal mengubah data: " . mysqli_error($koneksi);
        exit;
    }
}

/*
    Jika halaman dibuka menggunakan URL:
    edit_member.php?id=1

    maka ID diambil dari GET.
*/
if (isset($_GET['id'])) {

    $id = $_GET['id'];

    // Mengambil data member berdasarkan ID
    $query_member = mysqli_query(
        $koneksi,
        "SELECT * FROM tb_member WHERE id_member='$id'"
    );

    // Mengambil satu data member
    $member = mysqli_fetch_assoc($query_member);

    // Jika data tidak ditemukan
    if (!$member) {
        echo "Data member tidak ditemukan.";
        exit;
    }

} else {

    // Jika ID tidak ada di URL
    echo "ID member tidak ditemukan.";
    exit;
}

// Mengambil data user
$query_user = mysqli_query($koneksi, "SELECT * FROM tb_user");

// Mengambil data area parkir
$query_area = mysqli_query($koneksi, "SELECT * FROM tb_area_parkir");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Member</title>
</head>

<body>

    <h2>Edit Member</h2>

    <form action="edit_member.php" method="POST">

        <!-- ID member disimpan dalam input hidden -->
        <input type="hidden" name="id_member" value="<?= $member['id_member']; ?>">

        <label>Nama Member</label><br>
        <input type="text" name="nama_member" value="<?= $member['nama_member']; ?>" required>

        <br><br>

        <label>Jenis Kelamin</label><br>

        <select name="jenis_kelamin" required>

            <option value="L" <?= $member['jenis_kelamin'] == 'L' ? 'selected' : ''; ?>>
                Laki-laki
            </option>

            <option value="P" <?= $member['jenis_kelamin'] == 'P' ? 'selected' : ''; ?>>
                Perempuan
            </option>

        </select>

        <br><br>

        <label>No HP</label><br>

        <input type="text" name="no_hp" value="<?= $member['no_hp']; ?>">

        <br><br>

        <label>Tanggal Daftar</label><br>

        <input type="date" name="tanggal_daftar" value="<?= $member['tanggal_daftar']; ?>" required>

        <br><br>

        <label>Status Aktif</label><br>

        <select name="status_aktif" required>

            <option value="aktif" <?= $member['status_aktif'] == 'aktif' ? 'selected' : ''; ?>>
                Aktif
            </option>

            <option value="suspended" <?= $member['status_aktif'] == 'suspended' ? 'selected' : ''; ?>>
                Suspended
            </option>

        </select>

        <br><br>

        <label>User</label><br>

        <select name="id_user">

            <option value="">-- Pilih User --</option>

            <?php while ($user = mysqli_fetch_assoc($query_user)) { ?>

                <option value="<?= $user['id_user']; ?>" <?= $member['id_user'] == $user['id_user'] ? 'selected' : ''; ?>>

                    <?= $user['nama_lengkap']; ?>

                </option>

            <?php } ?>

        </select>

        <br><br>

        <label>Area Parkir</label><br>

        <select name="id_area">

            <option value="">-- Pilih Area --</option>

            <?php while ($area = mysqli_fetch_assoc($query_area)) { ?>

                <option value="<?= $area['id_area']; ?>" <?= $member['id_area'] == $area['id_area'] ? 'selected' : ''; ?>>

                    <?= $area['nama_area']; ?>

                </option>

            <?php } ?>

        </select>

        <br><br>

        <!-- Tombol menyimpan perubahan -->
        <button type="submit" name="update">Simpan Perubahan</button>

    </form>

    <br>

    <a href="tampil_member.php">Kembali</a>

</body>

</html>