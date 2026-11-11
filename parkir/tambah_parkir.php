<?php

// =========================================================
// SESSION
// =========================================================

// Memulai session jika session belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// =========================================================
// CEK LOGIN
// =========================================================

// Jika belum login, kembali ke halaman login
if (!isset($_SESSION['login'])) {

    header("Location: ../login.php");
    exit;
}


// =========================================================
// KONEKSI DATABASE
// =========================================================

// Memanggil koneksi database
require_once '../koneksi.php';


// =========================================================
// DATA USER YANG SEDANG LOGIN
// =========================================================

// Mengambil ID user dari session
$id_user_login = $_SESSION['id_user'] ?? 0;

// Mengambil nama lengkap user dari session
$nama_user_login = $_SESSION['nama_lengkap'] ?? 'User';

// Mengambil username dari session
$username_login = $_SESSION['username'] ?? '-';

// Mengambil role dari session
$role_login = $_SESSION['role'] ?? '-';


// =========================================================
// VALIDASI USER
// =========================================================

// Jika ID user tidak ditemukan di session
if (empty($id_user_login)) {

    echo "
        <script>
            alert('Data user yang sedang login tidak ditemukan.');
            window.location='../logout.php';
        </script>
    ";

    exit;
}


// =========================================================
// MENGAMBIL DATA MEMBER
// =========================================================

// Mengambil member yang masih aktif
$query_member = mysqli_query(
    $koneksi,
    "SELECT *
     FROM tb_member
     WHERE status_aktif = 'aktif'
     ORDER BY nama_member ASC"
);


// =========================================================
// MENGAMBIL DATA AREA PARKIR
// =========================================================

