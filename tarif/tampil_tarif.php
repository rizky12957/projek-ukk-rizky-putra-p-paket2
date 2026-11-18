
<?php

// =========================================================
// KONEKSI DATABASE
// =========================================================

// Memanggil koneksi database
require_once '../koneksi.php';


// =========================================================
// MENGAMBIL DATA TARIF
// =========================================================

// Mengambil semua data tarif dan mengurutkannya berdasarkan ID
$query = mysqli_query(
    $koneksi,
    "SELECT * FROM tb_tarif ORDER BY id_tarif ASC"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Tarif - Sistem Parkir</title>

    <!-- Memanggil CSS utama -->
    <link rel="stylesheet" href="http://localhost/parkir/style.css?v=3">

</head>

<body>

    <div class="container">

        <!-- HEADER HALAMAN -->
        <div class="page-header">

            <div>

                <h2>Data Tarif Parkir</h2>

                <p class="page-description">
                    Kelola tarif parkir berdasarkan jenis kendaraan.
                </p>

            </div>

            <!-- Tombol kembali ke dashboard -->
            <a href="../dashboard.php" class="kembali">
                ← Dashboard
            </a>

        </div>


        <!-- TABEL DATA TARIF -->
        <table>

            <tr>

                <th>ID Tarif</th>

                <th>Jenis Kendaraan</th>

                <th>Tarif Per Jam</th>

                <th>Aksi</th>

            </tr>


            <?php

            // Menampilkan seluruh data tarif
            while ($data = mysqli_fetch_assoc($query)) {

            ?>

                <tr>

                    <!-- ID TARIF -->
                    <td>
                        <?= htmlspecialchars($data['id_tarif']); ?>
                    </td>

                    <!-- JENIS KENDARAAN -->
                    <td>
                        <?= htmlspecialchars($data['jenis_kendaraan']); ?>
                    </td>

                    <!-- TARIF PER JAM -->
                    <td>
                        Rp
                        <?= number_format(
                            $data['tarif_per_jam'],
                            0,
                            ',',
                            '.'
                        ); ?>
                    </td>

                    <!-- AKSI -->
                    <td>

                        <!-- Tombol Edit -->
                        <a href="edit_tarif.php?id=<?= $data['id_tarif']; ?>" class="edit">
                            Edit
                        </a>

                        &nbsp;|&nbsp;

                        <!-- Tombol Hapus -->
                        <a
                            href="hapus_tarif.php?id=<?= $data['id_tarif']; ?>"
                            class="hapus"
                            onclick="return confirm('Yakin ingin menghapus tarif ini?')"
                        >
                            Hapus
                        </a>

                    </td>

                </tr>

            <?php

            }

            ?>

        </table>


        <!-- TOMBOL KEMBALI -->
        <br>

        <a href="../dashboard.php" class="kembali">
            ← Kembali ke Dashboard
        </a>

    </div>

</body>

</html>
