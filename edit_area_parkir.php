<?php

// Memanggil file koneksi database
require_once '../koneksi.php';


// Mengecek apakah tombol update sudah ditekan
if (isset($_POST['update'])) {

    // Mengambil ID area dari form
    $id_area = $_POST['id_area'];

    // Mengambil nama area yang baru
    $nama_area = $_POST['nama_area'];

    // Mengambil kapasitas yang baru
    $kapasitas = $_POST['kapasitas'];

    // Mengambil jumlah yang sudah terisi
    $terisi = $_POST['terisi'];


    // Mengubah data area parkir di database
    $query = mysqli_query(
        $koneksi,
        "UPDATE tb_area_parkir SET
        nama_area='$nama_area',
        kapasitas='$kapasitas',
        terisi='$terisi'
        WHERE id_area='$id_area'"
    );


    // Mengecek apakah proses update berhasil
    if ($query) {

        // Jika berhasil, kembali ke halaman data area parkir
        header("Location: tampil_area_parkir.php");

        // Menghentikan proses PHP
        exit;

    } else {

        // Jika gagal, tampilkan pesan error
        echo "Data gagal diubah: " . mysqli_error($koneksi);
    }
}


// Mengecek apakah ID dikirim melalui URL
// Contoh URL: edit_area_parkir.php?id=1
if (isset($_GET['id'])) {

    // Mengambil ID dari URL
    $id = $_GET['id'];

    // Mengambil data area berdasarkan ID
    $query = mysqli_query(
        $koneksi,
        "SELECT * FROM tb_area_parkir WHERE id_area='$id'"
    );

    // Mengubah hasil query menjadi array
    $data = mysqli_fetch_assoc($query);

}

?>

<!DOCTYPE html>
<html>

<head>

    <!-- Judul halaman -->
    <title>Edit Area Parkir</title>

</head>

<body>

    <!-- Judul halaman -->
    <h2>Edit Area Parkir</h2>

    <!--
        Form digunakan untuk mengubah data area parkir.

        method="POST" berarti data dikirim menggunakan POST.

        action="edit_area_parkir.php" berarti data akan
        dikirim kembali ke file edit_area_parkir.php.
    -->
    <form method="POST" action="edit_area_parkir.php">

        <!--
            ID area disimpan secara tersembunyi.

            Hidden berarti input tidak terlihat oleh user,
            tetapi nilainya tetap dikirim saat form disubmit.
        -->
        <input type="hidden" name="id_area" value="<?= $data['id_area']; ?>">

        <!-- Input nama area -->
        <label>Nama Area</label><br>

        <!--
            value digunakan untuk menampilkan
            nama area yang tersimpan di database.
        -->
        <input type="text" name="nama_area" value="<?= $data['nama_area']; ?>" required>

        <br><br>

        <!-- Input kapasitas -->
        <label>Kapasitas</label><br>

        <!--
            Menampilkan kapasitas lama dari database.
        -->
        <input type="number" name="kapasitas" value="<?= $data['kapasitas']; ?>" required>

        <br><br>

        <!-- Input jumlah yang sudah terisi -->
        <label>Terisi</label><br>

        <!--
            Menampilkan jumlah terisi lama dari database.
        -->
        <input type="number" name="terisi" value="<?= $data['terisi']; ?>" required>

        <br><br>

        <!--
            Tombol untuk menyimpan perubahan.

            name="update" digunakan untuk mengecek
            apakah tombol Simpan Perubahan sudah ditekan.
        -->
        <button type="submit" name="update">
            Simpan Perubahan
        </button>

        <!-- Tombol untuk kembali ke halaman data area parkir -->
        <a href="tampil_area_parkir.php">Kembali</a>

    </form>

</body>

</html>