<?php

// Memanggil file koneksi database
require_once '../koneksi.php';

// Mengecek apakah tombol simpan ditekan
if (isset($_POST['simpan'])) {

    // Mengambil jenis kendaraan dari form
    $jenis_kendaraan = $_POST['jenis_kendaraan'];

    // Mengambil tarif per jam dari form
    $tarif_per_jam = $_POST['tarif_per_jam'];

    // Menyimpan data ke tabel tb_tarif
    // id_tarif tidak perlu dimasukkan karena AUTO_INCREMENT
    $query = mysqli_query(
        $koneksi,
        "INSERT INTO tb_tarif
        (jenis_kendaraan, tarif_per_jam)
        VALUES
        ('$jenis_kendaraan', '$tarif_per_jam')"
    );

    // Mengecek apakah data berhasil disimpan
    if ($query) {

        // Jika berhasil, kembali ke halaman tampil tarif
        header("Location: tampil_tarif.php");
        exit;

    } else {

        // Jika gagal, tampilkan pesan error
        echo "Data gagal disimpan: " . mysqli_error($koneksi);
    }
}

?>