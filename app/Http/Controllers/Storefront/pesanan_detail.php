<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
require_once APP_ROOT . '/app/Support/user_helpers.php';
user_require_customer();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT); if (!$id) user_redirect('/orders'); $id_user = (int) $_SESSION['id_user'];
$stmt = mysqli_prepare($koneksi, 'SELECT p.*, pl.nama_pelanggan, pl.alamat, pl.no_telepon FROM penjualan p LEFT JOIN pelanggan pl ON pl.id_pelanggan = p.id_pelanggan WHERE p.id_penjualan = ? AND p.id_kasir = ? LIMIT 1'); mysqli_stmt_bind_param($stmt, 'ii', $id, $id_user); mysqli_stmt_execute($stmt); $order = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$order) { http_response_code(404); exit('Pesanan tidak ditemukan.'); }
$detail_stmt = mysqli_prepare($koneksi, 'SELECT d.jumlah_produk, d.sub_total, p.nama_produk FROM detail_penjualan d LEFT JOIN produk p ON p.id_produk = d.id_produk WHERE d.id_penjualan = ?'); mysqli_stmt_bind_param($detail_stmt, 'i', $id); mysqli_stmt_execute($detail_stmt); $details = mysqli_stmt_get_result($detail_stmt); $page_title = 'Detail pesanan #' . $id; $active_page = 'pesanan'; $site_settings = user_site_settings();
include APP_ROOT . '/resources/views/storefront/header.php';
require APP_ROOT . '/resources/views/storefront/pesanan_detail.php';
