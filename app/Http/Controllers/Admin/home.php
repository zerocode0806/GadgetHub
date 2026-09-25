<?php
require_once APP_ROOT . '/app/Support/koneksi.php';

// Fetch the business settings from the database
$query = "SELECT * FROM settings WHERE id = 1";
$result = mysqli_query($koneksi, $query);
$settings = mysqli_fetch_assoc($result);

// Get total sales amount
$total_sales_query = "SELECT SUM(total_harga) as total_sales FROM penjualan";
$total_sales_result = mysqli_query($koneksi, $total_sales_query);
$total_sales = mysqli_fetch_assoc($total_sales_result)['total_sales'];
$customer_count = (int) (mysqli_fetch_assoc(mysqli_query($koneksi, 'SELECT COUNT(*) AS total FROM pelanggan'))['total'] ?? 0);
$product_count = (int) (mysqli_fetch_assoc(mysqli_query($koneksi, 'SELECT COUNT(*) AS total FROM produk'))['total'] ?? 0);
$sales_count = (int) (mysqli_fetch_assoc(mysqli_query($koneksi, 'SELECT COUNT(*) AS total FROM penjualan'))['total'] ?? 0);
require APP_ROOT . '/resources/views/admin/home.php';
