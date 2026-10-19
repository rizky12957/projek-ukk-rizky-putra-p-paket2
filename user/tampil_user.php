<?php

// =========================================================
// KONEKSI DATABASE
// =========================================================

// Karena file berada di dalam folder user,
// maka ../ digunakan untuk menuju folder utama parkir.
require_once '../koneksi.php';


// =========================================================
// MENGAMBIL DATA USER
// =========================================================

// Menjalankan query untuk mengambil seluruh data
// dari tabel tb_user.
$data = mysqli_query(
    $koneksi,
    "SELECT * FROM tb_user"
);


// Mengubah seluruh hasil query menjadi array asosiatif.
$rows = mysqli_fetch_all(
    $data,
    MYSQLI_ASSOC
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <!-- Menentukan karakter encoding halaman -->
    <meta charset="UTF-8">

    <!-- Membuat tampilan responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Judul halaman -->
    <title>Data User - Sistem Parkir</title>


    <!--
        CSS utama sistem parkir.

        File CSS berada di:
        C:\xampp14\htdocs\parkir\style.css

        Menggunakan /parkir/style.css agar
        CSS dapat ditemukan dengan benar.
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
                    Data User
                </h2>


                <!-- Keterangan halaman -->

                <p class="page-description">

                    Kelola data pengguna Sistem Informasi Parkir.

                </p>

            </div>


            <!-- Tombol kembali ke dashboard -->

            <a href="../dashboard.php" class="kembali">
                ← Dashboard
            </a>

        </div>


        <!-- =================================================
             TOMBOL TAMBAH DATA USER
        ================================================== -->

        <p>

            <a href="tambah_user.php" class="btn">
                + Tambah Data User
            </a>

        </p>


        <!-- =================================================
             TABEL DATA USER
        ================================================== -->

        <table>

            <!-- Header tabel -->

            <tr>

                <th>
                    ID User
                </th>

                <th>
                    Nama Lengkap
                </th>

                <th>
                    Username
                </th>

                <th>
                    Password
                </th>

                <th>
                    Role
                </th>

                <th>
                    Status Aktif
                </th>

                <th>
                    Aksi
                </th>

            </tr>


            <?php

            // =================================================
            // MENAMPILKAN DATA USER
            // =================================================
            
            /*
                FOREACH digunakan untuk mengulangi
                setiap data yang terdapat di dalam
                array $rows.
            */

            foreach ($rows as $row):

                ?>

                <!-- ==========================================
                     BARIS DATA USER
                =========================================== -->

                <tr>


                    <!-- ======================================
                         ID USER
                    ======================================= -->

                    <td>

                        <?= htmlspecialchars(
                            $row['id_user']
                        ); ?>

                    </td>


                    <!-- ======================================
                         NAMA LENGKAP
                    ======================================= -->

                    <td>

                        <strong>

                            <?= htmlspecialchars(
                                $row['nama_lengkap']
                            ); ?>

                        </strong>

                    </td>


                    <!-- ======================================
                         USERNAME
                    ======================================= -->

                    <td>

                        <?= htmlspecialchars(
                            $row['username']
                        ); ?>

                    </td>


                    <!-- ======================================
                         PASSWORD
                    ======================================= -->

                    <td>

                        <?= htmlspecialchars(
                            $row['password']
                        ); ?>

                    </td>


                    <!-- ======================================
                         ROLE
                    ======================================= -->

                    <td>

                        <?= htmlspecialchars(
                            $row['role']
                        ); ?>

                    </td>


                    <!-- ======================================
                         STATUS AKTIF
                    ======================================= -->

                    <td>

                        <?= htmlspecialchars(
                            $row['status_aktif']
                        ); ?>

                    </td>


                    <!-- ======================================
                         AKSI
                    ======================================= -->

                    <td>

                        <!-- Tombol Edit -->

                        <a href="edit_user.php?id=<?= $row['id_user']; ?>" class="edit">
                            Edit
                        </a>


                        &nbsp;|&nbsp;


                        <!-- Tombol Hapus -->

                        <a href="hapus_user.php?id=<?= $row['id_user']; ?>" class="hapus"
                            onclick="return confirm('Yakin ingin menghapus user ini?')">
                            Hapus
                        </a>

                    </td>


                </tr>


                <?php

                // Mengakhiri perulangan foreach
            endforeach;

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