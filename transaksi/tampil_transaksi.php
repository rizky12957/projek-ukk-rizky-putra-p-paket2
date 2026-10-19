<?php

// =========================================================
// SESSION
// =========================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// =========================================================
// CEK LOGIN
// =========================================================

if (!isset($_SESSION['login'])) {

    header("Location: ../login.php");
    exit;
}


// =========================================================
// CEK ROLE
// =========================================================
// Halaman Histori Transaksi hanya boleh diakses oleh:
// - Admin
// - Owner
//
// Petugas tidak boleh mengakses halaman ini.

$role = $_SESSION['role'] ?? '';

if ($role !== 'admin' && $role !== 'owner') {

    echo "
        <script>
            alert('Anda tidak memiliki akses ke Histori Transaksi.');
            window.location='../dashboard.php';
        </script>
    ";

    exit;
}


// =========================================================
// KONEKSI DATABASE
// =========================================================

require_once '../koneksi.php';


// =========================================================
// FILTER HISTORI
// =========================================================

// Mengambil filter dari URL
$filter = $_GET['filter'] ?? 'semua';


// =========================================================
// KONDISI FILTER
// =========================================================

$where = "";

if ($filter == 'hari_ini') {

    // Menampilkan transaksi yang keluar hari ini
    $where = "WHERE DATE(t.waktu_keluar) = CURDATE()";

} elseif ($filter == 'kemarin') {

    // Menampilkan transaksi yang keluar kemarin
    $where = "WHERE DATE(t.waktu_keluar) = CURDATE() - INTERVAL 1 DAY";

} elseif ($filter == 'seminggu') {

    // Menampilkan transaksi 7 hari terakhir
    $where = "WHERE DATE(t.waktu_keluar)
              BETWEEN CURDATE() - INTERVAL 7 DAY
              AND CURDATE() - INTERVAL 1 DAY";

} elseif ($filter == 'bulan_kemarin') {

    // Menampilkan transaksi bulan sebelumnya
    $where = "WHERE MONTH(t.waktu_keluar) =
                    MONTH(CURDATE() - INTERVAL 1 MONTH)
              AND YEAR(t.waktu_keluar) =
                    YEAR(CURDATE() - INTERVAL 1 MONTH)";
}


// =========================================================
// MENGAMBIL DATA TRANSAKSI
// =========================================================

$query = mysqli_query(
    $koneksi,
    "SELECT
        t.id_transaksi,
        t.id_parkir,
        p.plat_nomor,
        p.warna_kendaraan,
        p.jenis_kendaraan,
        p.waktu_masuk,
        t.waktu_keluar,
        t.durasi_jam,
        t.biaya_total,
        u.nama_lengkap

    FROM tb_transaksi t

    INNER JOIN tb_parkir p
        ON t.id_parkir = p.id_parkir

    LEFT JOIN tb_user u
        ON t.id_user = u.id_user

    $where

    ORDER BY t.waktu_keluar DESC"
);


// =========================================================
// CEK QUERY
// =========================================================

if (!$query) {

    die(
        "Query histori transaksi gagal: "
        . mysqli_error($koneksi)
    );
}

?>

<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Histori Transaksi - Sistem Parkir
    </title>


    <!-- =====================================================
         CSS UTAMA
    ====================================================== -->

    <link rel="stylesheet" href="http://localhost/parkir/style.css?v=3">

</head>


<body>

    <div class="container">


        <!-- =====================================================
         HEADER
    ====================================================== -->

        <div class="page-header">

            <div>

                <h2>
                    Histori Transaksi
                </h2>

                <p class="page-description">
                    Daftar riwayat kendaraan yang sudah melakukan checkout.
                </p>

            </div>


            <a href="../dashboard.php" class="kembali">
                ← Dashboard
            </a>

        </div>


        <!-- =====================================================
         INFORMASI ROLE
    ====================================================== -->

        <div style="
        margin-bottom:20px;
        padding:12px 16px;
        background:#f4f5f7;
        border-radius:10px;
        border:1px solid #e5e7eb;
    ">

            <strong>
                Akses:
            </strong>

            <?= ucfirst(htmlspecialchars($role)); ?>

        </div>


        <!-- =====================================================
         FILTER
    ====================================================== -->

        <form method="GET">

            <label for="filter">
                Filter Histori
            </label>


            <select name="filter" id="filter" onchange="this.form.submit()">

                <option value="semua" <?= $filter == 'semua' ? 'selected' : ''; ?>>
                    Semua Transaksi
                </option>


                <option value="hari_ini" <?= $filter == 'hari_ini' ? 'selected' : ''; ?>>
                    Hari Ini
                </option>


                <option value="kemarin" <?= $filter == 'kemarin' ? 'selected' : ''; ?>>
                    Kemarin
                </option>


                <option value="seminggu" <?= $filter == 'seminggu' ? 'selected' : ''; ?>>
                    7 Hari Terakhir
                </option>


                <option value="bulan_kemarin" <?= $filter == 'bulan_kemarin' ? 'selected' : ''; ?>>
                    Bulan Kemarin
                </option>

            </select>

        </form>


        <!-- =====================================================
         TABEL HISTORI
    ====================================================== -->

        <table>

            <tr>

                <th>
                    No
                </th>

                <th>
                    ID Transaksi
                </th>

                <th>
                    Plat Nomor
                </th>

                <th>
                    Jenis Kendaraan
                </th>

                <th>
                    Waktu Masuk
                </th>

                <th>
                    Waktu Keluar
                </th>

                <th>
                    Durasi
                </th>

                <th>
                    Biaya
                </th>

                <th>
                    User
                </th>

            </tr>


            <?php

            // =====================================================
            // NOMOR URUT
            // =====================================================
            
            $no = 1;


            // =====================================================
            // CEK DATA
            // =====================================================
            
            if (mysqli_num_rows($query) > 0) {

                while ($data = mysqli_fetch_assoc($query)) {

                    ?>

                    <tr>

                        <!-- Nomor -->
                        <td>
                            <?= $no++; ?>
                        </td>


                        <!-- ID Transaksi -->
                        <td>
                            <?= htmlspecialchars($data['id_transaksi']); ?>
                        </td>


                        <!-- Plat Nomor -->
                        <td>
                            <?= htmlspecialchars($data['plat_nomor']); ?>
                        </td>


                        <!-- Jenis Kendaraan -->
                        <td>
                            <?= htmlspecialchars(
                                ucfirst($data['jenis_kendaraan'])
                            ); ?>
                        </td>


                        <!-- Waktu Masuk -->
                        <td>

                            <?= date(
                                'd-m-Y H:i:s',
                                strtotime($data['waktu_masuk'])
                            ); ?>

                        </td>


                        <!-- Waktu Keluar -->
                        <td>

                            <?= date(
                                'd-m-Y H:i:s',
                                strtotime($data['waktu_keluar'])
                            ); ?>

                        </td>


                        <!-- Durasi -->
                        <td>

                            <?= htmlspecialchars($data['durasi_jam']); ?>
                            jam

                        </td>


                        <!-- Biaya -->
                        <td>

                            Rp
                            <?= number_format(
                                $data['biaya_total'],
                                0,
                                ',',
                                '.'
                            ); ?>

                        </td>


                        <!-- User -->
                        <td>

                            <?= htmlspecialchars(
                                $data['nama_lengkap'] ?? '-'
                            ); ?>

                        </td>

                    </tr>

                    <?php

                }

            } else {

                ?>

                <tr>

                    <td colspan="9" style="text-align:center;">
                        Belum ada histori transaksi.
                    </td>

                </tr>

            <?php } ?>

        </table>


    </div>

</body>

</html>