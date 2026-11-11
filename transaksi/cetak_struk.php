<?php

// =========================================================
// TIMEZONE
// =========================================================

date_default_timezone_set('Asia/Jakarta');


// =========================================================
// KONEKSI DATABASE
// =========================================================

require_once '../koneksi.php';


// =========================================================
// CEK LOGIN
// =========================================================

if (!isset($_SESSION['login'])) {

    header("Location: ../login.php");
    exit;
}


// =========================================================
// MENGAMBIL ID TRANSAKSI
// =========================================================

// ID transaksi dikirim oleh simpan_checkout.php
// melalui URL:
// cetak_struk.php?id=ID_TRANSAKSI

if (!isset($_GET['id']) || (int) $_GET['id'] <= 0) {

    die("ID transaksi tidak ditemukan.");
}


// Mengubah ID menjadi integer agar lebih aman
$id_transaksi = (int) $_GET['id'];


// =========================================================
// MENGAMBIL DATA TRANSAKSI
// =========================================================

$query = mysqli_prepare(
    $koneksi,
    "
    SELECT

        t.id_transaksi,
        t.waktu_keluar,
        t.durasi_jam,
        t.biaya_total,

        p.id_parkir,
        p.plat_nomor,
        p.warna_kendaraan,
        p.jenis_kendaraan,
        p.waktu_masuk,

        a.nama_area,

        tr.tarif_per_jam,

        u.nama_lengkap

    FROM tb_transaksi t

    INNER JOIN tb_parkir p
        ON t.id_parkir = p.id_parkir

    LEFT JOIN tb_area_parkir a
        ON p.id_area = a.id_area

    LEFT JOIN tb_tarif tr
        ON p.id_tarif = tr.id_tarif

    LEFT JOIN tb_user u
        ON t.id_user = u.id_user

    WHERE t.id_transaksi = ?

    LIMIT 1
    "
);


// =========================================================
// CEK QUERY
// =========================================================

if (!$query) {

    die(
        "Query gagal disiapkan: " .
        mysqli_error($koneksi)
    );
}


// =========================================================
// MENJALANKAN QUERY
// =========================================================

mysqli_stmt_bind_param(
    $query,
    "i",
    $id_transaksi
);


if (!mysqli_stmt_execute($query)) {

    die(
        "Query gagal dijalankan: " .
        mysqli_stmt_error($query)
    );
}


// =========================================================
// MENGAMBIL HASIL QUERY
// =========================================================

$hasil = mysqli_stmt_get_result($query);


// =========================================================
// CEK DATA
// =========================================================

if (mysqli_num_rows($hasil) == 0) {

    mysqli_stmt_close($query);

    die("Data transaksi tidak ditemukan.");
}


// Mengambil data transaksi
$data = mysqli_fetch_assoc($hasil);


// Menutup statement
mysqli_stmt_close($query);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Struk Parkir</title>


    <!-- =====================================================
         CSS UTAMA
    ====================================================== -->

    <link rel="stylesheet" href="http://localhost/parkir/style.css?v=2">


    <style>
        /* =====================================================
           BODY
        ====================================================== */

        body {

            font-family: Arial, sans-serif;

            background: #f2f2f2;

            margin: 0;

            padding: 30px;

        }


        /* =====================================================
           STRUK
        ====================================================== */

        .struk {

            width: 400px;

            max-width: 100%;

            margin: auto;

            background: white;

            padding: 25px;

            box-sizing: border-box;

            border: 1px solid #ddd;

            border-radius: 12px;

        }


        /* =====================================================
           HEADER STRUK
        ====================================================== */

        .header {

            text-align: center;

            border-bottom: 2px dashed #555;

            padding-bottom: 15px;

            margin-bottom: 15px;

        }


        .header h2 {

            margin: 0 0 5px;

            font-size: 22px;

        }


        .header p {

            margin: 4px 0;

            font-size: 13px;

            color: #555;

        }


        /* =====================================================
           BARIS DATA
        ====================================================== */

        .row {

            display: flex;

            justify-content: space-between;

            gap: 15px;

            padding: 7px 0;

            font-size: 14px;

            border-bottom: 1px solid #eee;

        }


        .label {

            color: #555;

        }


        .value {

            text-align: right;

            font-weight: bold;

            word-break: break-word;

        }


        /* =====================================================
           TOTAL
        ====================================================== */

        .total {

            border-top: 2px dashed #555;

            margin-top: 15px;

            padding-top: 15px;

            display: flex;

            justify-content: space-between;

            gap: 15px;

            font-size: 18px;

            font-weight: bold;

        }


        /* =====================================================
           FOOTER
        ====================================================== */

        .footer {

            text-align: center;

            border-top: 1px dashed #555;

            margin-top: 20px;

            padding-top: 15px;

            font-size: 12px;

            color: #666;

        }


        /* =====================================================
           TOMBOL
        ====================================================== */

        .tombol {

            text-align: center;

            margin-top: 20px;

            display: flex;

            justify-content: center;

            gap: 10px;

            flex-wrap: wrap;

        }


        .btn-cetak {

            background: #333;

            color: white;

            border: none;

            padding: 10px 20px;

            border-radius: 6px;

            cursor: pointer;

            font-size: 14px;

        }


        .btn-kembali {

            background: #777;

            color: white;

            padding: 10px 20px;

            border-radius: 6px;

            text-decoration: none;

            font-size: 14px;

        }


        /* =====================================================
           PRINT
        ====================================================== */

        @media print {

            body {

                background: white;

                padding: 0;

            }


            .struk {

                width: 100%;

                border: none;

                box-shadow: none;

            }


            .tombol {

                display: none;

            }

        }
    </style>

