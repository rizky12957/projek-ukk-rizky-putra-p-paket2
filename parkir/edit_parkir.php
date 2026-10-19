<?php

// Memanggil koneksi database
require_once '../koneksi.php';

/*
    Jika form edit disubmit,
    maka data diproses menggunakan POST.
*/
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Mengambil ID parkir
    $id_parkir = $_POST['id_parkir'];

    // Mengambil data dari form
    $id_member = $_POST['id_member'];
    $plat_nomor = $_POST['plat_nomor'];
    $warna_kendaraan = $_POST['warna_kendaraan'];
    $jenis_kendaraan = $_POST['jenis_kendaraan'];
    $id_tarif = $_POST['id_tarif'];
    $waktu_masuk = $_POST['waktu_masuk'];
    $id_user = $_POST['id_user'];

    // Query untuk mengubah data parkir
    $query = "UPDATE tb_parkir SET
        id_member = ?,
        plat_nomor = ?,
        warna_kendaraan = ?,
        jenis_kendaraan = ?,
        id_tarif = ?,
        waktu_masuk = ?,
        id_user = ?
        WHERE id_parkir = ?";

    // Menyiapkan query
    $stmt = mysqli_prepare($koneksi, $query);

    // Menghubungkan data dengan query
    mysqli_stmt_bind_param(
        $stmt,
        "isssisii",
        $id_member,
        $plat_nomor,
        $warna_kendaraan,
        $jenis_kendaraan,
        $id_tarif,
        $waktu_masuk,
        $id_user,
        $id_parkir
    );

    // Menjalankan query
    if (mysqli_stmt_execute($stmt)) {

        // Setelah berhasil, kembali ke halaman tampil
        header("Location: tampil_parkir.php");
        exit;

    } else {

        echo "Gagal mengubah data: " . mysqli_error($koneksi);
        exit;
    }
}

/*
    Jika halaman dibuka dengan URL:
    edit_parkir.php?id=1

    maka ID diambil dari URL.
*/
if (isset($_GET['id'])) {

    // Mengambil ID dari URL
    $id = $_GET['id'];

    // Mengambil data parkir berdasarkan ID
    $query_parkir = mysqli_query(
        $koneksi,
        "SELECT * FROM tb_parkir WHERE id_parkir='$id'"
    );

    // Mengambil satu data
    $parkir = mysqli_fetch_assoc($query_parkir);

    // Jika data tidak ditemukan
    if (!$parkir) {
        echo "Data parkir tidak ditemukan.";
        exit;
    }

} else {

    // Jika ID tidak ada
    echo "ID parkir tidak ditemukan.";
    exit;
}

// Mengambil data member
$query_member = mysqli_query(
    $koneksi,
    "SELECT * FROM tb_member WHERE status_aktif = 'aktif'"
);

// Mengambil data tarif
$query_tarif = mysqli_query(
    $koneksi,
    "SELECT * FROM tb_tarif"
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
    <title>Edit Parkir</title>
</head>

<body>

    <h2>Edit Data Parkir</h2>

    <form action="edit_parkir.php" method="POST">

        <!-- ID parkir disimpan secara tersembunyi -->
        <input type="hidden" name="id_parkir" value="<?= $parkir['id_parkir']; ?>">

        <!-- Pilihan member -->
        <label>Member</label><br>

        <select name="id_member" required>

            <?php while ($member = mysqli_fetch_assoc($query_member)) { ?>

                <option value="<?= $member['id_member']; ?>" <?= $parkir['id_member'] == $member['id_member'] ? 'selected' : ''; ?>>

                    <?= $member['nama_member']; ?>

                </option>

            <?php } ?>

        </select>

        <br><br>

        <!-- Plat nomor -->
        <label>Plat Nomor</label><br>

        <input type="text" name="plat_nomor" value="<?= $parkir['plat_nomor']; ?>" required>

        <br><br>

        <!-- Warna kendaraan -->
        <label>Warna Kendaraan</label><br>

        <input type="text" name="warna_kendaraan" value="<?= $parkir['warna_kendaraan']; ?>" required>

        <br><br>

        <!-- Jenis kendaraan -->
        <label>Jenis Kendaraan</label><br>

        <select name="jenis_kendaraan" required>

            <option value="motor" <?= $parkir['jenis_kendaraan'] == 'motor' ? 'selected' : ''; ?>>
                Motor
            </option>

            <option value="mobil" <?= $parkir['jenis_kendaraan'] == 'mobil' ? 'selected' : ''; ?>>
                Mobil
            </option>

        </select>

        <br><br>

        <!-- Tarif -->
        <label>Tarif</label><br>

        <select name="id_tarif" required>

            <?php while ($tarif = mysqli_fetch_assoc($query_tarif)) { ?>

                <option value="<?= $tarif['id_tarif']; ?>" <?= $parkir['id_tarif'] == $tarif['id_tarif'] ? 'selected' : ''; ?>>

                    <?= $tarif['jenis_kendaraan']; ?>
                    -
                    Rp <?= number_format($tarif['tarif_per_jam'], 0, ',', '.'); ?>

                </option>

            <?php } ?>

        </select>

        <br><br>

        <!-- Waktu masuk -->
        <label>Waktu Masuk</label><br>

        <input type="datetime-local" name="waktu_masuk"
            value="<?= date('Y-m-d\TH:i', strtotime($parkir['waktu_masuk'])); ?>" required>

        <br><br>

        <!-- User -->
        <label>User</label><br>

        <select name="id_user" required>

            <?php while ($user = mysqli_fetch_assoc($query_user)) { ?>

                <option value="<?= $user['id_user']; ?>" <?= $parkir['id_user'] == $user['id_user'] ? 'selected' : ''; ?>>

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

    <a href="tampil_parkir.php">Kembali</a>

</body>

</html>