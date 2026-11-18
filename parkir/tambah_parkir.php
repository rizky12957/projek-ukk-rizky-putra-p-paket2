<?php

// =========================================================
// SESSION DAN CEK LOGIN
// =========================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

// =========================================================
// KONEKSI DATABASE
// =========================================================

require_once '../koneksi.php';

// =========================================================
// DATA USER LOGIN
// =========================================================

$id_user_login = $_SESSION['id_user'] ?? 0;
$nama_user_login = $_SESSION['nama_lengkap'] ?? 'User';
$role_login = $_SESSION['role'] ?? '-';

if (empty($id_user_login)) {
    echo "<script>
        alert('Data user yang sedang login tidak ditemukan.');
        window.location='../logout.php';
    </script>";
    exit;
}

// =========================================================
// MENGAMBIL DATA MEMBER AKTIF
// =========================================================

$query_member = mysqli_query(
    $koneksi,
    "SELECT
        m.id_member,
        m.nama_member,
        m.plat_nomor
     FROM tb_member m
     WHERE m.status_aktif = 'aktif'
     ORDER BY m.nama_member ASC"
);

// =========================================================
// MENGAMBIL DATA AREA PARKIR
// =========================================================

$query_area = mysqli_query(
    $koneksi,
    "SELECT * FROM tb_area_parkir
     ORDER BY nama_area ASC"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Parkir - Sistem Parkir</title>

    <!-- Memanggil CSS utama dari folder utama proyek -->
    <link rel="stylesheet" href="../style.css?v=10">

    <!-- CSS khusus halaman tambah parkir -->
    <style>
        .form-parkir {
            max-width: 750px;
            margin: 25px auto;
            padding: 30px;
            background: #ffffff;
            border: 1px solid #e5e5e5;
            border-radius: 14px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .form-parkir .form-title {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eeeeee;
        }

        .form-parkir .form-title h3 {
            margin: 0 0 7px;
            color: #222222;
            font-size: 20px;
        }

        .form-parkir .form-title p {
            margin: 0;
            color: #777777;
            font-size: 13px;
        }

        .form-parkir label {
            display: block;
            margin: 17px 0 8px;
            color: #333333;
            font-size: 14px;
            font-weight: 600;
        }

        .form-parkir input,
        .form-parkir select {
            width: 100%;
            box-sizing: border-box;
            padding: 12px 14px;
            border: 1px solid #dddddd;
            border-radius: 8px;
            background: #ffffff;
            color: #333333;
            font-size: 14px;
            outline: none;
            transition: 0.2s;
        }

        .form-parkir input:focus,
        .form-parkir select:focus {
            border-color: #555555;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.06);
        }

        .form-parkir input[readonly] {
            background: #f4f4f4;
            color: #666666;
            cursor: not-allowed;
        }

        .form-parkir .form-info {
            margin-top: 8px;
            color: #888888;
            font-size: 12px;
        }

        .form-parkir .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 28px;
        }

        .form-parkir button {
            flex: 1;
            padding: 13px;
            border: none;
            border-radius: 8px;
            background: #222222;
            color: #ffffff;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }

        .form-parkir button:hover {
            background: #444444;
            transform: translateY(-1px);
        }

        .form-parkir .btn-kembali {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 13px 20px;
            border: 1px solid #dddddd;
            border-radius: 8px;
            background: #f5f5f5;
            color: #333333;
            font-size: 14px;
            text-decoration: none;
            transition: 0.2s;
        }

        .form-parkir .btn-kembali:hover {
            background: #e9e9e9;
        }

        @media (max-width: 600px) {
            .form-parkir {
                margin: 15px 0;
                padding: 20px;
            }

            .form-parkir .form-actions {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

    <div class="container">

        <!-- HEADER HALAMAN -->
        <div class="page-header">
            <div>
                <h2>Tambah Data Parkir</h2>
                <p class="page-description">
                    Tambahkan kendaraan yang masuk ke area parkir.
                </p>
            </div>

            <a href="tampil_parkir.php" class="kembali">
                ← Kembali
            </a>
        </div>

        <!-- FORM TAMBAH PARKIR -->
        <div class="form-parkir">

            <div class="form-title">
                <h3>Form Kendaraan Masuk</h3>
                <p>Lengkapi informasi kendaraan yang akan parkir.</p>
            </div>

            <form action="simpan_parkir.php" method="POST">

                <!-- MEMBER -->
                <label for="id_member">Member</label>

                <select name="id_member" id="id_member" required onchange="isiPlatMember()">
                    <option value="">-- Pilih Member --</option>

                    <?php while ($member = mysqli_fetch_assoc($query_member)) { ?>
                        <option
                            value="<?= (int) $member['id_member']; ?>"
                            data-plat="<?= htmlspecialchars($member['plat_nomor'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                        >
                            <?= htmlspecialchars($member['nama_member']); ?>
                            — <?= htmlspecialchars($member['plat_nomor'] ?: 'Plat belum tersimpan'); ?>
                        </option>
                    <?php } ?>
                </select>

                <!-- AREA PARKIR -->
                <label for="id_area">Area Parkir</label>

                <select name="id_area" id="id_area" required>
                    <option value="">-- Pilih Area Parkir --</option>

                    <?php while ($area = mysqli_fetch_assoc($query_area)) { ?>

                        <?php
                        // Menghitung jumlah slot yang masih tersedia
                        $sisa_slot = $area['kapasitas'] - $area['terisi'];
                        ?>

                        <?php if ($sisa_slot > 0) { ?>
                            <option value="<?= htmlspecialchars($area['id_area']); ?>">
                                <?= htmlspecialchars($area['nama_area']); ?>
                                - Sisa Slot: <?= $sisa_slot; ?>
                            </option>
                        <?php } else { ?>
                            <option value="<?= htmlspecialchars($area['id_area']); ?>" disabled>
                                <?= htmlspecialchars($area['nama_area']); ?>
                                - PENUH
                            </option>
                        <?php } ?>

                    <?php } ?>
                </select>

                <!-- PLAT NOMOR -->
                <label for="plat_nomor">Plat Nomor</label>

                <input type="text" name="plat_nomor" id="plat_nomor" placeholder="Contoh: D 1234 AB" required>

                <!-- WARNA KENDARAAN -->
                <label for="warna_kendaraan">Warna Kendaraan</label>

                <input type="text" name="warna_kendaraan" id="warna_kendaraan" placeholder="Contoh: Hitam" required>

                <!-- JENIS KENDARAAN -->
                <label for="jenis_kendaraan">Jenis Kendaraan</label>

                <select name="jenis_kendaraan" id="jenis_kendaraan" required onchange="ubahTarif()">
                    <option value="">-- Pilih Jenis Kendaraan --</option>
                    <option value="motor">Motor</option>
                    <option value="mobil">Mobil</option>
                    <option value="truk">Truk</option>
                    <option value="bus">Bus</option>
                </select>

                <!-- TARIF OTOMATIS -->
                <label for="tampilan_tarif">Tarif Tetap per Sesi Parkir</label>

                <input type="text" id="tampilan_tarif" placeholder="Tarif otomatis" readonly>

                <!-- ID TARIF TERSEMBUNYI -->
                <input type="hidden" name="id_tarif" id="id_tarif">

                <!-- USER LOGIN -->
                <label for="user_login">Petugas yang Mencatat</label>

                <input type="text" id="user_login"
                    value="<?= htmlspecialchars($nama_user_login); ?> (<?= htmlspecialchars(ucfirst($role_login)); ?>)"
                    readonly>

                <input type="hidden" name="id_user" value="<?= htmlspecialchars($id_user_login); ?>">

                <!-- TOMBOL -->
                <div class="form-actions">
                    <a href="tampil_parkir.php" class="btn-kembali">
                        Batal
                    </a>

                    <button type="submit" name="simpan">
                        Simpan Data Parkir
                    </button>
                </div>

            </form>
        </div>

    </div>

    <!-- JAVASCRIPT TARIF OTOMATIS -->
    <script>
        // Mengisi plat nomor otomatis berdasarkan member yang dipilih.
        // Plat masih bisa diedit jika member membawa kendaraan berbeda.
        function isiPlatMember() {
            const pilihan = document.getElementById("id_member");
            const platNomor = document.getElementById("plat_nomor");
            const option = pilihan.options[pilihan.selectedIndex];

            if (option && option.dataset.plat) {
                platNomor.value = option.dataset.plat;
            } else {
                platNomor.value = "";
            }
        }

        function ubahTarif() {

            const jenis = document.getElementById("jenis_kendaraan").value;
            const tampilanTarif = document.getElementById("tampilan_tarif");
            const idTarif = document.getElementById("id_tarif");

            // Menentukan tarif berdasarkan jenis kendaraan
            const daftarTarif = {
                motor: {
                    harga: "Rp 3.000 / sekali parkir",
                    id: "1"
                },
                mobil: {
                    harga: "Rp 5.000 / sekali parkir",
                    id: "2"
                },
                truk: {
                    harga: "Rp 8.000 / sekali parkir",
                    id: "3"
                },
                bus: {
                    harga: "Rp 9.000 / sekali parkir",
                    id: "4"
                }
            };

            if (daftarTarif[jenis]) {
                tampilanTarif.value = daftarTarif[jenis].harga;
                idTarif.value = daftarTarif[jenis].id;
            } else {
                tampilanTarif.value = "";
                idTarif.value = "";
            }
        }
    </script>

</body>

</html>