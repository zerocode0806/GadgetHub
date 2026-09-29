-- Jalankan SATU KALI setelah migrasi 2026_09_28_000003_link_customers_to_accounts.sql.
-- Backup database terlebih dahulu. Migration ini mempertahankan histori transaksi;
-- hanya baris keranjang yang mengacu ke user/produk yang sudah tidak ada (atau jumlah <= 0)
-- yang dibuang karena tidak dapat ditampilkan/di-checkout dengan aman.

-- Selaraskan nilai kategori agar bisa direlasikan ke tabel kategori.
UPDATE `produk`
SET `kategori_produk` = 'Aksesori'
WHERE `kategori_produk` IS NULL OR TRIM(`kategori_produk`) = '';

UPDATE `produk`
SET `kategori_produk` = TRIM(`kategori_produk`)
WHERE `kategori_produk` <> TRIM(`kategori_produk`);

INSERT IGNORE INTO `kategori` (`nama_kategori`)
SELECT DISTINCT `kategori_produk`
FROM `produk`
WHERE `kategori_produk` IS NOT NULL AND TRIM(`kategori_produk`) <> '';

-- Lepas FK produk cart lama apa pun namanya, lalu pasang ulang dengan CASCADE.
-- Tabel `cart` tetap dipertahankan untuk kompatibilitas fitur kasir lama;
-- storefront memakai tabel `keranjang`.
SET @cart_product_fk_drop = COALESCE(
    (
        SELECT CONCAT('ALTER TABLE `cart` DROP FOREIGN KEY `', `CONSTRAINT_NAME`, '`')
        FROM `information_schema`.`KEY_COLUMN_USAGE`
        WHERE `TABLE_SCHEMA` = DATABASE()
          AND `TABLE_NAME` = 'cart'
          AND `COLUMN_NAME` = 'id_produk'
          AND `REFERENCED_TABLE_NAME` = 'produk'
        LIMIT 1
    ),
    'SELECT 1'
);
PREPARE cart_product_fk_drop_stmt FROM @cart_product_fk_drop;
EXECUTE cart_product_fk_drop_stmt;
DEALLOCATE PREPARE cart_product_fk_drop_stmt;

-- Normalisasi satu baris aktif per user-produk, jumlah duplikat dijumlahkan.
-- Referensi keranjang yang orphan dibersihkan karena produk/user-nya sudah tidak ada.
DROP TEMPORARY TABLE IF EXISTS `_tmp_gadgethub_keranjang`;
CREATE TEMPORARY TABLE `_tmp_gadgethub_keranjang` AS
SELECT MIN(k.`id_keranjang`) AS `id_keranjang`,
       k.`id_user`,
       k.`id_produk`,
       SUM(k.`jumlah`) AS `jumlah`,
       MIN(k.`created_at`) AS `created_at`
FROM `keranjang` k
INNER JOIN `user` u ON u.`id_user` = k.`id_user`
INNER JOIN `produk` p ON p.`id_produk` = k.`id_produk`
WHERE k.`jumlah` > 0
GROUP BY k.`id_user`, k.`id_produk`;

DELETE FROM `keranjang`;
INSERT INTO `keranjang` (`id_keranjang`, `id_user`, `id_produk`, `jumlah`, `created_at`)
SELECT `id_keranjang`, `id_user`, `id_produk`, `jumlah`, `created_at`
FROM `_tmp_gadgethub_keranjang`;
DROP TEMPORARY TABLE `_tmp_gadgethub_keranjang`;

-- Bersihkan cart lama yang tidak punya induk, agar FK bisa dijaga.
DELETE c
FROM `cart` c
LEFT JOIN `user` u ON u.`id_user` = c.`id_user`
LEFT JOIN `produk` p ON p.`id_produk` = c.`id_produk`
WHERE u.`id_user` IS NULL OR p.`id_produk` IS NULL;

-- Histori tetap disimpan; jika referensi lama sudah hilang, kolom relasinya dibuat NULL.
UPDATE `pelanggan` c
LEFT JOIN `user` u ON u.`id_user` = c.`id_user`
SET c.`id_user` = NULL
WHERE c.`id_user` IS NOT NULL AND u.`id_user` IS NULL;

UPDATE `penjualan` s
LEFT JOIN `user` u ON u.`id_user` = s.`id_kasir`
SET s.`id_kasir` = NULL
WHERE s.`id_kasir` IS NOT NULL AND u.`id_user` IS NULL;

UPDATE `penjualan` s
LEFT JOIN `pelanggan` c ON c.`id_pelanggan` = s.`id_pelanggan`
SET s.`id_pelanggan` = NULL
WHERE s.`id_pelanggan` IS NOT NULL AND c.`id_pelanggan` IS NULL;

UPDATE `detail_penjualan` d
LEFT JOIN `penjualan` s ON s.`id_penjualan` = d.`id_penjualan`
SET d.`id_penjualan` = NULL
WHERE d.`id_penjualan` IS NOT NULL AND s.`id_penjualan` IS NULL;

UPDATE `detail_penjualan` d
LEFT JOIN `produk` p ON p.`id_produk` = d.`id_produk`
SET d.`id_produk` = NULL
WHERE d.`id_produk` IS NOT NULL AND p.`id_produk` IS NULL;

-- Ulasan tanpa akun/produk induk tidak dapat dipertahankan sebagai ulasan valid.
DELETE r
FROM `ulasan_produk` r
LEFT JOIN `user` u ON u.`id_user` = r.`id_user`
LEFT JOIN `produk` p ON p.`id_produk` = r.`id_produk`
WHERE u.`id_user` IS NULL OR p.`id_produk` IS NULL;

ALTER TABLE `cart`
    ADD KEY `idx_cart_user` (`id_user`),
    ADD CONSTRAINT `fk_cart_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE,
    ADD CONSTRAINT `fk_cart_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

ALTER TABLE `keranjang`
    ADD UNIQUE KEY `uq_keranjang_user_produk` (`id_user`, `id_produk`),
    ADD CONSTRAINT `fk_keranjang_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE,
    ADD CONSTRAINT `fk_keranjang_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

ALTER TABLE `pelanggan`
    ADD CONSTRAINT `fk_pelanggan_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE SET NULL;

ALTER TABLE `penjualan`
    ADD KEY `idx_penjualan_user` (`id_kasir`),
    ADD KEY `idx_penjualan_pelanggan` (`id_pelanggan`),
    ADD CONSTRAINT `fk_penjualan_user` FOREIGN KEY (`id_kasir`) REFERENCES `user` (`id_user`) ON DELETE SET NULL,
    ADD CONSTRAINT `fk_penjualan_pelanggan` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE SET NULL;

ALTER TABLE `detail_penjualan`
    ADD KEY `idx_detail_penjualan_order` (`id_penjualan`),
    ADD KEY `idx_detail_penjualan_product` (`id_produk`),
    ADD CONSTRAINT `fk_detail_penjualan_order` FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan` (`id_penjualan`) ON DELETE CASCADE,
    ADD CONSTRAINT `fk_detail_penjualan_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE SET NULL;

ALTER TABLE `produk`
    ADD KEY `idx_produk_kategori` (`kategori_produk`),
    ADD CONSTRAINT `fk_produk_kategori` FOREIGN KEY (`kategori_produk`) REFERENCES `kategori` (`nama_kategori`) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE `ulasan_produk`
    ADD CONSTRAINT `fk_ulasan_produk_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE,
    ADD CONSTRAINT `fk_ulasan_produk_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;
