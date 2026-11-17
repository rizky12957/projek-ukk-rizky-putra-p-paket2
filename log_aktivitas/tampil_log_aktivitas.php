<?php

// =========================================================
// KONEKSI DATABASE
// =========================================================

require_once '../koneksi.php';


// =========================================================
// MENGAMBIL DATA LOG AKTIVITAS
// =========================================================

// Mengambil log beserta nama dan username pengguna
$query = mysqli_query(
    $koneksi,
    "
    SELECT
        tb_log_aktivitas.*,
        tb_user.nama_lengkap,
        tb_user.username

    FROM tb_log_aktivitas

    LEFT JOIN tb_user
        ON tb_log_aktivitas.id_user = tb_user.id_user

    ORDER BY tb_log_aktivitas.waktu_aktivitas DESC,
             tb_log_aktivitas.id_log DESC
    "
);

// Memeriksa kesalahan query
if (!$query) {
    die("Gagal mengambil data log aktivitas: " . mysqli_error($koneksi));
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Log Aktivitas - Sistem Parkir</title>

    <!-- CSS utama -->
    <link rel="stylesheet" href="../style.css?v=10">

</head>

<body>

    <div class="container">

        <!-- HEADER HALAMAN -->
        <div class="page-header">

            <div>
                <h2>Log Aktivitas Pengguna</h2>

                <p class="page-description">
                    Riwayat seluruh aktivitas pengguna yang tercatat otomatis di sistem parkir.
                </p>
            </div>

            <a href="../dashboard.php" class="kembali">
                ← Dashboard
            </a>

        </div>


        <!-- INFORMASI -->
        <div class="alert alert-success">
            Seluruh aktivitas dicatat otomatis ketika pengguna berhasil melakukan tindakan pada sistem.
        </div>


        <!-- TABEL LOG AKTIVITAS -->
        <div class="table-wrapper">

            <table>

                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Log</th>
                        <th>Nama Pengguna</th>
                        <th>Username</th>
                        <th>Aktivitas</th>
                        <th>Waktu Aktivitas</th>
                    </tr>
                </thead>

                <tbody>

                    <?php

                    $no = 1;

                    // Memeriksa apakah terdapat data log
                    if (mysqli_num_rows($query) > 0) {

                        while ($log = mysqli_fetch_assoc($query)) {

                            ?>

                            <tr>

                                <td>
                                    <?= $no++; ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($log['id_log']); ?>
                                </td>

                                <td>
                                    <strong>
                                        <?= htmlspecialchars($log['nama_lengkap'] ?? 'Pengguna tidak ditemukan'); ?>
                                    </strong>
                                </td>

                                <td>
                                    <?= htmlspecialchars($log['username'] ?? '-'); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($log['aktivitas']); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($log['waktu_aktivitas']); ?>
                                </td>

                            </tr>

                            <?php

                        }

                    } else {

                        ?>

                        <tr>
                            <td colspan="6" class="empty-data">
                                Belum ada aktivitas yang tercatat.
                            </td>
                        </tr>

                        <?php

                    }

                    ?>

                </tbody>

            </table>

        </div>


        <!-- TOMBOL KEMBALI -->
        <br>

        <a href="../dashboard.php" class="kembali">
            ← Kembali ke Dashboard
        </a>


    </div>

</body>

</html>