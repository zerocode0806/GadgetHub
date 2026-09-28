-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 28, 2026 at 04:48 AM
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
-- Database: `ecommerce`
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
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int NOT NULL,
  `nama_kategori` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `created_at`) VALUES
(1, 'Smartphone', '2026-09-28 04:42:04'),
(2, 'Laptop', '2026-09-28 04:42:04'),
(3, 'Tablet', '2026-09-28 04:42:04'),
(4, 'Komponen PC', '2026-09-28 04:42:04'),
(5, 'Monitor', '2026-09-28 04:42:04'),
(6, 'Audio', '2026-09-28 04:42:04'),
(7, 'Wearable', '2026-09-28 04:42:04'),
(8, 'Aksesori', '2026-09-28 04:42:04'),
(9, 'Otomotif', '2026-09-28 04:42:04'),
(10, 'Kamera & Video', '2026-09-28 04:42:04'),
(11, 'Lainnya', '2026-09-28 04:42:04');

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
  `metode` enum('Cash On Delivery (COD)','QRIS','Bank') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Proses','Dikirim','Selesai','Dibatalkan') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Proses'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int NOT NULL,
  `nama_produk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kategori_produk` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Aksesori',
  `deskripsi_produk` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `spesifikasi_produk` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `harga` int DEFAULT NULL,
  `stok` int DEFAULT NULL,
  `gambar_produk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `nama_produk`, `kategori_produk`, `deskripsi_produk`, `spesifikasi_produk`, `harga`, `stok`, `gambar_produk`) VALUES
(17, 'Mouse', 'Aksesori', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.', NULL, 100000, 768, 'mouse.png'),
(18, 'Monitor', 'Monitor', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.', NULL, 200000, 959, 'monitor.jpg'),
(20, 'Vga Card', 'Komponen PC', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.', NULL, 20000000, 971, 'vga.jpeg'),
(21, 'Motherboard', 'Komponen PC', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.', NULL, 3000000, 974, 'motherboard.jpg'),
(22, 'Ram', 'Komponen PC', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.', NULL, 300000, 981, 'ram.jpeg'),
(25, 'Laptop', 'Laptop', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.', NULL, 5000000, 9990, 'laptop.jpeg'),
(28, 'Samsung S25 Ultra', 'Smartphone', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.\r\n', NULL, 25000000, 185, 's25 ultra.jpg'),
(29, 'Asus ROG 8 Pro', 'Smartphone', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.\r\n', NULL, 15000000, 77, 'rog 8.jpg'),
(30, 'iPhone 16 Pro Max', 'Smartphone', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.\r\n', NULL, 30000000, 98, 'iphone 16 promax.jpg'),
(31, 'Rubicon', 'Otomotif', 'Rubicon 2013', NULL, 900000000, 9, 'rubicon.jpg'),
(32, 'Motorola Signature', 'Smartphone', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.', NULL, 5000000, 34, 'br-m036969-02242_full09-4bb3b898-removebg-preview.png'),
(33, 'iPhone 17 Pro Max', 'Smartphone', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.', NULL, 20000000, 67, 'iphone-17-pro-17-pro-max-hero.png'),
(34, 'iPhone 18 Pro Max', 'Smartphone', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.', 'Network	Technology	\r\nGSM / HSPA / LTE / 5G\r\nLaunch	Announced	2026, September 09\r\nStatus	Available. Released 2026, September 18\r\nBody	Dimensions	163.4 x 78 x 8.8 mm (6.43 x 3.07 x 0.35 in)\r\nWeight	249 g (8.78 oz)\r\nBuild	Glass front (Ceramic Shield 2), aluminum frame, aluminum back/ glass back (Ceramic Shield)\r\nSIM	· Nano-SIM + eSIM + eSIM (max 2 at a time; International)\r\n· eSIM + eSIM (8 or more, max 2 at a time; USA)\r\n· Nano-SIM + Nano-SIM + eSIM + eSIM (max 2 at a time; China)\r\n 	IP68 dust tight and water resistant (immersible up to 6m for 30 min)\r\nDisplay	Type	LTPO Super Retina XDR OLED, 120Hz, HDR10, Dolby Vision, 1000 nits (typ), 1600 nits (HBM), 3000 nits (peak)\r\nSize	6.9 inches, 115.6 cm2 (~90.7% screen-to-body ratio)\r\nResolution	1320 x 2868 pixels, 19.5:9 ratio (~460 ppi density)\r\nProtection	Ceramic Shield 2\r\n 	Anti-reflective coating\r\nPlatform	OS	iOS 27\r\nChipset	Apple A20 Pro (2 nm)\r\nCPU	Hexa-core (2x4.93 GHz + 4x2.64 GHz)\r\nGPU	Apple GPU (7-core graphics)\r\nMemory	Card slot	No\r\nInternal	256GB 12GB RAM, 512GB 12GB RAM, 1TB 12GB RAM, 2TB 12GB RAM\r\n 	NVMe\r\nMain Camera	Triple	48 MP, f/1.5-4.0, 24mm (wide), 1/1.28\", 1.22µm, dual pixel PDAF, sensor-shift OIS (gen2)\r\n48 MP, f/2.8, 100mm (periscope telephoto), 1/2.55\", 0.7µm, PDAF, 3D sensor‑shift OIS, 4x optical zoom\r\n48 MP, f/2.2, 13mm, 120˚ (ultrawide), 1/2.55\", 0.7µm, PDAF\r\nTOF 3D LiDAR scanner (depth)\r\nFeatures	Dual-LED dual-tone flash, HDR (photo/panorama), Cinematic from regular video\r\nVideo	4K@24/25/30/60/100/120fps, 1080p@25/30/60/120/240fps, 10-bit HDR, Dolby Vision HDR (up to 120fps), ProRes, ProRes RAW (up to 120fps), Apple Log 2, 3D (spatial) video/audio, stereo sound rec.\r\nSelfie camera	Single	18 MP multi-aspect, f/1.9, 20mm (ultrawide), PDAF\r\nSL 3D, (depth/biometrics sensor)\r\nFeatures	HDR, Dolby Vision HDR, 3D (spatial) audio, stereo sound rec., ProRes RAW, Apple Log 2\r\nVideo	4K@24/25/30/60fps, 1080p@25/30/60/120fps, gyro-EIS\r\nSound	Loudspeaker	Yes, with stereo speakers\r\n3.5mm jack	No\r\nComms	WLAN	Wi-Fi 802.11 a/b/g/n/ac/6e/7, tri-band, hotspot\r\nBluetooth	6.0, A2DP, LE\r\nPositioning	GPS (L1+L5), GLONASS, GALILEO, BDS, QZSS, NavIC\r\nNFC	Yes\r\nRadio	No\r\nUSB	USB Type-C 3.2 Gen 2, DisplayPort\r\nFeatures	Sensors	Face ID, accelerometer, gyro, proximity, compass, barometer\r\n 	Ultra Wideband (UWB) support (gen2 chip)\r\nEmergency SOS, Messages and Find My via satellite\r\nBattery	Type	Market-dependent versions:\r\n· Li-Ion 5391 mAh - Nano SIM model\r\n· Li-Ion 5567 mAh - eSIM only model\r\nCharging	Wired, PD3.2, AVS, 50% in 15 min\r\n25W wireless MagSafe/Qi2, 50% in 30 min (15W - China)\r\n4.5W reverse wired\r\nMisc	Colors	Black, Silver, Glacier, Burgundy\r\nModels	A3717, A3473, A3716, A3718\r\nPrice	€ 1,599.00 / £ 1,299.00 / ₹ 179,900\r\nOur Tests	Performance	AnTuTu: 3463072 (v11)\r\nGeekBench: 12794 (v6), 11558 (v7)\r\n3DMark: 7931 (Wild Life Extreme)\r\nDisplay	969 nits max brightness (measured)\r\nLoudspeaker	-25.0 LUFS (Very good)\r\nBattery	\r\nActive use score 19:20h\r\nEU LABEL	Energy	Class A\r\nBattery	62:00h endurance, 1000 cycles\r\nFree fall	Class B (180 falls)\r\nRepairability	Class C', 24000000, 56, 'iPhone_18_Pro_Max_Burgundy_PDP_Image_Position_1__en-US.webp'),
(35, 'MackBook Neo', 'Aksesori', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.', NULL, 12000000, 30, 'mackboob neo.webp'),
(36, 'MackBook Pro M5', 'Aksesori', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.', NULL, 70000000, 46, 'apple_macbook_pro_14_inci_m5_max_2026_space_black_1_.webp'),
(37, 'Huawei Mate XT', 'Smartphone', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.', NULL, 18000000, 34, 'matext.webp'),
(38, 'Marshal Major V', 'Audio', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.', NULL, 2000000, 65, 'major 5.webp'),
(39, 'Marshal Embarton', 'Audio', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.', NULL, 800000, 75, 'marshal embarton.webp'),
(40, 'Airpods 5', 'Audio', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.', NULL, 4000000, 67, 'airpods 5.jpg'),
(41, 'Huawei MatePad Air', 'Tablet', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.\r\n\r\n', NULL, 5000000, 88, 'huawei matepad air.webp'),
(42, 'Samsung Tab S10 Ultra', 'Tablet', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.', NULL, 11000000, 40, 'tab s10 ultra.jpg'),
(43, 'Apple Watch Ultra 3', 'Wearable', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.', NULL, 3400000, 45, 'apple watch ultra 3.jpg'),
(44, 'Huawei Watch Fit 5 Pro', 'Wearable', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, dolores.', NULL, 6000000, 21, 'huawei watch fit 5 pro.jpg');

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
-- Table structure for table `ulasan_produk`
--

CREATE TABLE `ulasan_produk` (
  `id_ulasan` int NOT NULL,
  `id_produk` int NOT NULL,
  `id_user` int NOT NULL,
  `rating` tinyint UNSIGNED NOT NULL,
  `ulasan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ulasan_produk`
--

INSERT INTO `ulasan_produk` (`id_ulasan`, `id_produk`, `id_user`, `rating`, `ulasan`, `created_at`, `updated_at`) VALUES
(1, 34, 24, 5, 'mnatab', '2026-09-28 04:46:30', '2026-09-28 04:46:30');

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
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`),
  ADD UNIQUE KEY `uq_kategori_nama` (`nama_kategori`);

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
-- Indexes for table `ulasan_produk`
--
ALTER TABLE `ulasan_produk`
  ADD PRIMARY KEY (`id_ulasan`),
  ADD UNIQUE KEY `uq_ulasan_produk_user` (`id_produk`,`id_user`),
  ADD KEY `idx_ulasan_produk` (`id_produk`),
  ADD KEY `idx_ulasan_user` (`id_user`);

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
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
-- AUTO_INCREMENT for table `ulasan_produk`
--
ALTER TABLE `ulasan_produk`
  MODIFY `id_ulasan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
