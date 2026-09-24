-- Jalankan sekali pada database ukk_kasir.
-- Perubahan ini mempertahankan seluruh data dan transaksi admin yang sudah ada.

ALTER TABLE `user`
    MODIFY `level` enum('admin','petugas','user') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    ADD COLUMN `no_telepon` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL AFTER `username`,
    ADD COLUMN `alamat` text COLLATE utf8mb4_unicode_ci AFTER `no_telepon`;

ALTER TABLE `penjualan`
    MODIFY `metode` enum('Cash','Transfer','E-Wallet') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    MODIFY `status` enum('Selsesai','Proses','Dikirim','Selesai','Dibatalkan') COLLATE utf8mb4_unicode_ci DEFAULT 'Proses';
