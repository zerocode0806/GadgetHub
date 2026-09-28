<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
require_once APP_ROOT . '/app/Support/user_helpers.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) user_redirect('/products');
$stmt = mysqli_prepare($koneksi, 'SELECT * FROM produk WHERE id_produk = ? LIMIT 1');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$product = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$product) { http_response_code(404); exit('Produk tidak ditemukan.'); }
$ratingStmt = mysqli_prepare($koneksi, 'SELECT COUNT(*) AS total, COALESCE(AVG(rating), 0) AS average FROM ulasan_produk WHERE id_produk = ?');
mysqli_stmt_bind_param($ratingStmt, 'i', $id);
mysqli_stmt_execute($ratingStmt);
$ratingStats = mysqli_fetch_assoc(mysqli_stmt_get_result($ratingStmt)) ?: ['total' => 0, 'average' => 0];
$reviewStmt = mysqli_prepare($koneksi, 'SELECT r.id_ulasan, r.id_user, r.rating, r.ulasan, r.created_at, u.nama, u.username FROM ulasan_produk r LEFT JOIN user u ON u.id_user = r.id_user WHERE r.id_produk = ? ORDER BY r.created_at DESC, r.id_ulasan DESC LIMIT 30');
mysqli_stmt_bind_param($reviewStmt, 'i', $id);
mysqli_stmt_execute($reviewStmt);
$reviews = mysqli_stmt_get_result($reviewStmt);
$userReview = null;
if (isset($_SESSION['id_user']) && ($_SESSION['level'] ?? '') === 'user') {
    $userId = (int) $_SESSION['id_user'];
    $myReviewStmt = mysqli_prepare($koneksi, 'SELECT rating, ulasan FROM ulasan_produk WHERE id_produk = ? AND id_user = ? LIMIT 1');
    mysqli_stmt_bind_param($myReviewStmt, 'ii', $id, $userId);
    mysqli_stmt_execute($myReviewStmt);
    $userReview = mysqli_fetch_assoc(mysqli_stmt_get_result($myReviewStmt)) ?: null;
}
$page_title = $product['nama_produk'];
$active_page = 'produk';
$site_settings = user_site_settings();
include APP_ROOT . '/resources/views/storefront/header.php';
require APP_ROOT . '/resources/views/storefront/user_produk_detail.php';
