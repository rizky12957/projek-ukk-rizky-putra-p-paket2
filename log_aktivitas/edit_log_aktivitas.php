<?php

// Memanggil koneksi database
require_once '../koneksi.php';

/*
    Jika form edit dikirim menggunakan POST,
    maka data akan diperbarui.
*/
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Mengambil ID log
    $id_log = $_POST['id_log'];

    // Mengambil ID user
    $id_user = $_POST['id_user'];

    // Mengambil aktivitas
    $aktivitas = $_POST['aktivitas'];

    // Query untuk mengubah data log
    $query = "UPDATE tb_log_aktivitas SET
              id_user = ?,
              aktivitas = ?
              WHERE id_log = ?";

    // Menyiapkan query
    $stmt = mysqli_prepare($koneksi, $query);

    // Menghubungkan data dengan query
    mysqli_stmt_bind_param(
        $stmt,
        "isi",
        $id_user,
        $aktivitas,
        $id_log
    );

    // Menjalankan query
    if (mysqli_stmt_execute($stmt)) {

        // Kembali ke halaman tampil log
        header("Location: tampil_log.php");
        exit;

    } else {

        echo "Gagal mengubah log: " . mysqli_error($koneksi);
        exit;
    }
}

/*
    Jika halaman dibuka dengan:
    edit_log.php?id=1

    maka ID diambil dari URL.
*/
if (isset($_GET['id'])) {

    // Mengambil ID dari URL
    $id = $_GET['id'];

    // Mengambil data log berdasarkan ID
    $query_log = mysqli_query(
        $koneksi,
        "SELECT * FROM tb_log_aktivitas WHERE id_log='$id'"
    );

    // Mengambil satu data log
    $log = mysqli_fetch_assoc($query_log);

    // Jika data tidak ditemukan
    if (!$log) {
        echo "Data log tidak ditemukan.";
        exit;
    }

} else {

    // Jika ID tidak ada
    echo "ID log tidak ditemukan.";
    exit;
}

// Mengambil data user
$query_user = mysqli_query(
    $koneksi,
    "SELECT * FROM tb_user"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Log Aktivitas</title>
</head>

<body>

    <h2>Edit Log Aktivitas</h2>

    <form action="edit_log.php" method="POST">

        <!-- ID log disimpan secara tersembunyi -->
        <input type="hidden" name="id_log" value="<?= $log['id_log']; ?>">

        <!-- Memilih user -->
        <label>User</label><br>

        <select name="id_user" required>

            <?php while ($user = mysqli_fetch_assoc($query_user)) { ?>

                <option value="<?= $user['id_user']; ?>" <?= $log['id_user'] == $user['id_user'] ? 'selected' : ''; ?>>

                    <?= $user['nama_lengkap']; ?>

                </option>

            <?php } ?>

        </select>

        <br><br>

        <!-- Mengubah aktivitas -->
        <label>Aktivitas</label><br>

        <textarea name="aktivitas" rows="4" cols="40" required><?= $log['aktivitas']; ?></textarea>

        <br><br>

        <!-- Waktu aktivitas tidak diubah -->
        <p>
            Waktu Aktivitas:
            <?= $log['waktu_aktivitas']; ?>
        </p>

        <!-- Tombol menyimpan perubahan -->
        <button type="submit">
            Simpan Perubahan
        </button>

    </form>

    <br>

    <a href="tampil_log.php">Kembali</a>

</body>

</html>