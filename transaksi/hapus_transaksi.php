```php
<?php

// Memanggil koneksi database
require_once '../koneksi.php';

// Mengecek apakah ID transaksi dikirim melalui URL
// Contoh: hapus_transaksi.php?id=1
if (isset($_GET['id'])) {

    // Mengambil ID dari URL
    $id = $_GET['id'];

    // Menghapus histori yang menggunakan ID transaksi tersebut
    $query_histori = mysqli_query(
        $koneksi,
        "DELETE FROM tb_histori WHERE id_transaksi='$id'"
    );

    // Mengecek apakah histori berhasil dihapus
    if ($query_histori) {

        // Setelah histori dihapus,
        // baru menghapus data transaksi
        $query_transaksi = mysqli_query(
            $koneksi,
            "DELETE FROM tb_transaksi WHERE id_transaksi='$id'"
        );

        // Jika transaksi berhasil dihapus
        if ($query_transaksi) {

            // Kembali ke halaman transaksi
            header("Location: tampil_transaksi.php");
            exit;

        } else {

            // Jika transaksi gagal dihapus
            echo "Gagal menghapus transaksi: " . mysqli_error($koneksi);
        }

    } else {

        // Jika histori gagal dihapus
        echo "Gagal menghapus histori: " . mysqli_error($koneksi);
    }

} else {

    // Jika ID tidak dikirim
    echo "ID transaksi tidak ditemukan.";
}

?>
```

**Urutannya sekarang:**

```text
Klik Hapus
↓
Ambil id_transaksi
↓
Hapus data di tb_histori
↓
Hapus data di tb_transaksi
↓
Kembali ke tampil_transaksi.php


Jadi error `Cannot delete or update a parent row` tidak muncul lagi karena data anak di `tb_histori` dihapus terlebih
dahulu.