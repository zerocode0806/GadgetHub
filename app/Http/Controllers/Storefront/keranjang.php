<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
require_once APP_ROOT . '/app/Support/user_helpers.php';
user_require_customer();
$page_title = 'Keranjang';
$cart = user_cart();
$items = [];
$total = 0;
foreach ($cart as $id => $quantity) {
    $id = (int) $id; $quantity = (int) $quantity;
    $stmt = mysqli_prepare($koneksi, 'SELECT id_produk, nama_produk, harga, stok, gambar_produk FROM produk WHERE id_produk = ? LIMIT 1'); mysqli_stmt_bind_param($stmt, 'i', $id); mysqli_stmt_execute($stmt); $product = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    if (!$product) { unset($_SESSION['cart'][$id]); continue; }
    $subtotal = (int) $product['harga'] * $quantity; $total += $subtotal; $product['jumlah'] = $quantity; $product['subtotal'] = $subtotal; $items[] = $product;
}
$site_settings = user_site_settings();
include APP_ROOT . '/resources/views/storefront/header.php';
require APP_ROOT . '/resources/views/storefront/keranjang.php';
