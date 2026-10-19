<?php

// Memanggil koneksi database
require_once '../koneksi.php';

/*
    Jika form dikirim menggunakan POST,
    maka data transaksi akan diperbarui.
*/
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Mengambil ID transaksi
    $id_transaksi = $_POST['id_transaksi'];

    // Mengambil data dari form
    $id_parkir = $_POST['id_parkir'];
    $waktu_keluar = $_POST['waktu_keluar'];
    $durasi_jam = $_POST['durasi_jam'];
    $biaya_total = $_POST['biaya_total'];
    $id_user = $_POST['id_user'];

    // Query untuk mengubah transaksi
    $query = "UPDATE tb_transaksi SET
        id_parkir = ?,
        waktu_keluar = ?,
        durasi_jam = ?,
        biaya_total = ?,
        id_user = ?
        WHERE id_transaksi = ?";

    // Menyiapkan query
    $stmt = mysqli_prepare($koneksi, $query);

    // Menghubungkan data dengan query
    mysqli_stmt_bind_param(
        $stmt,
        "isidii",
        $id_parkir,
        $waktu_keluar,
        $durasi_jam,
        $biaya_total,
        $id_user,
        $id_transaksi
    );

    // Menjalankan query
    if (mysqli_stmt_execute($stmt)) {

        // Jika berhasil, kembali ke halaman transaksi
        header("Location: tampil_transaksi.php");
        exit;

    } else {

        // Jika gagal
        echo "Gagal mengubah transaksi: " . mysqli_error($koneksi);
        exit;
    }
}

/*
    Jika halaman dibuka dengan:
    edit_transaksi.php?id=1

    maka ID transaksi diambil dari URL.
*/
if (isset($_GET['id'])) {

    // Mengambil ID dari URL
    $id = $_GET['id'];

    // Mengambil data transaksi berdasarkan ID
    $query_transaksi = mysqli_query(
        $koneksi,
        "SELECT * FROM tb_transaksi WHERE id_transaksi='$id'"
    );

    // Mengambil satu data transaksi
    $transaksi = mysqli_fetch_assoc($query_transaksi);

    // Jika data tidak ditemukan
    if (!$transaksi) {
        echo "Data transaksi tidak ditemukan.";
        exit;
    }

} else {

    // Jika ID tidak ada
    echo "ID transaksi tidak ditemukan.";
    exit;
}

// Mengambil data parkir
$query_parkir = mysqli_query(
    $koneksi,
    "SELECT * FROM tb_parkir"
);

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
    <title>Edit Transaksi</title>
</head>

<body>

    <h2>Edit Transaksi</h2>

    <form action="edit_transaksi.php" method="POST">

        <!-- ID transaksi disimpan secara tersembunyi -->
        <input type="hidden" name="id_transaksi" value="<?= $transaksi['id_transaksi']; ?>">

        <!-- Pilihan kendaraan -->
        <label>Parkir</label><br>

        <select name="id_parkir" required>

            <?php while ($parkir = mysqli_fetch_assoc($query_parkir)) { ?>

                <option value="<?= $parkir['id_parkir']; ?>" <?= $transaksi['id_parkir'] == $parkir['id_parkir'] ? 'selected' : ''; ?>>

                    <?= $parkir['plat_nomor']; ?>
                    -
                    <?= $parkir['jenis_kendaraan']; ?>

                </option>

            <?php } ?>

        </select>

        <br><br>

        <!-- Waktu keluar -->
        <label>Waktu Keluar</label><br>

        <input type="datetime-local" name="waktu_keluar"
            value="<?= date('Y-m-d\TH:i', strtotime($transaksi['waktu_keluar'])); ?>" required>

        <br><br>

        <!-- Durasi parkir -->
        <label>Durasi (Jam)</label><br>

        <input type="number" name="durasi_jam" value="<?= $transaksi['durasi_jam']; ?>" min="1" required>

        <br><br>

        <!-- Biaya total -->
        <label>Biaya Total</label><br>

        <input type="number" name="biaya_total" value="<?= $transaksi['biaya_total']; ?>" min="0" required>

        <br><br>

        <!-- User -->
        <label>User</label><br>

        <select name="id_user" required>

            <?php while ($user = mysqli_fetch_assoc($query_user)) { ?>

                <option value="<?= $user['id_user']; ?>" <?= $transaksi['id_user'] == $user['id_user'] ? 'selected' : ''; ?>>

                    <?= $user['nama_lengkap']; ?>

                </option>

            <?php } ?>

        </select>

        <br><br>

        <!-- Tombol menyimpan perubahan -->
        <button type="submit">
            Simpan Perubahan
        </button>

    </form>

    <br>

    <a href="tampil_transaksi.php">Kembali</a>

</body>

</html>