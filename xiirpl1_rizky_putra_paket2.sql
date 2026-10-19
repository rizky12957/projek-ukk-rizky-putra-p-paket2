-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 19 Okt 2026 pada 03.39
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `xiirpl1_rizky_putra_paket2`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_area_parkir`
--

CREATE TABLE `tb_area_parkir` (
  `id_area` int(11) NOT NULL,
  `nama_area` varchar(50) NOT NULL,
  `kapasitas` int(11) NOT NULL DEFAULT 0,
  `terisi` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_area_parkir`
--

INSERT INTO `tb_area_parkir` (`id_area`, `nama_area`, `kapasitas`, `terisi`) VALUES
(1, 'Area A', 50, 41),
(2, 'Area B', 100, 35),
(3, 'Area C', 20, 3),
(4, 'Area D', 40, 10),
(5, 'Area E', 30, 15);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_log_aktivitas`
--

CREATE TABLE `tb_log_aktivitas` (
  `id_log` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `aktivitas` text NOT NULL,
  `waktu_aktivitas` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_log_aktivitas`
--

INSERT INTO `tb_log_aktivitas` (`id_log`, `id_user`, `aktivitas`, `waktu_aktivitas`) VALUES
(1, 1, 'Login ke sistem sebagai Admin', '2026-09-08 07:00:00'),
(2, 2, 'Menginput transaksi parkir masuk kendaraan B 1234 CD', '2026-09-08 08:00:00'),
(3, 3, 'Menginput transaksi parkir masuk kendaraan D 5678 EFG', '2026-09-08 08:30:00'),
(4, 1, 'Mendaftarkan member baru Eko Pratama', '2026-09-08 09:00:00'),
(5, 2, 'Menginput transaksi parkir keluar kendaraan B 1234 CD', '2026-09-08 10:15:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_member`
--

CREATE TABLE `tb_member` (
  `id_member` int(11) NOT NULL,
  `nama_member` varchar(100) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `tanggal_daftar` date NOT NULL,
  `status_aktif` enum('aktif','suspended') NOT NULL DEFAULT 'aktif',
  `id_user` int(11) DEFAULT NULL,
  `id_area` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_member`
--

INSERT INTO `tb_member` (`id_member`, `nama_member`, `jenis_kelamin`, `no_hp`, `tanggal_daftar`, `status_aktif`, `id_user`, `id_area`) VALUES
(10, 'Rizky Putra', 'L', '08897654321', '2026-10-19', 'aktif', 1, NULL),
(11, 'Fandi Septia', 'P', '0876954321', '2026-10-19', 'aktif', 1, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_parkir`
--

CREATE TABLE `tb_parkir` (
  `id_parkir` int(11) NOT NULL,
  `id_member` int(11) DEFAULT NULL,
  `id_area` int(11) DEFAULT NULL,
  `plat_nomor` varchar(15) NOT NULL,
  `warna_kendaraan` varchar(30) DEFAULT NULL,
  `jenis_kendaraan` enum('motor','mobil','truk','bus') NOT NULL,
  `id_tarif` int(11) NOT NULL,
  `waktu_masuk` datetime NOT NULL DEFAULT current_timestamp(),
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_parkir`
--

INSERT INTO `tb_parkir` (`id_parkir`, `id_member`, `id_area`, `plat_nomor`, `warna_kendaraan`, `jenis_kendaraan`, `id_tarif`, `waktu_masuk`, `id_user`) VALUES
(1, NULL, NULL, 'B 1234 CD', 'Hitam', 'mobil', 2, '2026-09-08 08:00:00', 2),
(2, NULL, NULL, 'D 5678 EFG', 'Merah', 'motor', 1, '2026-09-08 08:30:00', 3),
(3, NULL, NULL, 'B 9999 XYZ', 'Putih', 'mobil', 2, '2026-09-08 09:15:00', 2),
(4, NULL, NULL, 'B 8888 VIP', 'Silver', 'mobil', 2, '2026-09-08 09:45:00', 2),
(5, NULL, NULL, 'F 4321 AAB', 'Biru', 'motor', 1, '2026-09-08 10:00:00', 3),
(6, NULL, NULL, 'D 43215 B', 'putih', 'mobil', 2, '2026-09-09 15:35:00', 3),
(7, NULL, NULL, 'D 43215 B', 'putih', 'mobil', 2, '2026-09-21 14:54:00', 2),
(8, NULL, NULL, 'B 9876 S', 'Merah', 'motor', 1, '2026-09-21 16:13:00', 5),
(9, NULL, NULL, 'B 98765 S', 'Kuning', 'mobil', 2, '2026-09-21 16:16:00', 5),
(10, NULL, NULL, 'B 98765 S', 'Merah', 'mobil', 2, '2026-09-21 16:23:00', 5),
(11, NULL, NULL, 'B 98765 S', 'Hitam', 'mobil', 2, '2026-09-21 16:25:00', 3),
(12, NULL, NULL, 'D 9876 SD', 'Abu', 'mobil', 2, '2026-09-22 06:16:42', 5),
(13, NULL, NULL, 'B 5678 FG', 'Kuning', 'truk', 1, '2026-09-22 06:20:29', 3),
(14, NULL, NULL, 'D 43215 B', 'Merah', 'mobil', 2, '2026-09-22 06:59:36', 2),
(15, NULL, NULL, 'D 43215 B', 'Hitam', 'truk', 1, '2026-09-22 07:53:21', 2),
(16, NULL, NULL, 'D 9876 SD', 'Hitam', 'mobil', 2, '2026-09-22 08:50:51', 2),
(17, NULL, NULL, 'D 9876 SD', 'Hitam', 'mobil', 2, '2026-09-23 08:01:33', 1),
(18, NULL, NULL, 'D 9876 SD', 'Merah', 'mobil', 2, '2026-09-23 08:09:40', 2),
(19, NULL, NULL, 'D 43215 B', 'Merah', 'mobil', 2, '2026-09-23 08:10:50', 2),
(21, NULL, 3, 'D 43215 B', 'Hitam', 'mobil', 2, '2026-09-23 08:18:33', 2),
(22, NULL, 2, 'D 43215 B', 'Merah', 'motor', 1, '2026-09-23 08:22:09', 2),
(23, NULL, 2, 'D 9876 SD', 'Hitam', 'motor', 1, '2026-09-23 08:26:09', 2),
(24, NULL, 5, 'G 5678 AB', 'Hitam', 'mobil', 2, '2026-09-23 08:29:03', 2),
(25, NULL, 4, 'B 5678 FG', 'Kuning', 'mobil', 2, '2026-09-23 08:35:39', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_tarif`
--

CREATE TABLE `tb_tarif` (
  `id_tarif` int(11) NOT NULL,
  `jenis_kendaraan` enum('motor','mobil','truk','bus') NOT NULL,
  `tarif_per_jam` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_tarif`
--

INSERT INTO `tb_tarif` (`id_tarif`, `jenis_kendaraan`, `tarif_per_jam`) VALUES
(1, 'motor', 3000.00),
(2, 'mobil', 5000.00),
(3, 'truk', 8000.00),
(4, 'bus', 9000.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_transaksi`
--

CREATE TABLE `tb_transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_parkir` int(11) NOT NULL,
  `waktu_keluar` datetime NOT NULL DEFAULT current_timestamp(),
  `durasi_jam` int(11) NOT NULL,
  `biaya_total` decimal(10,2) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_transaksi`
--

INSERT INTO `tb_transaksi` (`id_transaksi`, `id_parkir`, `waktu_keluar`, `durasi_jam`, `biaya_total`, `id_user`) VALUES
(1, 1, '2026-09-08 10:00:00', 2, 10000.00, 2),
(3, 3, '2026-09-08 12:15:00', 3, 15000.00, 2),
(4, 4, '2026-09-08 10:45:00', 1, 5000.00, 2),
(8, 7, '2026-09-21 16:00:00', 2, 14000.00, 2),
(9, 5, '2026-09-21 11:07:00', 314, 628000.00, 3),
(10, 2, '2026-09-21 11:12:00', 315, 630000.00, 3),
(11, 6, '2026-09-21 16:13:00', 289, 2023000.00, 3),
(12, 8, '2026-09-21 18:17:00', 3, 9000.00, 5),
(13, 9, '2026-09-21 17:23:00', 2, 14000.00, 5),
(15, 10, '2026-09-22 01:15:44', 9, 45000.00, 5),
(16, 11, '2026-09-22 01:20:40', 9, 45000.00, 3),
(17, 12, '2026-09-22 01:30:25', 1, 5000.00, 2),
(19, 13, '2026-09-22 02:00:15', 1, 3000.00, 2),
(20, 14, '2026-09-22 02:52:26', 1, 5000.00, 2),
(21, 15, '2026-09-22 08:39:38', 1, 3000.00, 2),
(22, 16, '2026-09-22 08:50:59', 1, 5000.00, 2),
(23, 17, '2026-09-23 08:01:49', 1, 5000.00, 1),
(24, 18, '2026-09-23 08:10:15', 1, 5000.00, 2),
(25, 19, '2026-09-23 08:11:04', 1, 5000.00, 2),
(26, 21, '2026-09-23 08:19:44', 1, 5000.00, 2),
(27, 22, '2026-09-23 08:22:29', 1, 3000.00, 2),
(28, 23, '2026-09-23 08:26:20', 1, 3000.00, 2),
(29, 24, '2026-09-23 08:29:15', 1, 5000.00, 2),
(30, 25, '2026-09-23 08:35:55', 1, 5000.00, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_user`
--

CREATE TABLE `tb_user` (
  `id_user` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','petugas','owner') NOT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_user`
--

INSERT INTO `tb_user` (`id_user`, `nama_lengkap`, `username`, `password`, `role`, `status_aktif`) VALUES
(1, 'Admin Utama', 'admin', 'admin123', 'admin', 1),
(2, 'Petugas Satu', 'p1', 'p1', 'petugas', 1),
(3, 'Petugas Dua', 'p2', 'p2', 'petugas', 1),
(4, 'Owner Parkir', 'owner', 'owner123', 'owner', 1),
(5, 'Petugas Tiga', 'p3', 'p3', 'petugas', 1);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tb_area_parkir`
--
ALTER TABLE `tb_area_parkir`
  ADD PRIMARY KEY (`id_area`);

--
-- Indeks untuk tabel `tb_log_aktivitas`
--
ALTER TABLE `tb_log_aktivitas`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `fk_log_user` (`id_user`);

--
-- Indeks untuk tabel `tb_member`
--
ALTER TABLE `tb_member`
  ADD PRIMARY KEY (`id_member`),
  ADD KEY `fk_member_user` (`id_user`),
  ADD KEY `fk_member_area` (`id_area`);

--
-- Indeks untuk tabel `tb_parkir`
--
ALTER TABLE `tb_parkir`
  ADD PRIMARY KEY (`id_parkir`),
  ADD KEY `fk_parkir_member` (`id_member`),
  ADD KEY `fk_parkir_tarif` (`id_tarif`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `tb_tarif`
--
ALTER TABLE `tb_tarif`
  ADD PRIMARY KEY (`id_tarif`);

--
-- Indeks untuk tabel `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD UNIQUE KEY `id_parkir` (`id_parkir`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tb_area_parkir`
--
ALTER TABLE `tb_area_parkir`
  MODIFY `id_area` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `tb_log_aktivitas`
--
ALTER TABLE `tb_log_aktivitas`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `tb_member`
--
ALTER TABLE `tb_member`
  MODIFY `id_member` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `tb_parkir`
--
ALTER TABLE `tb_parkir`
  MODIFY `id_parkir` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `tb_tarif`
--
ALTER TABLE `tb_tarif`
  MODIFY `id_tarif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10010;

--
-- AUTO_INCREMENT untuk tabel `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tb_log_aktivitas`
--
ALTER TABLE `tb_log_aktivitas`
  ADD CONSTRAINT `fk_log_user` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_member`
--
ALTER TABLE `tb_member`
  ADD CONSTRAINT `fk_member_area` FOREIGN KEY (`id_area`) REFERENCES `tb_area_parkir` (`id_area`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_member_user` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_parkir`
--
ALTER TABLE `tb_parkir`
  ADD CONSTRAINT `fk_parkir_member` FOREIGN KEY (`id_member`) REFERENCES `tb_member` (`id_member`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_parkir_tarif` FOREIGN KEY (`id_tarif`) REFERENCES `tb_tarif` (`id_tarif`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_parkir_user_masuk` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD CONSTRAINT `fk_transaksi_parkir` FOREIGN KEY (`id_parkir`) REFERENCES `tb_parkir` (`id_parkir`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transaksi_user_keluar` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
