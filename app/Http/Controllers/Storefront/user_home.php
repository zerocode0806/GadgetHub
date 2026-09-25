<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
require_once APP_ROOT . '/app/Support/user_helpers.php';
$page_title = 'Belanja mudah';
$active_page = 'home';
$products = mysqli_query($koneksi, "SELECT id_produk, nama_produk, harga, stok, gambar_produk FROM produk ORDER BY id_produk DESC LIMIT 8");
$site_settings = user_site_settings();
include APP_ROOT . '/resources/views/storefront/header.php';
require APP_ROOT . '/resources/views/storefront/user_home.php';
