<?php

// Memanggil file koneksi database
require_once '../koneksi.php';

// ============================================
// PROSES UPDATE DATA TARIF
// ============================================

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {

    // Mengambil data dari form
    $id = (int) $_POST['id_tarif'];
    $jenis_kendaraan = $_POST['jenis_kendaraan'];
    $tarif_per_jam = (int) $_POST['tarif_per_jam'];

    // Memastikan jenis kendaraan sesuai pilihan
    if (!in_array($jenis_kendaraan, ['motor', 'mobil'], true)) {
        echo "<script>
            alert('Jenis kendaraan tidak valid!');
            window.history.back();
        </script>";
        exit;
    }

    // Memastikan tarif tidak negatif
    if ($tarif_per_jam < 0) {
        echo "<script>
            alert('Tarif per jam tidak boleh kurang dari 0!');
            window.history.back();
        </script>";
        exit;
    }

    // Menyiapkan query update dengan prepared statement
    $stmt = mysqli_prepare(
        $koneksi,
        "UPDATE tb_tarif
         SET jenis_kendaraan = ?, tarif_per_jam = ?
         WHERE id_tarif = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "sii",
        $jenis_kendaraan,
        $tarif_per_jam,
        $id
    );

    // Menjalankan query update
    if (mysqli_stmt_execute($stmt)) {

        mysqli_stmt_close($stmt);

        // Kembali ke halaman daftar tarif
        header("Location: tampil_tarif.php");
        exit;

    } else {

        echo "Data gagal diubah: " . mysqli_stmt_error($stmt);

        mysqli_stmt_close($stmt);
        exit;
    }
}

// ============================================
// MENGAMBIL DATA TARIF BERDASARKAN ID
// ============================================

if (!isset($_GET['id'])) {
    echo "<script>
        alert('ID tarif tidak ditemukan!');
        window.location='tampil_tarif.php';
    </script>";
    exit;
}

// Mengamankan ID tarif
$id = (int) $_GET['id'];

// Mengambil data tarif
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT * FROM tb_tarif WHERE id_tarif = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$hasil = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($hasil);

mysqli_stmt_close($stmt);

// Mengecek apakah data tersedia
if (!$data) {
    echo "<script>
        alert('Data tarif tidak ditemukan!');
        window.location='tampil_tarif.php';
    </script>";
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Tarif Parkir</title>

    <style>
        /* Pengaturan dasar halaman */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            padding: 35px 18px;
            background: #f3f5f3;
            color: #29332d;
        }

        /* Kotak utama form */
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        /* Bagian judul */
        .form-header {
            background: #30483a;
            color: #ffffff;
            padding: 26px 30px;
        }

        .form-header h2 {
            font-size: 24px;
            margin-bottom: 7px;
        }

        .form-header p {
            font-size: 14px;
            color: #dce6df;
        }

        /* Isi formulir */
        .form-content {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
        }

        input,
        select {
            width: 100%;
            padding: 13px 14px;
            border: 1px solid #d4ddd6;
            border-radius: 8px;
            background: #ffffff;
            color: #29332d;
            font-size: 14px;
            outline: none;
            transition: 0.2s;
        }

        input:focus,
        select:focus {
            border-color: #557a62;
            box-shadow: 0 0 0 3px rgba(85, 122, 98, 0.13);
        }

        .form-help {
            display: block;
            margin-top: 7px;
            font-size: 12px;
            line-height: 1.5;
            color: #727d75;
        }

        /* Tombol aksi */
        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 28px;
        }

        .btn {
            flex: 1;
            display: inline-block;
            padding: 13px 16px;
            border: none;
            border-radius: 8px;
            text-align: center;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-simpan {
            background: #365b43;
            color: #ffffff;
        }

        .btn-simpan:hover {
            background: #274632;
        }

        .btn-kembali {
            background: #e9eeea;
            color: #34443a;
        }

        .btn-kembali:hover {
            background: #dce4de;
        }

        /* Tampilan pada layar HP */
        @media (max-width: 480px) {
            body {
                padding: 20px 12px;
            }

            .form-header {
                padding: 22px 20px;
            }

            .form-content {
                padding: 22px 20px;
            }

            .form-actions {
                flex-direction: column;
            }
        }
    </style>

</head>

<body>

    <div class="form-container">

        <div class="form-header">
            <h2>Edit Tarif Parkir</h2>
            <p>Perbarui jenis kendaraan dan tarif parkir per jam.</p>
        </div>

        <div class="form-content">

            <form action="edit_tarif.php?id=<?= (int) $data['id_tarif']; ?>" method="POST">

                <!-- ID tarif disimpan secara tersembunyi -->
                <input type="hidden" name="id_tarif" value="<?= (int) $data['id_tarif']; ?>">

                <!-- Pilihan jenis kendaraan -->
                <div class="form-group">

                    <label for="jenis_kendaraan">
                        Jenis Kendaraan
                    </label>

                    <select id="jenis_kendaraan" name="jenis_kendaraan" required>

                        <option value="motor" <?= $data['jenis_kendaraan'] == 'motor' ? 'selected' : ''; ?>>
                            Motor
                        </option>

                        <option value="mobil" <?= $data['jenis_kendaraan'] == 'mobil' ? 'selected' : ''; ?>>
                            Mobil
                        </option>

                    </select>

                    <span class="form-help">
                        Pilih jenis kendaraan yang akan dikenakan tarif.
                    </span>

                </div>

                <!-- Input tarif per jam -->
                <div class="form-group">

                    <label for="tarif_per_jam">
                        Tarif Per Jam (Rp)
                    </label>

                    <input type="number" id="tarif_per_jam" name="tarif_per_jam"
                        value="<?= (int) $data['tarif_per_jam']; ?>" min="0" step="1" placeholder="Contoh: 3000"
                        required>

                    <span class="form-help">
                        Masukkan biaya parkir untuk setiap jam.
                    </span>

                </div>

                <!-- Tombol aksi -->
                <div class="form-actions">

                    <a href="tampil_tarif.php" class="btn btn-kembali">
                        Kembali
                    </a>

                    <button type="submit" name="update" class="btn btn-simpan">
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    </div>

</body>

</html>