<?php
// Memanggil koneksi database
require_once '../koneksi.php';

// Memproses data ketika tombol Simpan ditekan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Mengambil data dari form
    $id_member = $_POST['id_member'];
    $nama_member = $_POST['nama_member'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $no_hp = $_POST['no_hp'];
    $tanggal_daftar = $_POST['tanggal_daftar'];
    $status_aktif = $_POST['status_aktif'];

    // Mengambil ID user, jika kosong maka NULL
    $id_user = !empty($_POST['id_user'])
        ? (int) $_POST['id_user']
        : NULL;

    // Query update tanpa mengubah id_area
    $query = "UPDATE tb_member SET
        nama_member = ?,
        jenis_kelamin = ?,
        no_hp = ?,
        tanggal_daftar = ?,
        status_aktif = ?,
        id_user = ?
        WHERE id_member = ?";

    // Menyiapkan query
    $stmt = mysqli_prepare($koneksi, $query);

    if (!$stmt) {
        die("Query gagal disiapkan: " . mysqli_error($koneksi));
    }

    // Menghubungkan parameter dengan query
    mysqli_stmt_bind_param(
        $stmt,
        "sssssii",
        $nama_member,
        $jenis_kelamin,
        $no_hp,
        $tanggal_daftar,
        $status_aktif,
        $id_user,
        $id_member
    );

    // Menjalankan query
    if (mysqli_stmt_execute($stmt)) {

        // Kembali ke halaman data member jika berhasil
        mysqli_stmt_close($stmt);

        header("Location: tampil_member.php");
        exit;

    } else {
        echo "Gagal mengubah data: " .
            mysqli_stmt_error($stmt);

        mysqli_stmt_close($stmt);
        exit;
    }
}

// Mengambil ID member dari URL
if (isset($_GET['id'])) {

    $id = (int) $_GET['id'];

    // Mengambil data member berdasarkan ID
    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT * FROM tb_member WHERE id_member = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $hasil = mysqli_stmt_get_result($stmt);
    $member = mysqli_fetch_assoc($hasil);

    mysqli_stmt_close($stmt);

    // Mengecek apakah data ditemukan
    if (!$member) {
        echo "<script>
            alert('Data member tidak ditemukan!');
            window.location='tampil_member.php';
        </script>";
        exit;
    }

} else {
    echo "<script>
        alert('ID member tidak ditemukan!');
        window.location='tampil_member.php';
    </script>";
    exit;
}

// Mengambil daftar user untuk pilihan user
$query_user = mysqli_query(
    $koneksi,
    "SELECT id_user, nama_lengkap FROM tb_user ORDER BY nama_lengkap ASC"
);

if (!$query_user) {
    die("Gagal mengambil data user: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Member</title>

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
            background: #f3f5f3;
            color: #29332d;
            padding: 35px 18px;
        }

        /* Kotak utama form */
        .container {
            max-width: 650px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        /* Bagian judul */
        .header {
            background: #30483a;
            color: #ffffff;
            padding: 26px 30px;
        }

        .header h2 {
            font-size: 25px;
            margin-bottom: 7px;
        }

        .header p {
            color: #dce6df;
            font-size: 14px;
        }

        /* Bagian form */
        .form-content {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 21px;
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
            padding: 12px 14px;
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

        /* Tombol form */
        .button-group {
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

        .btn-save {
            background: #365b43;
            color: #ffffff;
        }

        .btn-save:hover {
            background: #274632;
        }

        .btn-back {
            background: #e9eeea;
            color: #34443a;
        }

        .btn-back:hover {
            background: #dce4de;
        }

        /* Menyesuaikan tampilan untuk HP */
        @media (max-width: 480px) {
            body {
                padding: 20px 12px;
            }

            .header {
                padding: 22px 20px;
            }

            .form-content {
                padding: 22px 20px;
            }

            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="header">
            <h2>Edit Member</h2>
            <p>Perbarui informasi member parkir.</p>
        </div>

        <div class="form-content">

            <form action="edit_member.php?id=<?= (int) $member['id_member']; ?>" method="POST">

                <!-- Menyimpan ID member yang sedang diedit -->
                <input type="hidden" name="id_member" value="<?= (int) $member['id_member']; ?>">

                <div class="form-group">
                    <label for="nama_member">Nama Member</label>
                    <input type="text" id="nama_member" name="nama_member"
                        value="<?= htmlspecialchars($member['nama_member'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                        placeholder="Masukkan nama member" required>
                </div>

                <div class="form-group">
                    <label for="jenis_kelamin">Jenis Kelamin</label>
                    <select id="jenis_kelamin" name="jenis_kelamin" required>

                        <option value="L" <?= $member['jenis_kelamin'] == 'L' ? 'selected' : ''; ?>>
                            Laki-laki
                        </option>

                        <option value="P" <?= $member['jenis_kelamin'] == 'P' ? 'selected' : ''; ?>>
                            Perempuan
                        </option>

                    </select>
                </div>

                <div class="form-group">
                    <label for="no_hp">Nomor HP</label>
                    <input type="text" id="no_hp" name="no_hp"
                        value="<?= htmlspecialchars($member['no_hp'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                        placeholder="Masukkan nomor HP">
                </div>

                <div class="form-group">
                    <label for="tanggal_daftar">Tanggal Daftar</label>
                    <input type="date" id="tanggal_daftar" name="tanggal_daftar"
                        value="<?= htmlspecialchars($member['tanggal_daftar'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div class="form-group">
                    <label for="status_aktif">Status Member</label>
                    <select id="status_aktif" name="status_aktif" required>

                        <option value="aktif" <?= $member['status_aktif'] == 'aktif' ? 'selected' : ''; ?>>
                            Aktif
                        </option>

                        <option value="suspended" <?= $member['status_aktif'] == 'suspended' ? 'selected' : ''; ?>>
                            Suspended
                        </option>

                    </select>
                </div>

                <div class="form-group">
                    <label for="id_user">User</label>
                    <select id="id_user" name="id_user">

                        <option value="">-- Pilih User --</option>

                        <?php while ($user = mysqli_fetch_assoc($query_user)) { ?>

                            <option value="<?= (int) $user['id_user']; ?>" <?= (string) $member['id_user'] === (string) $user['id_user'] ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($user['nama_lengkap'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>

                        <?php } ?>

                    </select>
                </div>

                <!-- Pilihan area parkir sudah dihapus -->

                <div class="button-group">
                    <button type="submit" class="btn btn-save">
                        Simpan Perubahan
                    </button>

                    <a href="tampil_member.php" class="btn btn-back">
                        Kembali
                    </a>
                </div>

            </form>

        </div>
    </div>

</body>

</html>