-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 31, 2026 at 08:09 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

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
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id` int NOT NULL,
  `kode_barang` varchar(50) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `merk` varchar(50) DEFAULT NULL,
  `satuan` varchar(20) DEFAULT NULL,
  `stok` int DEFAULT '0',
  `harga` decimal(15,2) DEFAULT NULL,
  `deskripsi` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id`, `kode_barang`, `nama_barang`, `kategori`, `merk`, `satuan`, `stok`, `harga`, `deskripsi`, `created_at`) VALUES
(1, 'BRG001', 'Router Wifi', 'Router', 'TP-Link', 'Unit', 99, '0.00', '', '2026-02-19 13:55:53'),
(2, 'BRG002', 'Access Point Indoor', 'Access Point', 'Ubiquiti', 'Unit', 100, '0.00', '', '2026-03-19 13:55:53'),
(3, 'BRG003', 'Kabel UTP Cat6', 'Kabel', 'AMP', 'Roll', 5, '0.00', '', '2026-03-19 13:55:53'),
(4, 'BRG004', 'Switch 8 Port Gigabit', 'Switch', 'Huawei', 'Unit', 8, '0.00', '', '2026-03-19 13:55:53'),
(5, 'BRG005', 'ONT', 'ONT', 'ZTE', 'Unit', 400, '0.00', '', '2026-03-19 13:55:53'),
(6, 'BRG006', 'Kabel FO', 'Kabel', 'Falcom', 'Box', 100, '0.00', '', '2026-03-29 17:17:34');

-- --------------------------------------------------------

