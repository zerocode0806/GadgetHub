<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
require_once APP_ROOT . '/app/Support/user_helpers.php';
$page_title = 'Produk';
$active_page = 'produk';
$search = trim((string) ($_GET['q'] ?? ''));
$categoryFilter = trim((string) ($_GET['kategori'] ?? ''));
$sort = $_GET['sort'] ?? 'terbaru';
$allowed_sorts = ['termurah' => 'harga ASC', 'termahal' => 'harga DESC', 'terbaru' => 'id_produk DESC'];
$order_by = $allowed_sorts[$sort] ?? $allowed_sorts['terbaru'];
$page = max(1, (int) ($_GET['halaman'] ?? 1));
$per_page = 12;
$offset = ($page - 1) * $per_page;
$where = "WHERE (? = '' OR nama_produk LIKE ? OR deskripsi_produk LIKE ? OR kategori_produk LIKE ? OR spesifikasi_produk LIKE ?) AND (? = '' OR kategori_produk = ?)";
$like = '%' . $search . '%';
$count_stmt = mysqli_prepare($koneksi, "SELECT COUNT(*) AS total FROM produk $where");
mysqli_stmt_bind_param($count_stmt, 'sssssss', $search, $like, $like, $like, $like, $categoryFilter, $categoryFilter);
mysqli_stmt_execute($count_stmt);
$total = (int) (mysqli_fetch_assoc(mysqli_stmt_get_result($count_stmt))['total'] ?? 0);
$stmt = mysqli_prepare($koneksi, "SELECT id_produk, nama_produk, kategori_produk, deskripsi_produk, spesifikasi_produk, harga, stok, gambar_produk FROM produk $where ORDER BY $order_by LIMIT ?, ?");
mysqli_stmt_bind_param($stmt, 'sssssssii', $search, $like, $like, $like, $like, $categoryFilter, $categoryFilter, $offset, $per_page);
mysqli_stmt_execute($stmt);
$products = mysqli_stmt_get_result($stmt);
$pages = (int) ceil($total / $per_page);
$categories = [];
$categoryResult = mysqli_query($koneksi, "SELECT nama_kategori FROM kategori ORDER BY CASE nama_kategori WHEN 'Smartphone' THEN 1 WHEN 'Audio' THEN 2 WHEN 'Wearable' THEN 3 WHEN 'Kamera & Video' THEN 4 ELSE 99 END, nama_kategori ASC");
if ($categoryResult) {
    while ($categoryRow = mysqli_fetch_assoc($categoryResult)) $categories[] = $categoryRow['nama_kategori'];
}
$site_settings = user_site_settings();
include APP_ROOT . '/resources/views/storefront/header.php';
require APP_ROOT . '/resources/views/storefront/user_produk.php';
