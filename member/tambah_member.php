<?php

// =========================================================
// KONEKSI DATABASE
// =========================================================

// Memanggil koneksi database
require_once '../koneksi.php';


// =========================================================
// MENGAMBIL DATA USER
// =========================================================

// Mengambil data user untuk ditampilkan di dropdown
$query_user = mysqli_query(
    $koneksi,
    "SELECT * FROM tb_user ORDER BY nama_lengkap ASC"
);


// =========================================================
// MENGAMBIL DATA AREA PARKIR
// =========================================================

// Mengambil data area parkir untuk ditampilkan di dropdown
$query_area = mysqli_query(
    $koneksi,
    "SELECT * FROM tb_area_parkir ORDER BY nama_area ASC"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Member</title>

    <!-- Memanggil CSS utama -->
    <link rel="stylesheet" href="../style.css">

</head>


<body>

    <!-- =====================================================
         CONTAINER FORM
         ===================================================== -->

    <div class="form-container">


        <!-- Judul halaman -->

        <h2>Tambah Member</h2>

        <p class="form-subtitle">
            Tambahkan data member baru ke dalam sistem parkir.
        </p>


        <!-- =================================================
             FORM TAMBAH MEMBER
             ================================================= -->

        <form action="simpan_member.php" method="POST">


            <!-- =================================================
                 NAMA MEMBER
                 ================================================= -->

            <div class="form-group">

                <label for="nama_member">

                    Nama Member

                    <span class="required">*</span>

                </label>


                <input
                    type="text"
                    id="nama_member"
                    name="nama_member"
                    placeholder="Masukkan nama member"
                    required
                >

            </div>


            <!-- =================================================
                 JENIS KELAMIN
                 ================================================= -->

            <div class="form-group">

                <label for="jenis_kelamin">

                    Jenis Kelamin

                    <span class="required">*</span>

                </label>


                <select
                    id="jenis_kelamin"
                    name="jenis_kelamin"
                    required
                >

                    <option value="">
                        -- Pilih Jenis Kelamin --
                    </option>

                    <option value="L">
                        Laki-laki
                    </option>

                    <option value="P">
                        Perempuan
                    </option>

                </select>

            </div>


            <!-- =================================================
                 NOMOR HP
                 ================================================= -->

            <div class="form-group">

                <label for="no_hp">

                    No HP

                </label>


                <input
                    type="text"
                    id="no_hp"
                    name="no_hp"
                    placeholder="Contoh: 081234567890"
                >

            </div>


            <!-- =================================================
                 TANGGAL DAFTAR
                 ================================================= -->

            <div class="form-group">

                <label for="tanggal_daftar">

                    Tanggal Daftar

                    <span class="required">*</span>

                </label>


                <input
                    type="date"
                    id="tanggal_daftar"
                    name="tanggal_daftar"
                    value="<?php echo date('Y-m-d'); ?>"
                    required
                >

            </div>


            <!-- =================================================
                 STATUS MEMBER
                 ================================================= -->

            <div class="form-group">

                <label for="status_aktif">

                    Status Aktif

                    <span class="required">*</span>

                </label>


                <select
                    id="status_aktif"
                    name="status_aktif"
                    required
                >

                    <option value="aktif">
                        Aktif
                    </option>

                    <option value="suspended">
                        Suspended
                    </option>

                </select>

            </div>


            <!-- =================================================
                 USER
                 ================================================= -->

            <div class="form-group">

                <label for="id_user">

                    User

                </label>


                <!--
                    id_user merupakan foreign key.
                    Data user diambil dari tb_user
                    sehingga tidak perlu mengetik ID secara manual.
                -->

                <select
                    id="id_user"
                    name="id_user"
                >

                    <option value="">
                        -- Pilih User --
                    </option>


                    <?php while ($user = mysqli_fetch_assoc($query_user)) { ?>

                        <option value="<?= $user['id_user']; ?>">

                            <?= htmlspecialchars($user['nama_lengkap']); ?>

                        </option>

                    <?php } ?>


                </select>


                <span class="form-help">
                    Pilih user yang akan dikaitkan dengan member.
                </span>

            </div>


            <!-- =================================================
                 AREA PARKIR
                 ================================================= -->

            <div class="form-group">

                <label for="id_area">

                    Area Parkir

                </label>


                <!--
                    id_area merupakan foreign key.
                    Data area diambil dari tb_area_parkir.
                -->

                <select
                    id="id_area"
                    name="id_area"
                >

                    <option value="">
                        -- Pilih Area --
                    </option>


                    <?php while ($area = mysqli_fetch_assoc($query_area)) { ?>

                        <option value="<?= $area['id_area']; ?>">

                            <?= htmlspecialchars($area['nama_area']); ?>

                        </option>

                    <?php } ?>


                </select>


                <span class="form-help">
                    Pilih area parkir untuk member.
                </span>

            </div>


            <!-- =================================================
                 TOMBOL
                 ================================================= -->

            <div class="form-actions">


                <!-- Tombol kembali -->

                <a
                    href="tampil_member.php"
                    class="btn-kembali"
                >

                    Kembali

                </a>


                <!-- Tombol simpan -->

                <button
                    type="submit"
                    name="simpan"
                    class="btn-simpan"
                >

                    Simpan Member

                </button>


            </div>


        </form>


    </div>


</body>

</html>