-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 25, 2026 at 03:33 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ukk_kasir`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int NOT NULL,
  `id_produk` int NOT NULL,
  `nama_produk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `harga` int DEFAULT NULL,
  `jumlah` int DEFAULT NULL,
  `total_harga` int DEFAULT NULL,
  `id_user` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_penjualan`
--

CREATE TABLE `detail_penjualan` (
  `id_detail` int NOT NULL,
  `id_penjualan` int DEFAULT NULL,
  `id_produk` int DEFAULT NULL,
  `jumlah_produk` int DEFAULT NULL,
  `sub_total` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

CREATE TABLE `keranjang` (
  `id_keranjang` int NOT NULL,
  `id_user` int NOT NULL,
  `id_produk` int NOT NULL,
  `jumlah` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `keranjang`
--

INSERT INTO `keranjang` (`id_keranjang`, `id_user`, `id_produk`, `jumlah`, `created_at`) VALUES
(1, 20, 23, 4, '2025-01-28 14:16:39');

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int NOT NULL,
  `nama_pelanggan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `no_telepon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penjualan`
--

CREATE TABLE `penjualan` (
  `id_penjualan` int NOT NULL,
  `tanggal_penjualan` date DEFAULT NULL,
  `id_kasir` int DEFAULT NULL,
  `total_harga` int DEFAULT NULL,
  `id_pelanggan` int DEFAULT NULL,
  `bayar` int DEFAULT NULL,
  `kembali` int DEFAULT NULL,
  `metode` enum('Cash','Transfer','E-Wallet') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Proses','Dikirim','Selesai','Dibatalkan') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Proses'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int NOT NULL,
  `nama_produk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi_produk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `harga` int DEFAULT NULL,
  `stok` int DEFAULT NULL,
  `gambar_produk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `nama_produk`, `deskripsi_produk`, `harga`, `stok`, `gambar_produk`) VALUES
(17, 'Mouse', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.', 100000, 768, 'mouse.png'),
(18, 'Monitor', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.', 200000, 959, 'monitor.jpg'),
(20, 'Vga Card', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.', 20000000, 971, 'vga.jpeg'),
(21, 'Motherboard', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.', 3000000, 974, 'motherboard.jpg'),
(22, 'Ram', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.', 300000, 981, 'ram.jpeg'),
(25, 'Laptop', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.', 5000000, 9990, 'laptop.jpeg'),
(28, 'Samsung S25 Ultra', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.\r\n', 25000000, 185, 's25 ultra.jpg'),
(29, 'Asus ROG 8 Pro', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.\r\n', 15000000, 77, 'rog 8.jpg'),
(30, 'iPhone 16 Pro Max', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.\r\n', 30000000, 98, 'iphone 16 promax.jpg'),
(31, 'Rubicon', 'Rubicon 2013', 900000000, 9, 'rubicon.jpg'),
(32, 'Motorola Signature', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.', 5000000, 34, 'br-m036969-02242_full09-4bb3b898-removebg-preview.png'),
(33, 'iPhone 17 Pro Max', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.', 20000000, 67, 'iphone-17-pro-17-pro-max-hero.png'),
(34, 'iPhone 18 Pro Max', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.', 24000000, 56, 'iPhone_18_Pro_Max_Burgundy_PDP_Image_Position_1__en-US.webp'),
(35, 'MackBook Neo', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.', 12000000, 30, 'mackboob neo.webp'),
(36, 'MackBook Pro M5', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.', 70000000, 46, 'apple_macbook_pro_14_inci_m5_max_2026_space_black_1_.webp'),
(37, 'Huawei Mate XT', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.', 18000000, 34, 'matext.webp'),
(38, 'Marshal Major V', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.', 2000000, 65, 'major 5.webp'),
(39, 'Marshal Embarton', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.', 800000, 75, 'marshal embarton.webp'),
(40, 'Airpods 5', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.', 4000000, 67, 'airpods 5.jpg'),
(41, 'Huawei MatePad Air', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.\r\n\r\n', 5000000, 88, 'huawei matepad air.webp'),
(42, 'Samsung Tab S10 Ultra', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.', 11000000, 40, 'tab s10 ultra.jpg'),
(43, 'Apple Watch Ultra 3', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.', 3400000, 45, 'apple watch ultra 3.jpg'),
(44, 'Huawei Watch Fit 5 Pro', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.', 6000000, 21, 'huawei watch fit 5 pro.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int NOT NULL,
  `business_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `business_name`, `email`, `address`, `phone`, `logo`) VALUES
(1, 'GadgetHub', 'GadgetHub@gmail.com', 'Dsn Terik, Ds Terik, Kec Krian, Kab Sidoarjo Rt 7 Rw 3', '085163024682', 'logo ucell2.png');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_telepon` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level` enum('admin','petugas','user') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama`, `username`, `no_telepon`, `alamat`, `password`, `level`) VALUES
(20, 'Ubed Dahlan', 'ubeddahlan', NULL, NULL, '$2y$10$.i96rAR25h4anpS9fmVP0uA/R7yZeCxZ6yp2mVLCTBRbSy60BBeiC', 'admin'),
(24, 'Ferdian Renaldy', 'ferdian', '086754329879', 'Penatarsewu 01/02 Tanggulangin Sidoarjo', '$2y$10$.i96rAR25h4anpS9fmVP0uA/R7yZeCxZ6yp2mVLCTBRbSy60BBeiC', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  ADD PRIMARY KEY (`id_detail`);

--
-- Indexes for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id_keranjang`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indexes for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`id_penjualan`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=253;

--
-- AUTO_INCREMENT for table `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  MODIFY `id_detail` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id_keranjang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `penjualan`
--
ALTER TABLE `penjualan`
  MODIFY `id_penjualan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
