<?php

// =========================================================
// KONEKSI DATABASE
// =========================================================

// Karena file berada di folder tarif,
// maka ../ digunakan untuk menuju folder utama.
require_once '../koneksi.php';


// =========================================================
// MENGAMBIL DATA TARIF
// =========================================================

// Mengambil semua data dari tabel tb_tarif.
// Data diurutkan berdasarkan ID tarif dari yang terkecil.
$query = mysqli_query(
    $koneksi,
    "SELECT * FROM tb_tarif ORDER BY id_tarif ASC"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <!-- Menentukan karakter encoding -->
    <meta charset="UTF-8">

    <!-- Membuat tampilan responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Judul halaman -->
    <title>Data Tarif - Sistem Parkir</title>

    <!--
        Memanggil CSS utama sistem parkir.

        File CSS berada di:
        C:\xampp14\htdocs\parkir\style.css

        Menggunakan /parkir/style.css agar
        CSS tetap ditemukan dengan benar.
    -->
    <link rel="stylesheet" href="http://localhost/parkir/style.css?v=3">

</head>

<body>


    <!-- =====================================================
         CONTAINER UTAMA
    ====================================================== -->

    <div class="container">


        <!-- =================================================
             HEADER HALAMAN
        ================================================== -->

        <div class="page-header">

            <div>

                <!-- Judul halaman -->

                <h2>
                    Data Tarif Parkir
                </h2>


                <!-- Keterangan halaman -->

                <p class="page-description">

                    Kelola tarif parkir berdasarkan jenis kendaraan.

                </p>

            </div>


            <!-- Tombol kembali ke dashboard -->

            <a href="../dashboard.php" class="kembali">
                ← Dashboard
            </a>

        </div>


        <!-- =================================================
             TOMBOL TAMBAH TARIF
        ================================================== -->

        <p>

            <a href="tambah_tarif.php" class="btn">
                + Tambah Tarif
            </a>

        </p>


        <!-- =================================================
             TABEL DATA TARIF
        ================================================== -->

        <table>

            <!-- Header tabel -->

            <tr>

                <th>
                    ID Tarif
                </th>

                <th>
                    Jenis Kendaraan
                </th>

                <th>
                    Tarif Per Jam
                </th>

                <th>
                    Aksi
                </th>

            </tr>


            <?php

            // =================================================
            // MENAMPILKAN DATA TARIF
            // =================================================
            
            // Mengulangi seluruh data tarif
            while ($data = mysqli_fetch_assoc($query)) {

                ?>

                <tr>


                    <!-- ======================================
                         ID TARIF
                    ======================================= -->

                    <td>

                        <?= htmlspecialchars(
                            $data['id_tarif']
                        ); ?>

                    </td>


                    <!-- ======================================
                         JENIS KENDARAAN
                    ======================================= -->

                    <td>

                        <?= htmlspecialchars(
                            $data['jenis_kendaraan']
                        ); ?>

                    </td>


                    <!-- ======================================
                         TARIF PER JAM
                    ======================================= -->

                    <td>

                        Rp

                        <?= number_format(
                            $data['tarif_per_jam'],
                            0,
                            ',',
                            '.'
                        ); ?>

                    </td>


                    <!-- ======================================
                         AKSI
                    ======================================= -->

                    <td>

                        <!-- Tombol Edit -->

                        <a href="edit_tarif.php?id=<?= $data['id_tarif']; ?>" class="edit">
                            Edit
                        </a>


                        &nbsp;|&nbsp;


                        <!-- Tombol Hapus -->

                        <a href="hapus_tarif.php?id=<?= $data['id_tarif']; ?>" class="hapus"
                            onclick="return confirm('Yakin ingin menghapus tarif ini?')">
                            Hapus
                        </a>

                    </td>


                </tr>


                <?php

            }

            ?>

        </table>


        <!-- =================================================
             TOMBOL KEMBALI
        ================================================== -->

        <br>

        <a href="../dashboard.php" class="kembali">
            ← Kembali ke Dashboard
        </a>


    </div>


</body>

</html>