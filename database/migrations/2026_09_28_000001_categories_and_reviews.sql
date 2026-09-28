-- Jalankan setelah 2026_09_28_000000_product_category_specifications.sql.
-- Tabel kategori terpisah memudahkan admin menambah pilihan kategori baru.
CREATE TABLE IF NOT EXISTS `kategori` (
    `id_kategori` int NOT NULL AUTO_INCREMENT,
    `nama_kategori` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_kategori`),
    UNIQUE KEY `uq_kategori_nama` (`nama_kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Satu customer dapat memberi satu ulasan per produk; submit ulang memperbarui ulasan tersebut.
CREATE TABLE IF NOT EXISTS `ulasan_produk` (
    `id_ulasan` int NOT NULL AUTO_INCREMENT,
    `id_produk` int NOT NULL,
    `id_user` int NOT NULL,
    `rating` tinyint unsigned NOT NULL,
    `ulasan` text COLLATE utf8mb4_unicode_ci NOT NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_ulasan`),
    UNIQUE KEY `uq_ulasan_produk_user` (`id_produk`, `id_user`),
    KEY `idx_ulasan_produk` (`id_produk`),
    KEY `idx_ulasan_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `kategori` (`nama_kategori`) VALUES
    ('Smartphone'), ('Laptop'), ('Tablet'), ('Komponen PC'), ('Monitor'),
    ('Audio'), ('Wearable'), ('Aksesori'), ('Otomotif'), ('Kamera & Video'), ('Lainnya');

-- Sinkronkan kategori khusus yang sudah tersimpan pada produk lama.
INSERT IGNORE INTO `kategori` (`nama_kategori`)
SELECT DISTINCT TRIM(`kategori_produk`)
FROM `produk`
WHERE `kategori_produk` IS NOT NULL AND TRIM(`kategori_produk`) <> '';