// Mengambil semua area parkir
$query_area = mysqli_query(
    $koneksi,
    "SELECT *
     FROM tb_area_parkir
     ORDER BY nama_area ASC"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <!-- Karakter halaman -->
    <meta charset="UTF-8">

    <!-- Membuat halaman responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Judul halaman -->
    <title>Tambah Parkir - Sistem Parkir</title>

    <!-- CSS utama -->
    <link rel="stylesheet" href="http://localhost/parkir/style.css?v=2">

</head>

<body>


    <!-- =====================================================
         CONTAINER UTAMA
    ====================================================== -->

    <div class="container">


        <!-- =================================================
             HEADER
        ================================================== -->

        <div class="page-header">

            <div>

                <h2>
                    Tambah Data Parkir
                </h2>

                <p class="page-description">
                    Tambahkan kendaraan yang masuk ke area parkir.
                </p>

            </div>


            <!-- Tombol kembali -->

            <a href="tampil_parkir.php" class="kembali">
                ← Kembali
            </a>

        </div>


        <!-- =================================================
             FORM TAMBAH PARKIR
        ================================================== -->

        <form action="simpan_parkir.php" method="POST">


            <!-- =============================================
                 MEMBER
            ============================================== -->

            <label for="id_member">
                Member
            </label>

            <select name="id_member" id="id_member" required>

                <option value="">
                    -- Pilih Member --
                </option>


                <?php while ($member = mysqli_fetch_assoc($query_member)) { ?>

                    <option value="<?= htmlspecialchars($member['id_member']); ?>">

                        <?= htmlspecialchars($member['nama_member']); ?>

                    </option>

                <?php } ?>

            </select>


            <!-- =============================================
                 AREA PARKIR
            ============================================== -->

            <label for="id_area">
                Area Parkir
            </label>

            <select name="id_area" id="id_area" required>

                <option value="">
                    -- Pilih Area Parkir --
                </option>


                <?php while ($area = mysqli_fetch_assoc($query_area)) { ?>

                    <?php

                    // Menghitung sisa slot
                    $sisa_slot =
                        $area['kapasitas'] -
                        $area['terisi'];

                    ?>


                    <?php if ($sisa_slot > 0) { ?>

                        <!--
                            Jika masih ada slot,
                            area dapat dipilih.
                        -->

                        <option value="<?= htmlspecialchars($area['id_area']); ?>">

                            <?= htmlspecialchars($area['nama_area']); ?>

                            -
                            Sisa Slot:
                            <?= $sisa_slot; ?>

                        </option>

                    <?php } else { ?>

                        <!--
                            Jika area penuh,
                            pilihan dibuat disabled.
                        -->

                        <option value="<?= htmlspecialchars($area['id_area']); ?>" disabled>

                            <?= htmlspecialchars($area['nama_area']); ?>

                            -
                            Sisa Slot: 0
                            (PENUH)

                        </option>

                    <?php } ?>

                <?php } ?>

            </select>


            <!-- =============================================
                 PLAT NOMOR
            ============================================== -->

            <label for="plat_nomor">
                Plat Nomor
            </label>

            <input type="text" name="plat_nomor" id="plat_nomor" placeholder="Contoh: D 1234 AB" required>


            <!-- =============================================
                 WARNA KENDARAAN
            ============================================== -->

            <label for="warna_kendaraan">
                Warna Kendaraan
            </label>

            <input type="text" name="warna_kendaraan" id="warna_kendaraan" placeholder="Contoh: Hitam" required>


            <!-- =============================================
                 JENIS KENDARAAN
            ============================================== -->

            <label for="jenis_kendaraan">
                Jenis Kendaraan
            </label>

            <select name="jenis_kendaraan" id="jenis_kendaraan" required onchange="ubahTarif()">

                <option value="">
                    -- Pilih Jenis Kendaraan --
                </option>

                <option value="motor">
                    Motor
                </option>

                <option value="mobil">
                    Mobil
                </option>

                <option value="truk">
                    Truk
                </option>

                <option value="bus">
                    Bus
                </option>

            </select>


            <!-- =============================================
                 TARIF OTOMATIS
            ============================================== -->

            <label for="tampilan_tarif">
                Tarif Per Jam
            </label>

            <input type="text" id="tampilan_tarif" placeholder="Tarif otomatis" readonly>


            <!--
                ID tarif dikirim secara tersembunyi
                ke simpan_parkir.php.
            -->

            <input type="hidden" name="id_tarif" id="id_tarif">


            <!-- =============================================
                 WAKTU MASUK
            ============================================== -->

            <!--
                Waktu masuk tidak perlu dipilih manual.

                Waktu masuk akan otomatis dibuat oleh
                simpan_parkir.php menggunakan waktu server
                saat tombol Simpan ditekan.
            -->


            <!-- =============================================
                 USER YANG LOGIN
            ============================================== -->

            <label>
                User
            </label>


            <!--
                User otomatis mengikuti akun
                yang sedang login.
            -->

            <input type="text"
                value="<?= htmlspecialchars($nama_user_login); ?> (<?= htmlspecialchars(ucfirst($role_login)); ?>)"
                readonly>


            <!--
                ID user tidak ditampilkan kepada pengguna,
                tetapi tetap dikirim ke server.
            -->

            <input type="hidden" name="id_user" value="<?= htmlspecialchars($id_user_login); ?>">


            <!-- =============================================
                 TOMBOL SIMPAN
            ============================================== -->

            <button type="submit" name="simpan">
                Simpan
            </button>


        </form>

    </div>


    <!-- =====================================================
         JAVASCRIPT TARIF OTOMATIS
    ====================================================== -->

    <script>

        // =====================================================
        // FUNGSI MENGUBAH TARIF
        // =====================================================

        function ubahTarif() {

            // Mengambil jenis kendaraan
            const jenis =
                document.getElementById("jenis_kendaraan").value;


            // Input untuk menampilkan tarif
            const tampilanTarif =
                document.getElementById("tampilan_tarif");


            // Input tersembunyi untuk ID tarif
            const idTarif =
                document.getElementById("id_tarif");


            // =================================================
            // MOTOR
            // =================================================

            if (jenis === "motor") {

                tampilanTarif.value =
                    "Rp 3.000 / jam";

                idTarif.value = "1";

            }


            // =================================================
            // MOBIL
            // =================================================

            else if (jenis === "mobil") {

                tampilanTarif.value =
                    "Rp 5.000 / jam";

                idTarif.value = "2";

            }


            // =================================================
            // TRUK
            // =================================================

            else if (jenis === "truk") {

                tampilanTarif.value =
                    "Rp 8.000 / jam";

                idTarif.value = "3";

            }


            // =================================================
            // BUS
            // =================================================

            else if (jenis === "bus") {

                tampilanTarif.value =
                    "Rp 9.000 / jam";

                idTarif.value = "4";

            }


            // =================================================
            // BELUM MEMILIH JENIS
            // =================================================

            else {

                tampilanTarif.value = "";

                idTarif.value = "";

            }

        }

    </script>


</body>

</html>