-- Preserve each order's customer/address snapshot, but link it back to the
-- storefront account so the admin can aggregate all orders under one customer.
ALTER TABLE `pelanggan`
    ADD COLUMN `id_user` int DEFAULT NULL AFTER `id_pelanggan`,
    ADD KEY `idx_pelanggan_user` (`id_user`);

-- Legacy storefront checkout stored the buyer account ID in penjualan.id_kasir.
-- Backfill only when a customer snapshot is associated with exactly one buyer.
UPDATE `pelanggan` c
JOIN (
    SELECT s.`id_pelanggan`, MIN(s.`id_kasir`) AS `id_user`
    FROM `penjualan` s
    JOIN `user` u ON u.`id_user` = s.`id_kasir` AND u.`level` = 'user'
    WHERE s.`id_pelanggan` IS NOT NULL
    GROUP BY s.`id_pelanggan`
    HAVING COUNT(DISTINCT s.`id_kasir`) = 1
) linked ON linked.`id_pelanggan` = c.`id_pelanggan`
SET c.`id_user` = linked.`id_user`;
