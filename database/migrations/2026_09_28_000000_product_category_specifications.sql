-- Jalankan sekali pada database ukk_kasir setelah memilih database yang benar.
-- Data deskripsi yang lama dipertahankan; kolom spesifikasi lama tidak ada,
-- jadi tetap NULL sampai admin mengisinya dari halaman Edit Produk.
ALTER TABLE `produk`
    MODIFY COLUMN `deskripsi_produk` TEXT COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    ADD COLUMN `kategori_produk` VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Aksesori' AFTER `nama_produk`,
    ADD COLUMN `spesifikasi_produk` TEXT COLLATE utf8mb4_unicode_ci DEFAULT NULL AFTER `deskripsi_produk`;

UPDATE `produk`
SET `kategori_produk` = CASE
    WHEN LOWER(`nama_produk`) LIKE '%laptop%' OR LOWER(`nama_produk`) LIKE '%macbook%' THEN 'Laptop'
    WHEN LOWER(`nama_produk`) LIKE '%matepad%' OR LOWER(`nama_produk`) LIKE '%tab s%' OR LOWER(`nama_produk`) LIKE '%tablet%' THEN 'Tablet'
    WHEN LOWER(`nama_produk`) LIKE '%airpods%' OR LOWER(`nama_produk`) LIKE '%marshal%' OR LOWER(`nama_produk`) LIKE '%speaker%' THEN 'Audio'
    WHEN LOWER(`nama_produk`) LIKE '%watch%' THEN 'Wearable'
    WHEN LOWER(`nama_produk`) LIKE '%vga%' OR LOWER(`nama_produk`) LIKE '%motherboard%' OR LOWER(`nama_produk`) LIKE '%ram%' THEN 'Komponen PC'
    WHEN LOWER(`nama_produk`) LIKE '%monitor%' THEN 'Monitor'
    WHEN LOWER(`nama_produk`) LIKE '%rubicon%' THEN 'Otomotif'
    WHEN LOWER(`nama_produk`) LIKE '%iphone%' OR LOWER(`nama_produk`) LIKE '%samsung s%' OR LOWER(`nama_produk`) LIKE '%rog%' OR LOWER(`nama_produk`) LIKE '%motorola%' OR LOWER(`nama_produk`) LIKE '%mate xt%' THEN 'Smartphone'
    ELSE 'Aksesori'
END;
