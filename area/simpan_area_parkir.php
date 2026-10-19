<?php

// =========================================================
// KONEKSI DATABASE
// =========================================================

require_once '../koneksi.php';


// =========================================================
// CEK FORM
// =========================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Mengambil data dari form
    $nama_area = trim($_POST['nama_area'] ?? '');
    $kapasitas = (int) ($_POST['kapasitas'] ?? 0);

    // Saat area baru dibuat, jumlah terisi dimulai dari 0
    $terisi = 0;


    // =====================================================
    // VALIDASI
    // =====================================================

    if ($nama_area === '') {
        die("Nama area wajib diisi.");
    }

    if ($kapasitas <= 0) {
        die("Kapasitas area harus lebih dari 0.");
    }


    // Terisi tidak boleh lebih besar dari kapasitas
    if ($terisi > $kapasitas) {
        die("Jumlah terisi tidak boleh lebih besar dari kapasitas.");
    }


    // =====================================================
    // SIMPAN DATA AREA
    // =====================================================

    $query = "
        INSERT INTO tb_area_parkir
        (
            nama_area,
            kapasitas,
            terisi
        )
        VALUES
        (
            ?,
            ?,
            ?
        )
    ";


    // Menyiapkan query
    $stmt = mysqli_prepare($koneksi, $query);


    if (!$stmt) {
        die(
            "Query gagal disiapkan: " .
            mysqli_error($koneksi)
        );
    }


    // Memasukkan nilai ke query
    mysqli_stmt_bind_param(
        $stmt,
        "sii",
        $nama_area,
        $kapasitas,
        $terisi
    );


    // Menjalankan query
    if (mysqli_stmt_execute($stmt)) {

        // Jika berhasil kembali ke halaman area
        header("Location: tampil_area_parkir.php");
        exit();

    } else {

        // Jika gagal
        echo "Gagal menambahkan area: " .
             mysqli_stmt_error($stmt);
    }


    // Menutup statement
    mysqli_stmt_close($stmt);

} else {

    // Jika halaman dibuka tanpa POST
    header("Location: tambah_area_parkir.php");
    exit();

}

?>