</head>


<body>


    <div class="struk">


        <!-- =====================================================
         HEADER
    ====================================================== -->

        <div class="header">

            <h2>PARKIRAN</h2>

            <p>STRUK PEMBAYARAN PARKIR</p>

            <p>
                Terima kasih telah menggunakan layanan kami
            </p>

        </div>


        <!-- =====================================================
         ID TRANSAKSI
    ====================================================== -->

        <div class="row">

            <span class="label">
                ID Transaksi
            </span>

            <span class="value">

                #<?= (int) $data['id_transaksi']; ?>

            </span>

        </div>


        <!-- =====================================================
         PLAT NOMOR
    ====================================================== -->

        <div class="row">

            <span class="label">
                Plat Nomor
            </span>

            <span class="value">

                <?= htmlspecialchars(
                    $data['plat_nomor']
                ); ?>

            </span>

        </div>


        <!-- =====================================================
         JENIS KENDARAAN
    ====================================================== -->

        <div class="row">

            <span class="label">
                Kendaraan
            </span>

            <span class="value">

                <?= htmlspecialchars(
                    ucfirst($data['jenis_kendaraan'])
                ); ?>

            </span>

        </div>


        <!-- =====================================================
         WARNA
    ====================================================== -->

        <div class="row">

            <span class="label">
                Warna
            </span>

            <span class="value">

                <?= htmlspecialchars(
                    $data['warna_kendaraan']
                ); ?>

            </span>

        </div>


        <!-- =====================================================
         AREA PARKIR
    ====================================================== -->

        <div class="row">

            <span class="label">
                Area Parkir
            </span>

            <span class="value">

                <?= htmlspecialchars(
                    $data['nama_area'] ?? '-'
                ); ?>

            </span>

        </div>


        <!-- =====================================================
         WAKTU MASUK
    ====================================================== -->

        <div class="row">

            <span class="label">
                Waktu Masuk
            </span>

            <span class="value">

                <?= date(
                    'd-m-Y H:i:s',
                    strtotime($data['waktu_masuk'])
                ); ?>

            </span>

        </div>


        <!-- =====================================================
         WAKTU KELUAR
    ====================================================== -->

        <div class="row">

            <span class="label">
                Waktu Keluar
            </span>

            <span class="value">

                <?= date(
                    'd-m-Y H:i:s',
                    strtotime($data['waktu_keluar'])
                ); ?>

            </span>

        </div>


        <!-- =====================================================
         DURASI
    ====================================================== -->

        <div class="row">

            <span class="label">
                Durasi
            </span>

            <span class="value">

                <?= (int) $data['durasi_jam']; ?> jam

            </span>

        </div>


        <!-- =====================================================
         TARIF
    ====================================================== -->

        <div class="row">

            <span class="label">
                Tarif / Jam
            </span>

            <span class="value">

                Rp
                <?= number_format(
                    $data['tarif_per_jam'] ?? 0,
                    0,
                    ',',
                    '.'
                ); ?>

            </span>

        </div>


        <!-- =====================================================
         PETUGAS
    ====================================================== -->

        <div class="row">

            <span class="label">
                Petugas
            </span>

            <span class="value">

                <?= htmlspecialchars(
                    $data['nama_lengkap'] ?? '-'
                ); ?>

            </span>

        </div>


        <!-- =====================================================
         TOTAL BIAYA
    ====================================================== -->

        <div class="total">

            <span>
                TOTAL
            </span>

            <span>

                Rp
                <?= number_format(
                    $data['biaya_total'],
                    0,
                    ',',
                    '.'
                ); ?>

            </span>

        </div>


        <!-- =====================================================
         FOOTER
    ====================================================== -->

        <div class="footer">

            <p>
                Pembayaran telah berhasil.
            </p>

            <p>
                Simpan struk ini sebagai bukti pembayaran.
            </p>

        </div>


        <!-- =====================================================
         TOMBOL
    ====================================================== -->

        <div class="tombol">

            <!-- Tombol cetak -->
            <button type="button" class="btn-cetak" onclick="window.print()">
                🖨 Cetak Struk
            </button>


            <!-- Kembali ke data parkir -->
            <a href="../parkir/tampil_parkir.php" class="btn-kembali">
                ← Data Parkir
            </a>

        </div>


    </div>


</body>

</html>