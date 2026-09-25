<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
require_once APP_ROOT . '/app/Support/user_helpers.php';
user_require_customer();
$page_title = 'Pesanan saya'; $active_page = 'pesanan'; $id_user = (int) $_SESSION['id_user'];
$stmt = mysqli_prepare($koneksi, 'SELECT p.id_penjualan, p.tanggal_penjualan, p.total_harga, p.metode, p.status, pl.nama_pelanggan FROM penjualan p LEFT JOIN pelanggan pl ON pl.id_pelanggan = p.id_pelanggan WHERE p.id_kasir = ? ORDER BY p.id_penjualan DESC'); mysqli_stmt_bind_param($stmt, 'i', $id_user); mysqli_stmt_execute($stmt); $orders = mysqli_stmt_get_result($stmt);
$site_settings = user_site_settings();
include APP_ROOT . '/resources/views/storefront/header.php';
require APP_ROOT . '/resources/views/storefront/pesanan.php';
