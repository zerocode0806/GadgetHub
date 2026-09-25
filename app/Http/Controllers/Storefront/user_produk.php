<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
require_once APP_ROOT . '/app/Support/user_helpers.php';
$page_title = 'Produk';
$active_page = 'produk';
$search = trim($_GET['q'] ?? '');
$sort = $_GET['sort'] ?? 'terbaru';
$allowed_sorts = ['termurah' => 'harga ASC', 'termahal' => 'harga DESC', 'terbaru' => 'id_produk DESC'];
$order_by = $allowed_sorts[$sort] ?? $allowed_sorts['terbaru'];
$page = max(1, (int) ($_GET['halaman'] ?? 1));
$per_page = 12;
$offset = ($page - 1) * $per_page;
$where = '';
if ($search !== '') $where = 'WHERE nama_produk LIKE ? OR deskripsi_produk LIKE ?';
$count_stmt = mysqli_prepare($koneksi, "SELECT COUNT(*) AS total FROM produk $where");
if ($search !== '') { $like = '%' . $search . '%'; mysqli_stmt_bind_param($count_stmt, 'ss', $like, $like); }
mysqli_stmt_execute($count_stmt);
$total = (int) mysqli_fetch_assoc(mysqli_stmt_get_result($count_stmt))['total'];
$stmt = mysqli_prepare($koneksi, "SELECT id_produk, nama_produk, deskripsi_produk, harga, stok, gambar_produk FROM produk $where ORDER BY $order_by LIMIT ?, ?");
if ($search !== '') mysqli_stmt_bind_param($stmt, 'ssii', $like, $like, $offset, $per_page); else mysqli_stmt_bind_param($stmt, 'ii', $offset, $per_page);
mysqli_stmt_execute($stmt);
$products = mysqli_stmt_get_result($stmt);
$pages = (int) ceil($total / $per_page);
$site_settings = user_site_settings();
include APP_ROOT . '/resources/views/storefront/header.php';
require APP_ROOT . '/resources/views/storefront/user_produk.php';
