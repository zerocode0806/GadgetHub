-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 29, 2026 at 07:34 AM
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
-- Database: `gadgethub-db`
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
(1, 'Smartphone', '2026-09-29 07:20:48'),
(2, 'Laptop', '2026-09-29 07:20:48'),
(3, 'Tablet', '2026-09-29 07:20:48'),
(4, 'Komponen PC', '2026-09-29 07:20:48'),
(5, 'Monitor', '2026-09-29 07:20:48'),
(6, 'Audio', '2026-09-29 07:20:48'),
(7, 'Wearable', '2026-09-29 07:20:48'),
(8, 'Aksesori', '2026-09-29 07:20:48'),
(9, 'Otomotif', '2026-09-29 07:20:48'),
(10, 'Kamera & Video', '2026-09-29 07:20:48'),
(11, 'Lainnya', '2026-09-29 07:20:48');

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
(1, 24, 45, 1, '2026-09-29 07:27:25');

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int NOT NULL,
  `id_user` int DEFAULT NULL,
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
(17, 'Mouse', 'Aksesori', 'Mouse gaming dengan desain ergonomis yang nyaman digunakan untuk bekerja maupun bermain game. Dilengkapi sensor optik responsif dan tombol yang dapat digunakan untuk berbagai kebutuhan.', 'Jenis: Mouse Gaming\r\nKoneksi: USB\r\nSensor: Optical\r\nDPI: Hingga 12.000 DPI\r\nTombol: 6 tombol\r\nPolling Rate: 1000Hz\r\nKompatibilitas: Windows, macOS, Linux', 100000, 768, 'mouse.png'),
(18, 'Monitor', 'Monitor', 'Monitor 24 inci dengan tampilan Full HD yang cocok untuk bekerja, belajar, menonton film, maupun kebutuhan gaming ringan. Panel IPS memberikan warna yang lebih nyaman dan sudut pandang yang luas.', 'Ukuran Layar: 24 inci\r\nResolusi: Full HD 1920 × 1080\r\nPanel: IPS\r\nRefresh Rate: 75Hz\r\nResponse Time: 5ms\r\nPort: HDMI, VGA\r\nAspect Ratio: 16:9', 200000, 959, 'monitor.jpg'),
(20, 'Vga Card', 'Komponen PC', 'VGA Card berperforma tinggi yang dirancang untuk gaming, editing, rendering, dan berbagai kebutuhan komputasi grafis. Dilengkapi memori grafis berkapasitas besar untuk menangani aplikasi dan game modern.', 'Chipset GPU: NVIDIA GeForce RTX\r\nMemori: 12GB GDDR6\r\nMemory Interface: 192-bit\r\nInterface: PCI Express\r\nOutput: HDMI, DisplayPort\r\nDirectX: 12\r\nCooling: Dual Fan', 20000000, 971, 'vga.jpeg'),
(21, 'Motherboard', 'Komponen PC', 'Motherboard modern untuk membangun PC dengan performa stabil dan fleksibel. Mendukung prosesor generasi terbaru, RAM DDR5, penyimpanan NVMe, serta berbagai perangkat tambahan melalui slot ekspansi yang tersedia.', 'Socket CPU: AM5\r\nChipset: AMD B650\r\nForm Factor: ATX\r\nRAM: DDR5\r\nSlot RAM: 4\r\nPCIe: PCIe 4.0 / PCIe 5.0\r\nStorage: M.2 NVMe, SATA\r\nLAN: Gigabit Ethernet\r\nUSB: USB 3.2, USB 2.0', 3000000, 974, 'motherboard.jpg'),
(22, 'Ram', 'Komponen PC', 'RAM berkapasitas 16GB dengan teknologi DDR5 yang menawarkan kecepatan transfer data tinggi dan efisiensi daya. Cocok untuk gaming, multitasking, editing, maupun penggunaan komputer sehari-hari.', 'Kapasitas: 16GB\r\nTipe: DDR5\r\nSpeed: 5600MHz\r\nForm Factor: DIMM\r\nVoltage: 1.1V\r\nLatency: CL40\r\nKompatibilitas: Desktop PC', 300000, 981, 'ram.jpeg'),
(25, 'Laptop', 'Laptop', 'Laptop serbaguna dengan layar 15,6 inci dan performa yang cocok untuk kebutuhan kuliah, pekerjaan, multimedia, hingga produktivitas sehari-hari. Dilengkapi SSD NVMe untuk proses booting dan akses data yang cepat.', 'Layar: 15.6 inci\r\nResolusi: Full HD 1920 × 1080\r\nProcessor: Intel Core i5\r\nRAM: 16GB\r\nStorage: 512GB NVMe SSD\r\nGraphics: Integrated Graphics\r\nOperating System: Windows 11\r\nKonektivitas: Wi-Fi, Bluetooth\r\nPort: USB-A, USB-C, HDMI', 5000000, 9990, 'laptop.jpeg'),
(28, 'Samsung S25 Ultra', 'Smartphone', 'Smartphone flagship dengan layar besar dan performa tinggi untuk gaming, fotografi, multimedia, serta aktivitas harian. Menghadirkan kamera beresolusi tinggi, konektivitas 5G, dan perlindungan terhadap air serta debu.', 'Layar: 6.9 inci\r\nPanel: Dynamic AMOLED\r\nRefresh Rate: 120Hz\r\nProcessor: Snapdragon 8 Elite\r\nRAM: 12GB\r\nStorage: 256GB\r\nKamera Belakang: 200MP\r\nKamera Depan: 12MP\r\nJaringan: 5G\r\nOS: Android\r\nBaterai: 5000mAh\r\nCharging: Fast Charging\r\nKetahanan: IP68', 25000000, 185, 's25 ultra.jpg'),
(29, 'Asus ROG 8 Pro', 'Smartphone', 'Smartphone gaming berperforma tinggi dengan layar AMOLED refresh rate tinggi. Kombinasi RAM besar, penyimpanan luas, dan baterai berkapasitas besar membuatnya cocok untuk gaming dan penggunaan intensif.', 'Layar: 6.78 inci\r\nPanel: AMOLED\r\nRefresh Rate: 165Hz\r\nProcessor: Snapdragon 8 Gen 3\r\nRAM: 16GB\r\nStorage: 512GB\r\nKamera Belakang: 50MP\r\nKamera Depan: 32MP\r\nJaringan: 5G\r\nOS: Android\r\nBaterai: 5500mAh\r\nCharging: 65W\r\nKetahanan: IP68', 15000000, 77, 'rog 8.jpg'),
(30, 'iPhone 16 Pro Max', 'Smartphone', 'Smartphone flagship Apple dengan performa tinggi, layar premium, serta sistem kamera yang dirancang untuk fotografi dan videografi. Cocok untuk pengguna yang membutuhkan performa dan pengalaman ekosistem Apple.', 'Layar: 6.9 inci\r\nPanel: Super Retina XDR OLED\r\nRefresh Rate: 120Hz\r\nProcessor: Apple A18 Pro\r\nRAM: 8GB\r\nStorage: 256GB\r\nKamera Belakang: 48MP + 48MP + 12MP\r\nKamera Depan: 12MP\r\nJaringan: 5G\r\nOS: iOS\r\nPort: USB-C\r\nKetahanan: IP68', 30000000, 98, 'iphone 16 promax.jpg'),
(32, 'Motorola Signature', 'Smartphone', 'Smartphone premium dengan desain modern, layar berkualitas tinggi, dan performa yang mendukung berbagai aktivitas. Cocok untuk komunikasi, multimedia, fotografi, gaming, dan produktivitas sehari-hari.', 'Layar: 6.67 inci\r\nPanel: OLED\r\nRefresh Rate: 120Hz\r\nProcessor: Snapdragon\r\nRAM: 12GB\r\nStorage: 512GB\r\nKamera Belakang: Triple Camera\r\nKamera Depan: 32MP\r\nJaringan: 5G\r\nOS: Android\r\nBaterai: 5000mAh\r\nCharging: Fast Charging', 5000000, 34, 'br-m036969-02242_full09-4bb3b898-removebg-preview.png'),
(33, 'iPhone 17 Pro Max', 'Smartphone', 'Smartphone flagship Apple dengan layar besar, performa tinggi, dan kemampuan kamera yang dirancang untuk menghasilkan foto serta video berkualitas. Memiliki penyimpanan luas dan konektivitas modern untuk kebutuhan sehari-hari.', 'Layar: 6.9 inci\r\nPanel: Super Retina XDR OLED\r\nRefresh Rate: 120Hz\r\nProcessor: Apple A19 Pro\r\nRAM: 12GB\r\nStorage: 256GB / 512GB / 1TB\r\nKamera Belakang: Triple Camera\r\nKamera Depan: 24MP\r\nJaringan: 5G\r\nOS: iOS\r\nPort: USB-C\r\nKetahanan: IP68', 20000000, 67, 'iphone-17-pro-17-pro-max-hero.png'),
(34, 'iPhone 18 Pro Max', 'Smartphone', 'Smartphone flagship generasi terbaru dengan layar LTPO Super Retina XDR OLED berukuran besar dan chipset Apple A20 Pro. Menawarkan performa tinggi, sistem kamera profesional, penyimpanan hingga 2TB, serta dukungan konektivitas generasi terbaru.', 'Layar: 6.9 inci\r\nPanel: LTPO Super Retina XDR OLED\r\nRefresh Rate: 120Hz\r\nResolusi: 1320 × 2868 piksel\r\nChipset: Apple A20 Pro\r\nCPU: Hexa-core hingga 4.93GHz\r\nGPU: Apple 7-core GPU\r\nRAM: 12GB\r\nStorage: 256GB / 512GB / 1TB / 2TB NVMe\r\nKamera Belakang: Triple 48MP + LiDAR\r\nTelephoto: 48MP Periscope\r\nOptical Zoom: 4x\r\nKamera Depan: 18MP\r\nVideo: 4K 120fps\r\nOS: iOS 27\r\nBaterai: 5391–5567mAh\r\nCharging: 25W Wireless / 50% dalam 15 menit\r\nKonektivitas: 5G, Wi-Fi 7, Bluetooth 6.0, NFC\r\nPort: USB-C\r\nKetahanan: IP68\r\nBerat: 249g', 24000000, 56, 'iPhone_18_Pro_Max_Burgundy_PDP_Image_Position_1__en-US.webp'),
(35, 'MackBook Neo', 'Aksesori', 'Laptop Apple dengan desain tipis dan ringan yang cocok untuk mobilitas tinggi. Menawarkan performa efisien untuk pekerjaan, kuliah, pemrograman, multimedia, dan berbagai aktivitas produktivitas sehari-hari.', 'Layar: 13.6 inci\r\nProcessor: Apple Silicon\r\nRAM: 8GB\r\nStorage: 256GB SSD\r\nGraphics: Integrated GPU\r\nOperating System: macOS\r\nKonektivitas: Wi-Fi, Bluetooth\r\nPort: USB-C\r\nBaterai: Hingga 15 jam', 12000000, 30, 'mackboob neo.webp'),
(36, 'MackBook Pro M5', 'Aksesori', 'Laptop profesional Apple dengan performa tinggi menggunakan chip Apple M5 Max. Dirancang untuk kebutuhan berat seperti pemrograman, desain grafis, video editing, rendering, dan pekerjaan profesional lainnya.', 'Layar: 14.2 inci\r\nPanel: Liquid Retina XDR\r\nProcessor: Apple M5 Max\r\nRAM: 36GB\r\nStorage: 1TB SSD\r\nGraphics: Integrated Apple GPU\r\nOperating System: macOS\r\nKonektivitas: Wi-Fi 7, Bluetooth\r\nPort: Thunderbolt, USB-C, HDMI\r\nBaterai: Hingga 18 jam', 70000000, 46, 'apple_macbook_pro_14_inci_m5_max_2026_space_black_1_.webp'),
(37, 'Huawei Mate XT', 'Smartphone', 'Smartphone lipat inovatif dengan desain tri-fold yang menawarkan area layar lebih luas ketika dibuka. Cocok untuk pengguna yang menginginkan pengalaman multitasking dan produktivitas dalam perangkat mobile.', 'Layar: 10.2 inci\r\nPanel: OLED Foldable\r\nProcessor: Kirin Series\r\nRAM: 16GB\r\nStorage: 512GB\r\nKamera Belakang: Triple Camera\r\nJaringan: 5G\r\nOS: HarmonyOS\r\nBaterai: 5600mAh\r\nCharging: Fast Charging\r\nJenis: Tri-Fold Smartphone', 18000000, 34, 'matext.webp'),
(38, 'Marshal Major V', 'Audio', 'Headphone over-ear dengan karakter suara yang khas dan desain nyaman untuk penggunaan sehari-hari. Dilengkapi konektivitas Bluetooth dan baterai berdaya tahan panjang untuk menikmati musik tanpa sering melakukan pengisian daya.', 'Jenis: Over-Ear Headphone\r\nKoneksi: Bluetooth 5.2\r\nDriver: 40mm\r\nBattery Life: Hingga 100 jam\r\nCharging: USB-C\r\nAudio: Stereo\r\nFitur: Active Noise Cancellation\r\nMicrophone: Built-in\r\nKompatibilitas: Android, iOS, Windows, macOS', 2000000, 65, 'major 5.webp'),
(39, 'Marshal Embarton', 'Audio', 'Speaker Bluetooth portabel dengan desain ringkas dan suara yang bertenaga. Cocok digunakan di rumah, perjalanan, maupun aktivitas luar ruangan berkat daya tahan baterai dan perlindungan terhadap air.', 'Jenis: Portable Bluetooth Speaker\r\nKoneksi: Bluetooth\r\nDriver: Full Range\r\nOutput: Stereo\r\nBattery Life: Hingga 20 jam\r\nCharging: USB-C\r\nWater Resistance: IPX7\r\nFitur: Wireless Connectivity\r\nKompatibilitas: Smartphone, Tablet, Laptop', 800000, 75, 'marshal embarton.webp'),
(40, 'Airpods 5', 'Audio', 'Earbuds wireless dengan desain praktis dan konektivitas Bluetooth yang memudahkan penggunaan sehari-hari. Dilengkapi charging case sehingga perangkat dapat digunakan lebih lama tanpa harus sering mencari sumber listrik.', 'Jenis: True Wireless Stereo\r\nKoneksi: Bluetooth\r\nDriver: High Fidelity\r\nBattery Life: Hingga 6 jam\r\nTotal Battery Life: Hingga 30 jam dengan charging case\r\nCharging Case: USB-C\r\nFitur: Touch Control\r\nMicrophone: Built-in\r\nKompatibilitas: iOS, Android', 4000000, 67, 'airpods 5.jpg'),
(41, 'Huawei MatePad Air', 'Tablet', 'Tablet dengan layar berukuran besar yang cocok untuk produktivitas, hiburan, membaca, belajar, dan multimedia. Kapasitas baterai besar memberikan daya tahan yang mendukung penggunaan sepanjang hari.', 'Layar: 12.4 inci\r\nPanel: IPS\r\nResolusi: 2560 × 1600\r\nRefresh Rate: 120Hz\r\nProcessor: Kirin Series\r\nRAM: 8GB\r\nStorage: 128GB\r\nKamera Belakang: 13MP\r\nKamera Depan: 8MP\r\nOS: HarmonyOS\r\nBaterai: 10100mAh\r\nCharging: Fast Charging\r\nKonektivitas: Wi-Fi, Bluetooth', 5000000, 88, 'huawei matepad air.webp'),
(42, 'Samsung Tab S10 Ultra', 'Tablet', 'Tablet premium dengan layar Dynamic AMOLED 2X berukuran besar dan refresh rate tinggi. Cocok untuk produktivitas, hiburan, menggambar, gaming, hingga menikmati konten multimedia dengan tampilan yang tajam dan nyaman.', 'Layar: 14.6 inci\r\nPanel: Dynamic AMOLED 2X\r\nResolusi: 2960 × 1848\r\nRefresh Rate: 120Hz\r\nProcessor: Flagship Octa-Core\r\nRAM: 12GB\r\nStorage: 256GB\r\nKamera Belakang: 13MP + 8MP\r\nKamera Depan: Dual Camera\r\nOS: Android\r\nBaterai: 11200mAh\r\nCharging: 45W\r\nKonektivitas: Wi-Fi, Bluetooth, 5G', 11000000, 40, 'tab s10 ultra.jpg'),
(43, 'Apple Watch Ultra 3', 'Wearable', 'Smartwatch premium dengan desain tangguh dan fitur kesehatan serta kebugaran yang lengkap. Cocok untuk aktivitas olahraga, pemantauan kesehatan, navigasi, dan penggunaan sehari-hari dengan daya tahan baterai yang panjang.', 'Layar: 49mm\r\nPanel: Retina LTPO OLED\r\nProcessor: Apple S10\r\nStorage: 64GB\r\nGPS: Yes\r\nWater Resistance: 100m\r\nHealth Sensor: Heart Rate, ECG, SpO2\r\nConnectivity: Wi-Fi, Bluetooth, Cellular\r\nOS: watchOS\r\nBattery Life: Hingga 36 jam\r\nMaterial: Titanium', 3400000, 45, 'apple watch ultra 3.jpg'),
(44, 'Huawei Watch Fit 5 Pro', 'Wearable', 'Smartwatch dengan desain modern dan layar AMOLED yang jernih. Dilengkapi berbagai fitur pemantauan kesehatan, aktivitas olahraga, tidur, serta daya tahan baterai hingga beberapa hari untuk mendukung aktivitas sehari-hari.', 'Layar: AMOLED\r\nUkuran: 1.82 inci\r\nRefresh Rate: 60Hz\r\nRAM: 32MB\r\nStorage: 4GB\r\nGPS: Built-in\r\nHealth Sensor: Heart Rate, SpO2, Sleep Tracking\r\nWater Resistance: 5 ATM\r\nBattery Life: Hingga 10 hari\r\nConnectivity: Bluetooth\r\nKompatibilitas: Android, iOS', 6000000, 21, 'huawei watch fit 5 pro.jpg'),
(45, 'Hasselblad X2D 100C', 'Kamera & Video', 'Hasselblad X2D 100C adalah kamera mirrorless medium format premium yang dirancang untuk menghasilkan foto dengan detail, warna, dan dynamic range tingkat tinggi. Kamera ini menggunakan sensor 100MP berukuran 43,8 × 32,9 mm, dukungan warna 16-bit, serta stabilisasi gambar 5-axis hingga 7 stop. Dengan penyimpanan SSD internal 1TB dan EVF beresolusi tinggi, X2D 100C cocok untuk fotografi profesional, landscape, fashion, portrait, dan commercial photography', 'Sensor: 100MP BSI CMOS\r\nUkuran Sensor: 43.8 × 32.9 mm\r\nResolusi: 11656 × 8742 piksel\r\nColor Depth: 16-bit\r\nDynamic Range: Hingga 15 stop\r\nISO: 64–25600\r\nImage Stabilization: 5-axis IBIS, hingga 7 stop\r\nAutofocus: PDAF + CDAF\r\nPDAF Zones: 294 zona\r\nLens Mount: Hasselblad X System\r\nFile Format: Hasselblad 3FR RAW, JPEG\r\nInternal Storage: 1TB SSD\r\nExternal Storage: CFexpress Type B\r\nEVF: 5.76 juta titik OLED\r\nRear Display: 3.6 inci, 2.36 juta titik, touchscreen\r\nVideo: Hingga 2.7K 29.97fps\r\nKonektivitas: Wi-Fi, Bluetooth, USB-C\r\nBaterai: Li-ion, sekitar 420 foto\r\nDimensi: 148.5 × 106 × 74.5 mm\r\nBerat: 895g dengan baterai', 250000000, 78, 'hasselblad.webp'),
(46, 'Fujifilm GFX 100 II', 'Kamera & Video', 'Fujifilm GFX 100 II adalah kamera mirrorless medium format 102MP yang menggabungkan kualitas gambar resolusi sangat tinggi dengan kemampuan autofocus, burst shooting, dan video yang lebih berorientasi pada performa. Kamera ini menggunakan sensor GFX 102MP CMOS II HS dan X-Processor 5, serta memiliki IBIS hingga 8 stop. GFX 100 II juga mendukung perekaman video hingga 8K dan berbagai format video profesional', 'Sensor: 102MP GFX 102MP CMOS II HS\r\nUkuran Sensor: 43.8 × 32.9 mm\r\nProcessor: X-Processor 5\r\nResolusi Maksimum: 11648 × 7768 piksel\r\nColor Depth: 14-bit / 16-bit RAW\r\nISO Foto: ISO 80–12800\r\nExtended ISO: ISO 40–102400\r\nImage Stabilization: 5-axis IBIS, hingga 8 stop\r\nAutofocus: Subject Detection AF\r\nLens Mount: FUJIFILM G Mount\r\nContinuous Shooting: Hingga 8 fps\r\nStorage: SD Card, CFexpress Type B, SSD\r\nEVF: Electronic Viewfinder\r\nVideo: Hingga 8K 29.97p\r\n4K: Hingga 59.94p\r\nFull HD High Speed: Hingga 120p\r\nFormat RAW: RAF 14-bit / 16-bit\r\nKonektivitas: USB-C, HDMI, LAN, Wi-Fi, Bluetooth\r\nDimensi: 152.4 × 103.5 × 73.5 mm\r\nBerat: Sekitar 948g dengan baterai dan kartu', 200000000, 67, 'fujifilm.webp'),
(47, 'Sony A7R V', 'Kamera & Video', 'Sony A7R V adalah kamera mirrorless full-frame beresolusi tinggi yang menggunakan sensor Exmor R CMOS 61MP. Dibandingkan tiga kamera medium format di atas, sensor A7R V lebih kecil, tetapi kamera ini menawarkan autofocus berbasis AI, stabilisasi hingga 8 stop, EVF 9,44 juta titik, serta kemampuan video hingga 8K. Kamera ini cocok untuk landscape, portrait, studio, wildlife, commercial photography, dan kebutuhan hybrid foto-video', 'Sensor: 61MP Exmor R CMOS\r\nUkuran Sensor: 35.7 × 23.8 mm Full-Frame\r\nEffective Pixels: 61MP\r\nProcessor: BIONZ XR + AI Processing Unit\r\nResolusi Maksimum: 9504 × 6336 piksel\r\nColor Depth: 14-bit RAW\r\nISO Foto: 100–32000\r\nExtended ISO: 50–102400\r\nImage Stabilization: 5-axis IBIS, hingga 8 stop\r\nLens Mount: Sony E-mount\r\nAutofocus: AI-based Subject Recognition AF\r\nContinuous Shooting: Hingga 10 fps\r\nStorage: CFexpress Type A / SD\r\nEVF: 9.44 juta titik\r\nEVF Magnification: 0.90x\r\nLCD: 3.2 inci, touchscreen, fully articulating\r\nVideo: Hingga 8K 24/25p\r\n4K: Hingga 60p\r\nVideo Color: 10-bit 4:2:2\r\nFormat RAW: Sony ARW\r\nKonektivitas: Wi-Fi, Bluetooth, USB-C, HDMI\r\nImage Stabilization: 8 stop\r\nShutter Speed: Hingga 1/8000 detik', 220000000, 89, 'sonny a7rv.webp');

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
  ADD KEY `id_produk` (`id_produk`),
  ADD KEY `idx_cart_user` (`id_user`);

