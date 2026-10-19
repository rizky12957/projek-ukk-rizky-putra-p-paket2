<?php

// Memanggil file koneksi database
// Karena file berada di folder log_aktivitas,
// maka menggunakan ../ untuk menuju folder utama.
require_once '../koneksi.php';


// =========================================================
// MENGAMBIL DATA LOG AKTIVITAS
// =========================================================

/*
    LEFT JOIN digunakan untuk mengambil
    nama lengkap dan username dari tb_user.

    Dengan LEFT JOIN, data log tetap ditampilkan
    walaupun user yang terkait sudah tidak ada.
*/

$query = mysqli_query(
    $koneksi,
    "
    SELECT

        tb_log_aktivitas.*,

        tb_user.nama_lengkap,

        tb_user.username

    FROM tb_log_aktivitas


    /* Menghubungkan log dengan data user */

    LEFT JOIN tb_user

        ON tb_log_aktivitas.id_user = tb_user.id_user


    /* Mengurutkan data berdasarkan ID log */

    ORDER BY tb_log_aktivitas.id_log ASC
    "
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
    <title>Log Aktivitas - Sistem Parkir</title>

    <!--
        Memanggil CSS utama.
        Karena file berada di folder log_aktivitas,
        maka menggunakan ../style.css
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
                    Data Log Aktivitas
                </h2>


                <!-- Keterangan halaman -->

                <p style="color: #8a919c; font-size: 13px;">

                    Melihat dan mengelola aktivitas pengguna
                    dalam sistem parkir.

                </p>

            </div>


            <!-- Tombol kembali ke dashboard -->

            <a href="../dashboard.php" class="kembali">
                ← Dashboard
            </a>

        </div>


        <!-- =================================================
         TOMBOL TAMBAH LOG
    ================================================== -->

        <p>

            <a href="tambah_log_aktivitas.php" class="btn">
                + Tambah Log Aktivitas
            </a>

        </p>


        <!-- =================================================
         TABEL LOG AKTIVITAS
    ================================================== -->

        <table>

            <!-- Header tabel -->

            <tr>

                <th>
                    ID Log
                </th>

                <th>
                    User
                </th>

                <th>
                    Username
                </th>

                <th>
                    Aktivitas
                </th>

                <th>
                    Waktu Aktivitas
                </th>

                <th>
                    Aksi
                </th>

            </tr>


            <?php

            // Mengambil data log satu per satu
            while ($log = mysqli_fetch_assoc($query)) {

                ?>

                <tr>


                    <!-- ======================================
                     ID LOG
                ======================================= -->

                    <td>

                        <?= htmlspecialchars(
                            $log['id_log']
                        ); ?>

                    </td>


                    <!-- ======================================
                     NAMA USER
                ======================================= -->

                    <td>

                        <strong>

                            <?= htmlspecialchars(
                                $log['nama_lengkap'] ?? '-'
                            ); ?>

                        </strong>

                    </td>


                    <!-- ======================================
                     USERNAME
                ======================================= -->

                    <td>

                        <?= htmlspecialchars(
                            $log['username'] ?? '-'
                        ); ?>

                    </td>


                    <!-- ======================================
                     AKTIVITAS
                ======================================= -->

                    <td>

                        <?= htmlspecialchars(
                            $log['aktivitas']
                        ); ?>

                    </td>


                    <!-- ======================================
                     WAKTU AKTIVITAS
                ======================================= -->

                    <td>

                        <?= htmlspecialchars(
                            $log['waktu_aktivitas']
                        ); ?>

                    </td>


                    <!-- ======================================
                     AKSI
                ======================================= -->

                    <td>

                        <!-- Tombol Edit -->

                        <a href="edit_log_aktivitas.php?id=<?= $log['id_log']; ?>" class="edit">
                            Edit
                        </a>


                        &nbsp;|&nbsp;


                        <!-- Tombol Hapus -->

                        <a href="hapus_log_aktivitas.php?id=<?= $log['id_log']; ?>" class="hapus"
                            onclick="return confirm('Yakin ingin menghapus log ini?')">
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