<?php

// Memanggil file koneksi database
require_once '../koneksi.php';

// Memproses perubahan kapasitas ketika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {

    // Mengambil ID area dan kapasitas baru
    $id_area = (int) $_POST['id_area'];
    $kapasitas = (int) $_POST['kapasitas'];

    // Memastikan kapasitas minimal 1
    if ($kapasitas < 1) {
        echo "<script>
            alert('Kapasitas minimal harus 1!');
            window.history.back();
        </script>";
        exit;
    }

    // Mengambil jumlah kendaraan yang sedang terisi
    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT terisi FROM tb_area_parkir WHERE id_area = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id_area);
    mysqli_stmt_execute($stmt);

    $hasil = mysqli_stmt_get_result($stmt);
    $area = mysqli_fetch_assoc($hasil);

    mysqli_stmt_close($stmt);

    // Memastikan area ditemukan
    if (!$area) {
        echo "<script>
            alert('Data area parkir tidak ditemukan!');
            window.location='tampil_area_parkir.php';
        </script>";
        exit;
    }

    // Kapasitas tidak boleh lebih kecil dari kendaraan yang sedang parkir
    if ($kapasitas < (int) $area['terisi']) {
        echo "<script>
            alert('Kapasitas tidak boleh lebih kecil dari jumlah kendaraan yang sedang parkir!');
            window.history.back();
        </script>";
        exit;
    }

    // Mengubah kapasitas saja tanpa mengubah nama_area dan terisi
    $stmt = mysqli_prepare(
        $koneksi,
        "UPDATE tb_area_parkir SET kapasitas = ? WHERE id_area = ?"
    );

    mysqli_stmt_bind_param($stmt, "ii", $kapasitas, $id_area);

    // Menjalankan proses update
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);

        header("Location: tampil_area_parkir.php");
        exit;
    } else {
        echo "Gagal mengubah kapasitas: " . mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);
        exit;
    }
}

// Mengambil ID area dari URL
if (!isset($_GET['id'])) {
    echo "<script>
        alert('ID area tidak ditemukan!');
        window.location='tampil_area_parkir.php';
    </script>";
    exit;
}

$id = (int) $_GET['id'];

// Mengambil data area berdasarkan ID
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id_area, nama_area, kapasitas, terisi
     FROM tb_area_parkir
     WHERE id_area = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$hasil = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($hasil);

mysqli_stmt_close($stmt);

// Mengecek apakah data ditemukan
if (!$data) {
    echo "<script>
        alert('Data area parkir tidak ditemukan!');
        window.location='tampil_area_parkir.php';
    </script>";
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Area Parkir</title>

    <style>
        /* Mengatur tampilan dasar halaman */
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

        /* Kotak utama */
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
            padding: 25px 30px;
        }

        .form-header h2 {
            font-size: 24px;
            margin-bottom: 7px;
        }

        .form-header p {
            font-size: 14px;
            color: #dce6df;
        }

        /* Bagian formulir */
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

        input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #d4ddd6;
            border-radius: 8px;
            background: #ffffff;
            color: #29332d;
            font-size: 14px;
            outline: none;
            transition: 0.2s;
        }

        input:focus {
            border-color: #557a62;
            box-shadow: 0 0 0 3px rgba(85, 122, 98, 0.13);
        }

        /* Nama area hanya untuk ditampilkan */
        .readonly {
            background: #edf1ee;
            color: #647168;
            cursor: not-allowed;
        }

        .form-help {
            display: block;
            margin-top: 7px;
            color: #727d75;
            font-size: 12px;
            line-height: 1.5;
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

        /* Tampilan perangkat kecil */
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
            <h2>Edit Area Parkir</h2>
            <p>Ubah kapasitas area parkir sesuai kebutuhan.</p>
        </div>

        <div class="form-content">

            <form method="POST" action="edit_area_parkir.php?id=<?= (int) $data['id_area']; ?>">

                <!-- ID area disimpan secara tersembunyi -->
                <input type="hidden" name="id_area" value="<?= (int) $data['id_area']; ?>">

                <!-- Nama area hanya ditampilkan -->
                <div class="form-group">

                    <label for="nama_area">Nama Area</label>

                    <input type="text" id="nama_area" class="readonly"
                        value="<?= htmlspecialchars($data['nama_area'], ENT_QUOTES, 'UTF-8'); ?>" readonly>

                    <span class="form-help">
                        Nama area tidak dapat diubah melalui halaman ini.
                    </span>

                </div>

                <!-- Kapasitas yang bisa diedit -->
                <div class="form-group">

                    <label for="kapasitas">Kapasitas Area</label>

                    <input type="number" id="kapasitas" name="kapasitas" value="<?= (int) $data['kapasitas']; ?>"
                        min="<?= max(1, (int) $data['terisi']); ?>" required>

                    <span class="form-help">
                        Kapasitas tidak boleh lebih kecil daripada jumlah kendaraan yang sedang parkir.
                    </span>

                </div>

                <!-- Kolom Terisi tidak ditampilkan dan tidak diedit -->

                <div class="form-actions">

                    <a href="tampil_area_parkir.php" class="btn btn-kembali">
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