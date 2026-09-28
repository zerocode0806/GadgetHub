-- Enable the demo storefront payment methods while keeping legacy values valid
-- for historical orders. Only COD, QRIS, and Bank are offered at checkout.
ALTER TABLE `penjualan`
    MODIFY `metode` enum('Cash On Delivery (COD)','QRIS','Bank','Cash','Transfer','E-Wallet')
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL;

-- Re-label historical cash-on-delivery orders with the new customer-facing name.
UPDATE `penjualan`
SET `metode` = 'Cash On Delivery (COD)'
WHERE `metode` = 'Cash';
