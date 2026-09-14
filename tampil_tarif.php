<?php

// Memanggil file koneksi database
require_once '../koneksi.php';

// Mengambil semua data dari tabel tb_tarif
$query = mysqli_query(
    $koneksi,
    "SELECT * FROM tb_tarif ORDER BY id_tarif ASC"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Tarif</title>
</head>

<body>

    <h2>Data Tarif Parkir</h2>

    <!-- Tombol untuk menuju halaman tambah tarif -->
    <a href="tambah_tarif.php">Tambah Tarif</a>

    <br><br>

    <table border="1" cellpadding="8" cellspacing="0">

        <tr>
            <th>ID Tarif</th>
            <th>Jenis Kendaraan</th>
            <th>Tarif Per Jam</th>
            <th>Aksi</th>
        </tr>

        <?php while ($data = mysqli_fetch_assoc($query)) { ?>

            <tr>

                <!-- Menampilkan ID tarif -->
                <td><?= $data['id_tarif']; ?></td>

                <!-- Menampilkan jenis kendaraan -->
                <td><?= $data['jenis_kendaraan']; ?></td>

                <!-- Menampilkan tarif per jam -->
                <td>
                    Rp <?= number_format($data['tarif_per_jam'], 0, ',', '.'); ?>
                </td>

                <td>

                    <!-- Tombol edit -->
                    <a href="edit_tarif.php?id=<?= $data['id_tarif']; ?>">
                        Edit
                    </a>

                    |

                    <!-- Tombol hapus -->
                    <a href="hapus_tarif.php?id=<?= $data['id_tarif']; ?>"
                        onclick="return confirm('Yakin ingin menghapus tarif ini?')">
                        Hapus
                    </a>

                </td>

            </tr>

        <?php } ?>

    </table>

</body>

</html>