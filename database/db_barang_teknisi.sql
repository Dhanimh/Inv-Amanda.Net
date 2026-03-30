-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 18 Okt 2025 pada 19.14
-- Versi server: 10.4.25-MariaDB
-- Versi PHP: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_barang_teknisi`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang`
--

CREATE TABLE `barang` (
  `id` int(11) NOT NULL,
  `kode_barang` varchar(50) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `merk` varchar(50) DEFAULT NULL,
  `satuan` varchar(20) DEFAULT NULL,
  `stok` int(11) DEFAULT 0,
  `harga` decimal(15,2) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `barang`
--

INSERT INTO `barang` (`id`, `kode_barang`, `nama_barang`, `kategori`, `merk`, `satuan`, `stok`, `harga`, `deskripsi`, `created_at`) VALUES
(1, 'BRG001', 'Router WiFi AC1200', 'Router', 'TP-Link', 'Unit', 14, '350000.00', '', '2025-10-12 13:55:53'),
(2, 'BRG002', 'Access Point Indoor', 'Access Point', 'Ubiquiti', 'Unit', 20, '450000.00', NULL, '2025-10-12 13:55:53'),
(3, 'BRG003', 'Kabel UTP Cat6', 'Kabel', 'AMP', 'Meter', 400, '5000.00', NULL, '2025-10-12 13:55:53'),
(4, 'BRG004', 'Switch 8 Port Gigabit', 'Switch', 'Cisco', 'Unit', 11, '750000.00', NULL, '2025-10-12 13:55:53'),
(5, 'BRG005', 'ONT HG8145V5', 'ONT', 'Huawei', 'Unit', 30, '250000.00', '', '2025-10-12 13:55:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `aktivitas` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id`, `id_user`, `aktivitas`, `keterangan`, `created_at`) VALUES
(1, 1, 'Login ke sistem', NULL, '2025-10-13 03:30:34'),
(2, 1, 'Menambah barang keluar', 'Kode: TRX-K-202510130001, Barang: Router WiFi AC1200, Teknisi: Budi Santoso, Jumlah: 2', '2025-10-13 03:38:39'),
(3, 1, 'Menambah user baru', 'Username: agungp, Role: teknisi', '2025-10-13 03:40:07'),
(4, 1, 'Logout dari sistem', NULL, '2025-10-13 03:40:29'),
(5, 2, 'Login ke sistem', NULL, '2025-10-13 03:40:52'),
(6, 2, 'Menambah barang masuk', 'Kode: TRX-M-202510130001, Barang: Switch 8 Port Gigabit, Jumlah: 1', '2025-10-13 03:47:44'),
(7, 2, 'Logout dari sistem', NULL, '2025-10-13 03:52:06'),
(8, 2, 'Login ke sistem', NULL, '2025-10-13 03:57:57'),
(9, 2, 'Mengubah data teknisi', 'Teknisi: Andi Pratama', '2025-10-13 04:02:09'),
(10, 2, 'Logout dari sistem', NULL, '2025-10-13 04:03:50'),
(11, 1, 'Login ke sistem', NULL, '2025-10-13 04:05:20'),
(12, 1, 'Logout dari sistem', NULL, '2025-10-13 04:10:46'),
(13, 1, 'Login ke sistem', NULL, '2025-10-13 04:11:59'),
(14, 1, 'Login ke sistem', NULL, '2025-10-13 04:19:42'),
(15, 1, 'Login ke sistem', NULL, '2025-10-13 04:32:19'),
(16, 1, 'Menambah barang keluar', 'Kode: TRX-K-202510130002, Barang: Kabel UTP Cat6, Teknisi: Budi Santoso, Jumlah: 100', '2025-10-13 04:37:49'),
(17, 1, 'Logout dari sistem', NULL, '2025-10-13 07:05:18'),
(18, 1, 'Login ke sistem', NULL, '2025-10-13 07:05:30'),
(19, 1, 'Logout dari sistem', NULL, '2025-10-13 09:14:52'),
(20, 1, 'Login ke sistem', NULL, '2025-10-13 09:15:34'),
(21, 1, 'Login ke sistem', NULL, '2025-10-15 02:57:27'),
(22, 1, 'Login ke sistem', NULL, '2025-10-16 14:32:40'),
(23, 1, 'Logout dari sistem', NULL, '2025-10-16 14:36:57'),
(24, 1, 'Login ke sistem', NULL, '2025-10-16 14:37:20'),
(25, 1, 'Logout dari sistem', NULL, '2025-10-16 14:38:36'),
(26, 1, 'Login ke sistem', NULL, '2025-10-16 14:54:41'),
(27, 1, 'Login ke sistem', NULL, '2025-10-16 14:56:18'),
(28, 1, 'Login ke sistem', NULL, '2025-10-16 15:55:52'),
(29, 1, 'Login ke sistem', NULL, '2025-10-18 01:34:06'),
(30, 1, 'Logout dari sistem', NULL, '2025-10-18 03:39:22'),
(31, 1, 'Login ke sistem', NULL, '2025-10-18 03:39:36'),
(32, 1, 'Logout dari sistem', NULL, '2025-10-18 03:39:42'),
(33, 2, 'Login ke sistem', NULL, '2025-10-18 03:39:47'),
(34, 2, 'Logout dari sistem', NULL, '2025-10-18 03:40:56'),
(35, 2, 'Login ke sistem', NULL, '2025-10-18 03:43:13'),
(36, 2, 'Logout dari sistem', NULL, '2025-10-18 03:45:37'),
(37, 2, 'Login ke sistem', NULL, '2025-10-18 03:48:08'),
(38, 2, 'Logout dari sistem', NULL, '2025-10-18 03:53:46'),
(39, 1, 'Login ke sistem', NULL, '2025-10-18 03:53:51'),
(40, 1, 'Logout dari sistem', NULL, '2025-10-18 03:54:02'),
(41, 1, 'Login ke sistem', NULL, '2025-10-18 03:58:00'),
(42, 1, 'Mengubah profil', '', '2025-10-18 03:58:24'),
(43, 1, 'Logout dari sistem', NULL, '2025-10-18 03:58:27'),
(44, 1, 'Login ke sistem', NULL, '2025-10-18 03:58:36'),
(45, 1, 'Logout dari sistem', NULL, '2025-10-18 03:58:57'),
(46, 1, 'Login ke sistem', NULL, '2025-10-18 04:03:38'),
(47, 1, 'Logout dari sistem', NULL, '2025-10-18 04:10:08'),
(48, 2, 'Login ke sistem', NULL, '2025-10-18 04:10:15'),
(49, 2, 'Mengubah profil', '', '2025-10-18 04:10:35'),
(50, 2, 'Logout dari sistem', NULL, '2025-10-18 04:10:43'),
(51, 1, 'Login ke sistem', NULL, '2025-10-18 04:10:50'),
(52, 1, 'Logout dari sistem', NULL, '2025-10-18 04:18:38'),
(53, 2, 'Login ke sistem', NULL, '2025-10-18 04:18:43'),
(54, 2, 'Login ke sistem', NULL, '2025-10-18 04:19:16'),
(55, 2, 'Logout dari sistem', NULL, '2025-10-18 04:19:33'),
(56, 2, 'Logout dari sistem', NULL, '2025-10-18 04:19:37'),
(57, 1, 'Login ke sistem', NULL, '2025-10-18 04:19:42'),
(58, 1, 'Mengubah data user', 'Username: admin', '2025-10-18 04:20:15'),
(59, 1, 'Login ke sistem', NULL, '2025-10-18 04:26:13'),
(60, 1, 'Menambah barang masuk', 'Kode: TRX-M-202510180001, Barang: ONT Fiber Optic, Jumlah: 5', '2025-10-18 04:28:42'),
(61, 1, 'Mengubah data barang', 'Barang: Router WiFi AC1200 (BRG001)', '2025-10-18 04:51:19'),
(62, 1, 'Menambah kategori', 'Kategori: ONT HG8145V5', '2025-10-18 04:51:34'),
(63, 1, 'Mengubah data barang', 'Barang: Router WiFi AC1200 (BRG001)', '2025-10-18 06:14:30'),
(64, 1, 'Login ke sistem', NULL, '2025-10-18 07:35:47'),
(65, 1, 'Logout dari sistem', NULL, '2025-10-18 07:36:27'),
(66, 1, 'Login ke sistem', NULL, '2025-10-18 07:39:47'),
(67, 1, 'Logout dari sistem', NULL, '2025-10-18 07:45:24'),
(68, 2, 'Login ke sistem', NULL, '2025-10-18 07:45:31'),
(69, 2, 'Menambah barang masuk', 'Kode: TRX-M-202510180002, Barang: Router WiFi AC1200, Jumlah: 1', '2025-10-18 07:45:44'),
(70, 2, 'Logout dari sistem', NULL, '2025-10-18 07:45:47'),
(71, 1, 'Login ke sistem', NULL, '2025-10-18 07:45:51'),
(72, 1, 'Menambah barang masuk', 'Kode: TRX-M-202510180003, Barang: ONT Fiber Optic, Jumlah: 1', '2025-10-18 08:02:50'),
(73, 1, 'Login ke sistem', NULL, '2025-10-18 08:13:33'),
(74, 1, 'Mengubah profil', '', '2025-10-18 08:14:21'),
(75, 1, 'Mengubah profil', '', '2025-10-18 08:15:17'),
(76, 1, 'Mengubah data barang', 'Barang: ONT HG8145V5 (BRG005)', '2025-10-18 08:16:01'),
(77, 1, 'Menambah barang keluar', 'Kode: TRX-K-202510180001, Barang: ONT HG8145V5, Teknisi: Dedi Kurniawan, Jumlah: 1', '2025-10-18 08:16:49'),
(78, 1, 'Mengubah profil', '', '2025-10-18 08:24:50'),
(79, 1, 'Mengubah profil', '', '2025-10-18 08:25:33'),
(80, 1, 'Mengubah profil', '', '2025-10-18 08:30:18'),
(81, 1, 'Logout dari sistem', NULL, '2025-10-18 08:57:53'),
(82, 2, 'Login ke sistem', NULL, '2025-10-18 08:58:03'),
(83, 1, 'Logout dari sistem', NULL, '2025-10-18 12:00:49'),
(84, 1, 'Login ke sistem', NULL, '2025-10-18 12:05:21'),
(85, 1, 'Logout dari sistem', NULL, '2025-10-18 12:05:24'),
(86, 1, 'Login ke sistem', NULL, '2025-10-18 12:06:54'),
(87, 1, 'Logout dari sistem', NULL, '2025-10-18 12:06:58'),
(88, 1, 'Login ke sistem', NULL, '2025-10-18 12:27:51'),
(89, 1, 'Login ke sistem', NULL, '2025-10-18 12:38:48'),
(90, 1, 'Login ke sistem', NULL, '2025-10-18 12:40:14'),
(91, 1, 'Logout dari sistem', NULL, '2025-10-18 12:46:30'),
(92, 2, 'Login ke sistem', NULL, '2025-10-18 12:55:09'),
(93, 1, 'Logout dari sistem', NULL, '2025-10-18 12:58:43'),
(94, 2, 'Login ke sistem', NULL, '2025-10-18 12:58:48'),
(95, 2, 'Logout dari sistem', NULL, '2025-10-18 13:01:47'),
(96, 2, 'Logout dari sistem', NULL, '2025-10-18 13:10:01'),
(97, 1, 'Login ke sistem', NULL, '2025-10-18 13:10:21'),
(98, 1, 'Logout dari sistem', NULL, '2025-10-18 13:10:27'),
(99, 2, 'Login ke sistem', NULL, '2025-10-18 13:10:32'),
(100, 1, 'Login ke sistem', NULL, '2025-10-18 16:30:36'),
(101, 1, 'Mengubah data teknisi', 'Teknisi: Andi Pratama', '2025-10-18 16:33:04'),
(102, 1, 'Logout dari sistem', NULL, '2025-10-18 16:35:24'),
(103, 2, 'Login ke sistem', NULL, '2025-10-18 16:35:30'),
(104, 2, 'Logout dari sistem', NULL, '2025-10-18 16:38:46'),
(105, 1, 'Login ke sistem', NULL, '2025-10-18 16:38:56'),
(106, 1, 'Logout dari sistem', NULL, '2025-10-18 16:46:41'),
(107, 2, 'Login ke sistem', NULL, '2025-10-18 16:46:59'),
(108, 2, 'Logout dari sistem', NULL, '2025-10-18 16:48:53'),
(109, 1, 'Login ke sistem', NULL, '2025-10-18 17:07:35'),
(110, 1, 'Logout dari sistem', NULL, '2025-10-18 17:07:59');

-- --------------------------------------------------------

--
-- Struktur dari tabel `teknisi`
--

CREATE TABLE `teknisi` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `teknisi`
--

INSERT INTO `teknisi` (`id`, `nama`, `no_hp`, `alamat`, `status`, `created_at`) VALUES
(1, 'Budi Santoso', '081234567890', 'Jl. Raya No. 123', 'aktif', '2025-10-12 13:55:53'),
(2, 'Andi Pratama', '082345678901', 'Jl. Merdeka No. 45', 'aktif', '2025-10-12 13:55:53'),
(3, 'Dedi Kurniawan', '083456789012', 'Jl. Sudirman No. 67', 'aktif', '2025-10-12 13:55:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `kode_transaksi` varchar(50) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `id_teknisi` int(11) DEFAULT NULL,
  `id_user` int(11) NOT NULL,
  `tipe` enum('masuk','keluar') NOT NULL,
  `jumlah` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id`, `kode_transaksi`, `id_barang`, `id_teknisi`, `id_user`, `tipe`, `jumlah`, `tanggal`, `keterangan`, `created_at`) VALUES
(1, 'TRX-K-202510130001', 1, 1, 1, 'keluar', 2, '2025-10-13', 'Instalasi', '2025-10-13 03:38:39'),
(2, 'TRX-M-202510130001', 4, NULL, 2, 'masuk', 1, '2025-10-13', '', '2025-10-13 03:47:44'),
(3, 'TRX-K-202510130002', 3, 1, 1, 'keluar', 10, '2025-10-13', '', '2025-10-13 04:37:49'),
(4, 'TRX-M-202510180001', 5, NULL, 1, 'masuk', 5, '2025-10-18', 'HG8145V5', '2025-10-18 04:28:42'),
(5, 'TRX-M-202510180002', 1, NULL, 2, 'masuk', 1, '2025-10-18', '', '2025-10-18 07:45:44'),
(6, 'TRX-M-202510180003', 5, 3, 1, 'masuk', 1, '2025-10-18', 'Dismantle', '2025-10-18 08:02:50'),
(7, 'TRX-K-202510180001', 5, 3, 1, 'keluar', 1, '2025-10-18', 'Ganti Alat', '2025-10-18 08:16:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','teknisi') DEFAULT 'teknisi',
  `foto_profil` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama_lengkap`, `email`, `role`, `foto_profil`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$mTsOwJXunyy6bd3ZXgoDpeE2TpYNgBhKUOBqO7PMpjaFJFG8fO9JK', 'Administrator', 'admin@inventory.com', 'admin', 'uploads/profil/profil_1_1760775933.jpg', '2025-10-12 13:55:53', '2025-10-18 08:25:33'),
(2, 'agungp', '$2y$10$jT/EbMDpSzIadmNgTasqjOb6xPw.ul056uSVUWU8.F61Z87M70txW', 'Agung', 'agung@inventory.com', 'teknisi', 'uploads/profil/profil_2_1760760635.jpg', '2025-10-13 03:40:07', '2025-10-18 04:10:35');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_barang` (`kode_barang`);

--
-- Indeks untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `teknisi`
--
ALTER TABLE `teknisi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_transaksi` (`kode_transaksi`),
  ADD KEY `id_barang` (`id_barang`),
  ADD KEY `id_teknisi` (`id_teknisi`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `barang`
--
ALTER TABLE `barang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT untuk tabel `teknisi`
--
ALTER TABLE `teknisi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `log_aktivitas_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_teknisi`) REFERENCES `teknisi` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transaksi_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
