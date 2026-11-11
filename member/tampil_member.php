<?php

// =========================================================
// KONEKSI DATABASE
// =========================================================

// Karena file berada di folder member,
// maka ../ digunakan untuk menuju folder utama.
require_once '../koneksi.php';


// =========================================================
// MENGAMBIL DATA MEMBER
// =========================================================

/*
    JOIN digunakan untuk mengambil:

    1. Nama user dari tabel tb_user
    2. Nama area dari tabel tb_area_parkir

    LEFT JOIN digunakan agar data member
    tetap ditampilkan walaupun belum memiliki
    user atau area parkir.
*/

$query = mysqli_query(
    $koneksi,
    "
    SELECT

        tb_member.*,

        tb_user.nama_lengkap,

        tb_area_parkir.nama_area

    FROM tb_member


    /* Menghubungkan member dengan data user */

    LEFT JOIN tb_user

        ON tb_member.id_user = tb_user.id_user


    /* Menghubungkan member dengan data area parkir */

    LEFT JOIN tb_area_parkir

        ON tb_member.id_area = tb_area_parkir.id_area


    /* Mengurutkan data berdasarkan ID member */

    ORDER BY tb_member.id_member ASC

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
    <title>Data Member - Sistem Parkir</title>

    <!--
        CSS utama sistem parkir.

        File CSS berada di:
        C:\xampp14\htdocs\parkir\style.css

        Karena halaman berada di folder member,
        tetap menggunakan path utama agar CSS
        dapat ditemukan dengan benar.
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
                    Data Member
                </h2>


                <!-- Keterangan halaman -->

                <p class="page-description">

                    Kelola data member Sistem Informasi Parkir.

                </p>

            </div>


            <!-- Tombol kembali ke dashboard -->

            <a href="../dashboard.php" class="kembali">
                ← Dashboard
            </a>

        </div>


        <!-- =================================================
             TOMBOL TAMBAH MEMBER
        ================================================== -->

        <p>

            <a href="tambah_member.php" class="btn">
                + Tambah Member
            </a>

        </p>


        <!-- =================================================
             TABEL DATA MEMBER
        ================================================== -->

        <table>

            <!-- Header tabel -->

            <tr>

                <th>
                    ID Member
                </th>

                <th>
                    Nama Member
                </th>

                <th>
                    Jenis Kelamin
                </th>

                <th>
                    No HP
                </th>

                <th>
                    Tanggal Daftar
                </th>

                <th>
                    Status
                </th>

                <th>
                    User
                </th>

                <th>
                    Area Parkir
                </th>

                <th>
                    Aksi
                </th>

            </tr>


            <?php

            // =================================================
            // MENAMPILKAN DATA MEMBER
            // =================================================
            
            while ($member = mysqli_fetch_assoc($query)) {

                ?>

                <tr>


                    <!-- ======================================
                         ID MEMBER
                    ======================================= -->

                    <td>

                        <?= htmlspecialchars(
                            $member['id_member']
                        ); ?>

                    </td>


                    <!-- ======================================
                         NAMA MEMBER
                    ======================================= -->

                    <td>

                        <strong>

                            <?= htmlspecialchars(
                                $member['nama_member']
                            ); ?>

                        </strong>

                    </td>


                    <!-- ======================================
                         JENIS KELAMIN
                    ======================================= -->

                    <td>

                        <?php

                        /*
                            Mengubah kode jenis kelamin:

                            L = Laki-laki
                            P = Perempuan
                        */

                        if ($member['jenis_kelamin'] == 'L') {

                            echo "Laki-laki";

                        } else {

                            echo "Perempuan";

                        }

                        ?>

                    </td>


                    <!-- ======================================
                         NOMOR HP
                    ======================================= -->

                    <td>

                        <?= htmlspecialchars(
                            $member['no_hp']
                        ); ?>

                    </td>


                    <!-- ======================================
                         TANGGAL DAFTAR
                    ======================================= -->

                    <td>

                        <?= htmlspecialchars(
                            $member['tanggal_daftar']
                        ); ?>

                    </td>


                    <!-- ======================================
                         STATUS MEMBER
                    ======================================= -->

                    <td>

                        <?= htmlspecialchars(
                            $member['status_aktif']
                        ); ?>

                    </td>


                    <!-- ======================================
                         USER
                    ======================================= -->

                    <td>

                        <?= htmlspecialchars(
                            $member['nama_lengkap'] ?? '-'
                        ); ?>

                    </td>


                    <!-- ======================================
                         AREA PARKIR
                    ======================================= -->

                    <td>

                        <?= htmlspecialchars(
                            $member['nama_area'] ?? '-'
                        ); ?>

                    </td>


                    <!-- ======================================
                         AKSI
                    ======================================= -->

                    <td>

                        <!-- Tombol Edit -->

                        <a href="edit_member.php?id=<?= $member['id_member']; ?>" class="edit">
                            Edit
                        </a>


                        &nbsp;|&nbsp;


                        <!-- Tombol Hapus -->

                        <a href="hapus_member.php?id=<?= $member['id_member']; ?>" class="hapus"
                            onclick="return confirm('Yakin ingin menghapus member ini?')">
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