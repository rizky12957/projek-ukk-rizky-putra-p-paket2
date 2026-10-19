<?php

// Memanggil file koneksi database
// File ini berada di dalam folder area,
// sehingga menggunakan ../ untuk menuju folder utama.
require_once '../koneksi.php';


// =========================================================
// MENGAMBIL DATA AREA PARKIR
// =========================================================

// Mengambil semua data dari tabel tb_area_parkir
$query = mysqli_query(
    $koneksi,
    "SELECT * FROM tb_area_parkir ORDER BY id_area ASC"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <!-- Menentukan karakter halaman -->
    <meta charset="UTF-8">

    <!-- Membuat halaman responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Judul halaman -->
    <title>Data Area Parkir - Sistem Parkir</title>

    <!-- =================================================
         CSS UTAMA
         
         CSS berada di:
         C:\xampp14\htdocs\parkir\style.css

         URL CSS:
         http://localhost/parkir/style.css

         Menggunakan path /parkir/style.css agar
         tidak tergantung pada lokasi folder PHP.
    ================================================== -->
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
                    Data Area Parkir
                </h2>

                <!-- Keterangan halaman -->
                <p class="page-description">
                    Kelola kapasitas dan kondisi area parkir.
                </p>

            </div>


            <!-- Tombol kembali ke dashboard -->
            <a href="../dashboard.php" class="kembali">
                ← Dashboard
            </a>

        </div>


        <!-- =================================================
             TOMBOL TAMBAH AREA
        ================================================== -->

        <p>

            <a href="tambah_area_parkir.php" class="btn">
                + Tambah Area
            </a>

        </p>


        <!-- =================================================
             TABEL DATA AREA PARKIR
        ================================================== -->

        <table>

            <!-- Header tabel -->

            <tr>

                <th>
                    ID Area
                </th>

                <th>
                    Nama Area
                </th>

                <th>
                    Kapasitas
                </th>

                <th>
                    Terisi
                </th>

                <th>
                    Sisa Slot
                </th>

                <th>
                    Aksi
                </th>

            </tr>


            <?php

            // Mengambil data area satu per satu
            while ($data = mysqli_fetch_assoc($query)) {

                // =================================================
                // MENGHITUNG SISA SLOT
                // =================================================
            
                /*
                    Rumus:

                    Sisa Slot = Kapasitas - Terisi
                */

                $sisa = $data['kapasitas'] - $data['terisi'];

                ?>

                <!-- =================================================
                     BARIS DATA AREA
                ================================================== -->

                <tr>


                    <!-- ID AREA -->

                    <td>

                        <?= htmlspecialchars(
                            $data['id_area']
                        ); ?>

                    </td>


                    <!-- NAMA AREA -->

                    <td>

                        <strong>

                            <?= htmlspecialchars(
                                $data['nama_area']
                            ); ?>

                        </strong>

                    </td>


                    <!-- KAPASITAS -->

                    <td>

                        <?= htmlspecialchars(
                            $data['kapasitas']
                        ); ?>

                        slot

                    </td>


                    <!-- TERISI -->

                    <td>

                        <?= htmlspecialchars(
                            $data['terisi']
                        ); ?>

                        slot

                    </td>


                    <!-- SISA SLOT -->

                    <td>

                        <?= htmlspecialchars(
                            $sisa
                        ); ?>

                        slot

                    </td>


                    <!-- AKSI -->

                    <td>

                        <!-- Tombol Edit -->

                        <a href="edit_area_parkir.php?id=<?= $data['id_area']; ?>" class="edit">
                            Edit
                        </a>

                        &nbsp;|&nbsp;

                        <!-- Tombol Hapus -->

                        <a href="hapus_area_parkir.php?id=<?= $data['id_area']; ?>" class="hapus"
                            onclick="return confirm('Yakin ingin menghapus data area ini?')">
                            Hapus
                        </a>

                    </td>

                </tr>


                <?php

                // Mengakhiri perulangan while
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