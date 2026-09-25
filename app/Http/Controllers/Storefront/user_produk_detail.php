<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
require_once APP_ROOT . '/app/Support/user_helpers.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) user_redirect('/products');
$stmt = mysqli_prepare($koneksi, "SELECT * FROM produk WHERE id_produk = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$product = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$product) { http_response_code(404); exit('Produk tidak ditemukan.'); }
$page_title = $product['nama_produk'];
$active_page = 'produk';
$site_settings = user_site_settings();
include APP_ROOT . '/resources/views/storefront/header.php';
require APP_ROOT . '/resources/views/storefront/user_produk_detail.php';
