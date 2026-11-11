<?php

// Memanggil koneksi database
// Karena file berada di dalam folder parkir,
// maka menggunakan ../ untuk menuju folder utama.
require_once '../koneksi.php';


// =========================================================
// MENGAMBIL DATA KENDARAAN YANG MASIH PARKIR
// =========================================================

/*
    NOT EXISTS digunakan untuk mengecek apakah kendaraan
    sudah melakukan checkout atau belum.

    Jika id_parkir sudah terdapat di tb_transaksi,
    maka kendaraan tersebut sudah checkout.

    Oleh karena itu, kendaraan yang sudah checkout
    tidak akan ditampilkan lagi di halaman Data Parkir.
*/

$query = mysqli_query(
    $koneksi,
    "
    SELECT

        tb_parkir.*,

        tb_member.nama_member,

        tb_tarif.jenis_kendaraan AS jenis_tarif,

        tb_tarif.tarif_per_jam,

        tb_user.nama_lengkap

    FROM tb_parkir


    /* Menghubungkan parkir dengan data member */

    LEFT JOIN tb_member

        ON tb_parkir.id_member = tb_member.id_member


    /* Menghubungkan parkir dengan data tarif */

    LEFT JOIN tb_tarif

        ON tb_parkir.id_tarif = tb_tarif.id_tarif


    /* Menghubungkan parkir dengan data user */

    LEFT JOIN tb_user

        ON tb_parkir.id_user = tb_user.id_user


    /*
        Hanya menampilkan kendaraan
        yang belum melakukan checkout.
    */

    WHERE NOT EXISTS (

        SELECT 1

        FROM tb_transaksi

        WHERE tb_transaksi.id_parkir = tb_parkir.id_parkir

    )


    /* Mengurutkan data berdasarkan ID parkir */

    ORDER BY tb_parkir.id_parkir ASC

    "
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <!-- Menentukan karakter halaman -->
    <meta charset="UTF-8">

    <!-- Membuat tampilan responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Judul halaman -->
    <title>Data Parkir - Sistem Parkir</title>

    <!--
        Memanggil CSS utama sistem parkir.

        File CSS berada di:
        C:\xampp14\htdocs\parkir\style.css

        Karena halaman ini berada di folder parkir,
        tetap menggunakan path utama /parkir/style.css
        agar CSS dapat ditemukan dengan benar.
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
                    Data Parkir
                </h2>


                <!-- Keterangan halaman -->

                <p class="page-description">

                    Daftar kendaraan yang masih berada
                    di area parkir.

                </p>

            </div>


            <!-- Tombol kembali ke dashboard -->

            <a href="../dashboard.php" class="kembali">
                ← Dashboard
            </a>

        </div>


        <!-- =================================================
             TOMBOL TAMBAH PARKIR
        ================================================== -->

        <p>

            <!--
                Tombol untuk menambahkan
                kendaraan yang masuk parkir.
            -->

            <a href="tambah_parkir.php" class="btn">
                + Tambah Parkir
            </a>

        </p>


        <!-- =================================================
             TABEL DATA PARKIR
        ================================================== -->

        <table>

            <!-- Header tabel -->

            <tr>

                <th>
                    ID Parkir
                </th>

                <th>
                    Member
                </th>

                <th>
                    Plat Nomor
                </th>

                <th>
                    Warna Kendaraan
                </th>

                <th>
                    Jenis Kendaraan
                </th>

                <th>
                    Tarif / Jam
                </th>

                <th>
                    Waktu Masuk
                </th>

                <th>
                    User
                </th>

                <th>
                    Aksi
                </th>

            </tr>


            <?php

            /*
                Mengulangi data parkir satu per satu.
            */

            while ($parkir = mysqli_fetch_assoc($query)) {

                ?>

                <tr>


                    <!-- ======================================
                         ID PARKIR
                    ======================================= -->

                    <td>

                        <?= htmlspecialchars(
                            $parkir['id_parkir']
                        ); ?>

                    </td>


                    <!-- ======================================
                         MEMBER
                    ======================================= -->

                    <td>

                        <?= htmlspecialchars(
                            $parkir['nama_member'] ?? '-'
                        ); ?>

                    </td>


                    <!-- ======================================
                         PLAT NOMOR
                    ======================================= -->

                    <td>

                        <strong>

                            <?= htmlspecialchars(
                                $parkir['plat_nomor']
                            ); ?>

                        </strong>

                    </td>


                    <!-- ======================================
                         WARNA KENDARAAN
                    ======================================= -->

                    <td>

                        <?= htmlspecialchars(
                            $parkir['warna_kendaraan']
                        ); ?>

                    </td>


                    <!-- ======================================
                         JENIS KENDARAAN
                    ======================================= -->

                    <td>

                        <?= htmlspecialchars(
                            $parkir['jenis_kendaraan']
                        ); ?>

                    </td>


                    <!-- ======================================
                         TARIF PER JAM
                    ======================================= -->

                    <td>

                        Rp

                        <?= number_format(
                            $parkir['tarif_per_jam'] ?? 0,
                            0,
                            ',',
                            '.'
                        ); ?>

                    </td>


                    <!-- ======================================
                         WAKTU MASUK
                    ======================================= -->

                    <td>

                        <?= htmlspecialchars(
                            $parkir['waktu_masuk']
                        ); ?>

                    </td>


                    <!-- ======================================
                         USER
                    ======================================= -->

                    <td>

                        <?= htmlspecialchars(
                            $parkir['nama_lengkap'] ?? '-'
                        ); ?>

                    </td>


                    <!-- ======================================
                         AKSI CHECKOUT
                    ======================================= -->

                    <td>

                        <a href="checkout.php?id=<?= $parkir['id_parkir']; ?>" class="checkout"
                            onclick="return confirm('Yakin kendaraan ini akan checkout?')">
                            Checkout
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