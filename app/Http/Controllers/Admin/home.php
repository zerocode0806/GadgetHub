<?php
require_once APP_ROOT . '/app/Support/koneksi.php';

$settingsResult = mysqli_query($koneksi, 'SELECT * FROM settings WHERE id = 1 LIMIT 1');
$settings = $settingsResult ? (mysqli_fetch_assoc($settingsResult) ?: []) : [];
$totalSalesResult = mysqli_query($koneksi, 'SELECT COALESCE(SUM(total_harga), 0) AS total_sales FROM penjualan');
$total_sales = $totalSalesResult ? (mysqli_fetch_assoc($totalSalesResult)['total_sales'] ?? 0) : 0;
$customer_count = (int) (mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM user WHERE level = 'user'"))['total'] ?? 0);
$product_count = (int) (mysqli_fetch_assoc(mysqli_query($koneksi, 'SELECT COUNT(*) AS total FROM produk'))['total'] ?? 0);
$sales_count = (int) (mysqli_fetch_assoc(mysqli_query($koneksi, 'SELECT COUNT(*) AS total FROM penjualan'))['total'] ?? 0);
$is_admin = ($_SESSION['level'] ?? '') === 'admin';
require APP_ROOT . '/resources/views/admin/home.php';
