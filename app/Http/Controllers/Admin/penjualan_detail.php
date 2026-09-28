<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
require_once APP_ROOT . '/app/Support/admin_helpers.php';
admin_require_roles(['admin', 'petugas']);

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    http_response_code(404);
    exit('Pesanan tidak ditemukan.');
}
$order_stmt = mysqli_prepare($koneksi, 'SELECT s.*, buyer.nama AS nama_kasir, buyer.level AS level_pembuat, c.nama_pelanggan, c.alamat AS alamat_pelanggan, c.no_telepon AS telepon_pelanggan FROM penjualan s LEFT JOIN user buyer ON buyer.id_user = s.id_kasir LEFT JOIN pelanggan c ON c.id_pelanggan = s.id_pelanggan WHERE s.id_penjualan = ? LIMIT 1');
mysqli_stmt_bind_param($order_stmt, 'i', $id);
mysqli_stmt_execute($order_stmt);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($order_stmt));
if (!$data) {
    http_response_code(404);
    exit('Pesanan tidak ditemukan.');
}
$product_stmt = mysqli_prepare($koneksi, 'SELECT d.*, p.nama_produk FROM detail_penjualan d LEFT JOIN produk p ON p.id_produk = d.id_produk WHERE d.id_penjualan = ?');
mysqli_stmt_bind_param($product_stmt, 'i', $id);
mysqli_stmt_execute($product_stmt);
$pro = mysqli_stmt_get_result($product_stmt);
require APP_ROOT . '/resources/views/admin/penjualan_detail.php';