--
-- Indexes for table `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `idx_detail_penjualan_order` (`id_penjualan`),
  ADD KEY `idx_detail_penjualan_product` (`id_produk`);

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
  ADD PRIMARY KEY (`id_keranjang`),
  ADD UNIQUE KEY `uq_keranjang_user_produk` (`id_user`,`id_produk`),
  ADD KEY `fk_keranjang_produk` (`id_produk`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`),
  ADD KEY `idx_pelanggan_user` (`id_user`);

--
-- Indexes for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`id_penjualan`),
  ADD KEY `idx_penjualan_user` (`id_kasir`),
  ADD KEY `idx_penjualan_pelanggan` (`id_pelanggan`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD KEY `idx_produk_kategori` (`kategori_produk`);

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
  MODIFY `id_produk` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ulasan_produk`
--
ALTER TABLE `ulasan_produk`
  MODIFY `id_ulasan` int NOT NULL AUTO_INCREMENT;

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
  ADD CONSTRAINT `fk_cart_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cart_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  ADD CONSTRAINT `fk_detail_penjualan_order` FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan` (`id_penjualan`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_detail_penjualan_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE SET NULL;

--
-- Constraints for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD CONSTRAINT `fk_keranjang_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_keranjang_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD CONSTRAINT `fk_pelanggan_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE SET NULL;

--
-- Constraints for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD CONSTRAINT `fk_penjualan_pelanggan` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_penjualan_user` FOREIGN KEY (`id_kasir`) REFERENCES `user` (`id_user`) ON DELETE SET NULL;

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `fk_produk_kategori` FOREIGN KEY (`kategori_produk`) REFERENCES `kategori` (`nama_kategori`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `ulasan_produk`
--
ALTER TABLE `ulasan_produk`
  ADD CONSTRAINT `fk_ulasan_produk_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ulasan_produk_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
