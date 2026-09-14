<?php

// Memanggil file koneksi database
require_once '../koneksi.php';


// ==================================================
// PROSES UPDATE DATA
// ==================================================

// Mengecek apakah tombol update ditekan
if (isset($_POST['update'])) {

    // Mengambil ID tarif dari form
    $id = $_POST['id_tarif'];

    // Mengambil data baru dari form
    $jenis_kendaraan = $_POST['jenis_kendaraan'];
    $tarif_per_jam = $_POST['tarif_per_jam'];

    // Mengubah data tarif berdasarkan ID
    $query = mysqli_query(
        $koneksi,
        "UPDATE tb_tarif SET
            jenis_kendaraan='$jenis_kendaraan',
            tarif_per_jam='$tarif_per_jam'
        WHERE id_tarif='$id'"
    );

    // Jika berhasil diubah
    if ($query) {

        // Kembali ke halaman tampil tarif
        header("Location: tampil_tarif.php");
        exit;

    } else {

        // Jika gagal, tampilkan pesan error
        echo "Data gagal diubah: " . mysqli_error($koneksi);
    }
}


// ==================================================
// MENGAMBIL DATA TARIF
// ==================================================

// Mengecek apakah ID dikirim melalui URL
// Contoh: edit_tarif.php?id=1
if (isset($_GET['id'])) {

    // Mengambil ID dari URL
    $id = $_GET['id'];

    // Mengambil data tarif berdasarkan ID
    $query = mysqli_query(
        $koneksi,
        "SELECT * FROM tb_tarif WHERE id_tarif='$id'"
    );

    // Mengubah hasil query menjadi array
    $data = mysqli_fetch_assoc($query);

} else {

    // Jika ID tidak ditemukan
    echo "ID tarif tidak ditemukan.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Tarif</title>
</head>

<body>

    <h2>Edit Data Tarif</h2>

    <!--
        Form digunakan untuk mengubah data tarif.
        Data dikirim menggunakan method POST
        ke file edit_tarif.php.
    -->
    <form action="" method="POST">

        <!--
            ID tarif disimpan menggunakan hidden.
            ID digunakan untuk menentukan data
            mana yang akan diubah.
        -->
        <input type="hidden" name="id_tarif" value="<?= $data['id_tarif']; ?>">


        <!-- Jenis kendaraan -->
        <label>Jenis Kendaraan</label><br>

        <select name="jenis_kendaraan" required>

            <option value="motor" <?= $data['jenis_kendaraan'] == 'motor' ? 'selected' : ''; ?>>
                Motor
            </option>

            <option value="mobil" <?= $data['jenis_kendaraan'] == 'mobil' ? 'selected' : ''; ?>>
                Mobil
            </option>

        </select>

        <br><br>


        <!-- Tarif per jam -->
        <label>Tarif Per Jam</label><br>

        <input type="number" name="tarif_per_jam" value="<?= $data['tarif_per_jam']; ?>" min="0" required>

        <br><br>


        <!-- Tombol untuk menyimpan perubahan -->
        <button type="submit" name="update">
            Simpan Perubahan
        </button>

    </form>

    <br>

    <!-- Kembali ke halaman tampil tarif -->
    <a href="tampil_tarif.php">
        Kembali
    </a>

</body>

</html>