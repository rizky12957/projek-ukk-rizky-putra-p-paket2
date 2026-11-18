<?php
// =========================================================
// LAPORAN TRANSAKSI KHUSUS OWNER
// =========================================================

require_once '../koneksi.php';

if (!isset($_SESSION['login'])) {
    header('Location: ../login.php');
    exit;
}

$role = $_SESSION['role'] ?? '';
if ($role !== 'owner') {
    http_response_code(403);
    die('Halaman cetak laporan hanya dapat diakses oleh Owner.');
}

$filter = $_GET['filter'] ?? 'semua';
$where = '';
$judul_filter = 'Semua Transaksi';

if ($filter === 'hari_ini') {
    $where = 'WHERE DATE(t.waktu_keluar) = CURDATE()';
    $judul_filter = 'Transaksi Hari Ini';
} elseif ($filter === 'kemarin') {
    $where = 'WHERE DATE(t.waktu_keluar) = CURDATE() - INTERVAL 1 DAY';
    $judul_filter = 'Transaksi Kemarin';
} elseif ($filter === 'seminggu') {
    $where = 'WHERE DATE(t.waktu_keluar) BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE() - INTERVAL 1 DAY';
    $judul_filter = 'Transaksi 7 Hari Terakhir';
} elseif ($filter === 'bulan_kemarin') {
    $where = 'WHERE MONTH(t.waktu_keluar) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(t.waktu_keluar) = YEAR(CURDATE() - INTERVAL 1 MONTH)';
    $judul_filter = 'Transaksi Bulan Kemarin';
}

$sql = "SELECT t.id_transaksi, t.waktu_keluar, t.durasi_jam, t.biaya_total,
               p.plat_nomor, p.jenis_kendaraan, p.waktu_masuk,
               m.nama_member, u.nama_lengkap
        FROM tb_transaksi t
        INNER JOIN tb_parkir p ON t.id_parkir = p.id_parkir
        LEFT JOIN tb_member m ON p.id_member = m.id_member
        LEFT JOIN tb_user u ON t.id_user = u.id_user
        $where
        ORDER BY t.waktu_keluar DESC";
$query = mysqli_query($koneksi, $sql);
if (!$query) {
    die('Gagal mengambil laporan: ' . htmlspecialchars(mysqli_error($koneksi)));
}

$total = 0;
$jumlah = mysqli_num_rows($query);
$rows = [];
while ($row = mysqli_fetch_assoc($query)) {
    $rows[] = $row;
    $total += (float) $row['biaya_total'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Transaksi</title>
    <style>
        body { font-family: Arial, sans-serif; color: #222; margin: 25px; }
        h2, p { text-align: center; margin: 5px 0; }
        .info { margin: 22px 0 12px; display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { border: 1px solid #999; padding: 8px; text-align: left; }
        th { background: #eee; }
        .total { margin-top: 16px; text-align: right; font-size: 17px; font-weight: bold; }
        .actions { margin: 20px 0; text-align: center; }
        button, a { display: inline-block; padding: 9px 15px; border: 0; border-radius: 5px; background: #333; color: #fff; text-decoration: none; cursor: pointer; }
        @media print { body { margin: 10mm; } .actions { display: none; } }
    </style>
</head>
<body>
    <h2>LAPORAN TRANSAKSI PARKIR</h2>
    <p><?= htmlspecialchars($judul_filter); ?></p>
    <div class="info">
        <span>Tanggal cetak: <?= date('d-m-Y H:i:s'); ?></span>
        <span>Jumlah transaksi: <?= $jumlah; ?></span>
    </div>
    <table>
        <thead>
            <tr>
                <th>No</th><th>ID</th><th>Member</th><th>Plat Nomor</th>
                <th>Kendaraan</th><th>Masuk</th><th>Keluar</th>
                <th>Durasi</th><th>Petugas</th><th>Biaya</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($jumlah > 0) { foreach ($rows as $i => $row) { ?>
                <tr>
                    <td><?= $i + 1; ?></td>
                    <td><?= (int) $row['id_transaksi']; ?></td>
                    <td><?= htmlspecialchars($row['nama_member'] ?? 'Non-member'); ?></td>
                    <td><?= htmlspecialchars($row['plat_nomor']); ?></td>
                    <td><?= htmlspecialchars(ucfirst($row['jenis_kendaraan'])); ?></td>
                    <td><?= date('d-m-Y H:i', strtotime($row['waktu_masuk'])); ?></td>
                    <td><?= date('d-m-Y H:i', strtotime($row['waktu_keluar'])); ?></td>
                    <td><?= (int) $row['durasi_jam']; ?> jam</td>
                    <td><?= htmlspecialchars($row['nama_lengkap'] ?? '-'); ?></td>
                    <td>Rp <?= number_format((float) $row['biaya_total'], 0, ',', '.'); ?></td>
                </tr>
            <?php } } else { ?>
                <tr><td colspan="10" style="text-align:center">Tidak ada transaksi pada periode ini.</td></tr>
            <?php } ?>
        </tbody>
    </table>
    <div class="total">Total Pendapatan: Rp <?= number_format($total, 0, ',', '.'); ?></div>
    <div class="actions">
        <button onclick="window.print()">Cetak Laporan</button>
        <a href="tampil_transaksi.php?filter=<?= urlencode($filter); ?>">Kembali</a>
    </div>
</body>
</html>