--
-- Table structure for table `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id` int NOT NULL,
  `id_user` int NOT NULL,
  `aktivitas` varchar(255) NOT NULL,
  `keterangan` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id`, `id_user`, `aktivitas`, `keterangan`, `created_at`) VALUES
(1, 1, 'Login ke sistem', NULL, '2026-03-19 13:55:53'),
(2, 1, 'Menambah barang keluar', 'Kode: TRX-K-202510130001, Barang: Router WiFi AC1200, Teknisi: Budi Santoso, Jumlah: 2', '2026-03-19 13:55:53'),
(3, 1, 'Menambah user baru', 'Username: agungp, Role: teknisi', '2026-03-19 13:55:53'),
(4, 1, 'Logout dari sistem', NULL, '2026-03-19 13:55:53'),
(11, 1, 'Login ke sistem', NULL, '2026-03-19 13:55:53'),
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
(41, 1, 'Login ke sistem', NULL, '2025-10-18 03:58:00'),
(42, 1, 'Mengubah profil', '', '2025-10-18 03:58:24'),
(43, 1, 'Logout dari sistem', NULL, '2025-10-18 03:58:27'),
(44, 1, 'Login ke sistem', NULL, '2025-10-18 03:58:36'),
(45, 1, 'Logout dari sistem', NULL, '2025-10-18 03:58:57'),
(46, 1, 'Login ke sistem', NULL, '2025-10-18 04:03:38'),
(47, 1, 'Logout dari sistem', NULL, '2025-10-18 04:10:08'),
(51, 1, 'Login ke sistem', NULL, '2025-10-18 04:10:50'),
(52, 1, 'Logout dari sistem', NULL, '2025-10-18 04:18:38'),
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
(83, 1, 'Logout dari sistem', NULL, '2025-10-18 12:00:49'),
(84, 1, 'Login ke sistem', NULL, '2025-10-18 12:05:21'),
(85, 1, 'Logout dari sistem', NULL, '2025-10-18 12:05:24'),
(86, 1, 'Login ke sistem', NULL, '2025-10-18 12:06:54'),
(87, 1, 'Logout dari sistem', NULL, '2025-10-18 12:06:58'),
(88, 1, 'Login ke sistem', NULL, '2025-10-18 12:27:51'),
(89, 1, 'Login ke sistem', NULL, '2025-10-18 12:38:48'),
(90, 1, 'Login ke sistem', NULL, '2025-10-18 12:40:14'),
(91, 1, 'Logout dari sistem', NULL, '2025-10-18 12:46:30'),
(93, 1, 'Logout dari sistem', NULL, '2025-10-18 12:58:43'),
(97, 1, 'Login ke sistem', NULL, '2025-10-18 13:10:21'),
(98, 1, 'Logout dari sistem', NULL, '2025-10-18 13:10:27'),
(100, 1, 'Login ke sistem', NULL, '2025-10-18 16:30:36'),
(101, 1, 'Mengubah data teknisi', 'Teknisi: Andi Pratama', '2025-10-18 16:33:04'),
(102, 1, 'Logout dari sistem', NULL, '2025-10-18 16:35:24'),
(105, 1, 'Login ke sistem', NULL, '2025-10-18 16:38:56'),
(106, 1, 'Logout dari sistem', NULL, '2025-10-18 16:46:41'),
(109, 1, 'Login ke sistem', NULL, '2025-10-18 17:07:35'),
(110, 1, 'Logout dari sistem', NULL, '2025-10-18 17:07:59'),
(111, 1, 'Login ke sistem', NULL, '2026-03-29 15:58:22'),
(112, 1, 'Mengubah data barang', 'Barang: Modem Wifi (BRG001)', '2026-03-29 16:01:14'),
(113, 1, 'Mengubah data barang', 'Barang: Modem Wifi (BRG001)', '2026-03-29 16:01:28'),
(114, 1, 'Mengubah data barang', 'Barang: Access Point Indoor (BRG002)', '2026-03-29 16:02:12'),
(115, 1, 'Mengubah data barang', 'Barang: Kabel UTP Cat6 (BRG003)', '2026-03-29 16:03:06'),
(116, 1, 'Mengubah data barang', 'Barang: Switch 8 Port Gigabit (BRG004)', '2026-03-29 16:04:31'),
(117, 1, 'Mengubah data barang', 'Barang: ONT (BRG005)', '2026-03-29 16:08:10'),
(118, 1, 'Mengubah data barang', 'Barang: Router Wifi (BRG001)', '2026-03-29 16:10:24'),
(119, 1, 'Mengubah data teknisi', 'Teknisi: Bayu Andy', '2026-03-29 16:13:26'),
(120, 1, 'Mengubah data teknisi', 'Teknisi: Lukman', '2026-03-29 16:14:45'),
(121, 1, 'Mengubah data teknisi', 'Teknisi: Udin', '2026-03-29 16:19:53'),
(122, 1, 'Mengubah profil', '', '2026-03-29 16:23:36'),
(123, 1, 'Menambah barang keluar', 'Kode: TRX-K-202603290001, Barang: Router Wifi, Teknisi: Bayu Andy, Jumlah: 1', '2026-03-29 16:31:04'),
(124, 1, 'Menambah user baru', 'Username: bayuandy, Role: teknisi', '2026-03-29 16:37:25'),
(125, 1, 'Logout dari sistem', NULL, '2026-03-29 16:37:39'),
(126, 3, 'Login ke sistem', NULL, '2026-03-29 16:37:59'),
(127, 3, 'Logout dari sistem', NULL, '2026-03-29 16:39:36'),
(128, 1, 'Login ke sistem', NULL, '2026-03-29 16:39:44'),
(129, 1, 'Mengubah data user', 'Username: admin', '2026-03-29 16:43:00'),
(130, 1, 'Logout dari sistem', NULL, '2026-03-29 17:11:53'),
(131, 1, 'Login ke sistem', NULL, '2026-03-29 17:12:09'),
(132, 1, 'Logout dari sistem', NULL, '2026-03-29 17:12:15'),
(133, 1, 'Login ke sistem', NULL, '2026-03-29 17:16:21'),
(134, 1, 'Menambah barang baru', 'Barang: Kabel FO (BRG006)', '2026-03-29 17:17:34'),
(135, 1, 'Logout dari sistem', NULL, '2026-03-29 17:22:23'),
(136, 1, 'Login ke sistem', NULL, '2026-03-29 18:02:32'),
(137, 1, 'Logout dari sistem', NULL, '2026-03-29 18:02:51'),
(138, 1, 'Login ke sistem', NULL, '2026-03-30 01:07:24'),
(139, 1, 'Login ke sistem', NULL, '2026-03-30 03:58:09'),
(140, 1, 'Login ke sistem', NULL, '2026-03-30 04:51:29'),
(141, 1, 'Login ke sistem', NULL, '2026-03-30 04:54:13'),
(142, 1, 'Logout dari sistem', NULL, '2026-03-30 04:56:01'),
(143, 3, 'Login ke sistem', NULL, '2026-03-30 04:56:10'),
(144, 3, 'Logout dari sistem', NULL, '2026-03-30 06:02:19'),
(145, 1, 'Login ke sistem', NULL, '2026-03-30 06:02:31'),
(146, 1, 'Logout dari sistem', NULL, '2026-03-30 06:04:22'),
(147, 3, 'Login ke sistem', NULL, '2026-03-30 06:04:32'),
(148, 3, 'Logout dari sistem', NULL, '2026-03-30 06:05:51'),
(149, 1, 'Login ke sistem', NULL, '2026-03-30 06:06:08'),
(150, 1, 'Logout dari sistem', NULL, '2026-03-30 15:28:03'),
(151, 1, 'Login ke sistem', NULL, '2026-03-30 15:39:47'),
(152, 1, 'Logout dari sistem', NULL, '2026-03-30 17:59:49'),
(153, 1, 'Login ke sistem', NULL, '2026-03-30 18:00:19'),
(154, 1, 'Logout dari sistem', NULL, '2026-03-30 18:00:32'),
(155, 1, 'Login ke sistem', NULL, '2026-03-31 01:31:58'),
(156, 1, 'Logout dari sistem', NULL, '2026-03-31 01:32:06'),
(157, 1, 'Login ke sistem', NULL, '2026-03-31 01:42:56'),
(158, 1, 'Logout dari sistem', NULL, '2026-03-31 01:46:12'),
(159, 1, 'Login ke sistem', NULL, '2026-03-31 01:50:57'),
(160, 1, 'Logout dari sistem', NULL, '2026-03-31 01:51:05'),
(161, 3, 'Login ke sistem', NULL, '2026-03-31 01:51:11'),
(162, 3, 'Logout dari sistem', NULL, '2026-03-31 01:55:29'),
(163, 1, 'Login ke sistem', NULL, '2026-03-31 02:26:27'),
(164, 1, 'Logout dari sistem', NULL, '2026-03-31 02:26:32'),
(165, 1, 'Login ke sistem', NULL, '2026-03-31 02:58:38'),
(166, 1, 'Logout dari sistem', NULL, '2026-03-31 02:59:03'),
(167, 1, 'Login ke sistem', NULL, '2026-03-31 03:29:06'),
(168, 1, 'Logout dari sistem', NULL, '2026-03-31 07:28:47'),
(169, 1, 'Login ke sistem', NULL, '2026-03-31 07:29:00'),
(170, 1, 'Logout dari sistem', NULL, '2026-03-31 07:29:14'),
(171, 1, 'Login ke sistem', NULL, '2026-03-31 07:30:00'),
(172, 1, 'Logout dari sistem', NULL, '2026-03-31 08:06:33');

-- --------------------------------------------------------

--
-- Table structure for table `teknisi`
--

CREATE TABLE `teknisi` (
  `id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` text,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `teknisi`
--

INSERT INTO `teknisi` (`id`, `nama`, `no_hp`, `alamat`, `status`, `created_at`) VALUES
(1, 'Bayu Andy', '088991828293', 'Pencol, Cangakan', 'aktif', '2026-03-19 13:55:53'),
(2, 'Lukman', '085749258070', 'Jl. Raya Jatirejo', 'aktif', '2026-03-19 13:55:53'),
(3, 'Udin', '081973227313', 'Blimbing, Dawu', 'aktif', '2026-03-19 13:55:53');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int NOT NULL,
  `kode_transaksi` varchar(50) NOT NULL,
  `id_barang` int NOT NULL,
  `id_teknisi` int DEFAULT NULL,
  `id_user` int NOT NULL,
  `tipe` enum('masuk','keluar') NOT NULL,
  `jumlah` int NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','teknisi') DEFAULT 'teknisi',
  `foto_profil` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama_lengkap`, `email`, `role`, `foto_profil`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$mTsOwJXunyy6bd3ZXgoDpeE2TpYNgBhKUOBqO7PMpjaFJFG8fO9JK', 'Administrator', 'amandanet@gmail.com', 'admin', 'uploads/profil/profil_1_1774801416.jpeg', '2026-02-10 09:55:53', '2026-03-20 01:50:00'),
(3, 'bayuandy', '$2y$10$VdqSK1S3WnRSA1Ks0Fywo.wiMwV0omcvcgemnZjWFdR7s42NsHSc6', 'Bayu Andy', 'bayuandy@gmail.com', 'teknisi', NULL, '2026-03-29 16:37:25', '2026-03-29 16:37:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_barang` (`kode_barang`);

--
-- Indexes for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `teknisi`
--
ALTER TABLE `teknisi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_transaksi` (`kode_transaksi`),
  ADD KEY `id_barang` (`id_barang`),
  ADD KEY `id_teknisi` (`id_teknisi`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=173;

--
-- AUTO_INCREMENT for table `teknisi`
--
ALTER TABLE `teknisi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `log_aktivitas_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_teknisi`) REFERENCES `teknisi` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transaksi_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
