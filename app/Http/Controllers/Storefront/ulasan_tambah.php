<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
require_once APP_ROOT . '/app/Support/user_helpers.php';
user_require_customer();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') user_redirect('/products');
user_verify_csrf();

$idProduk = filter_input(INPUT_POST, 'id_produk', FILTER_VALIDATE_INT);
$rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);
$ulasan = trim((string) ($_POST['ulasan'] ?? ''));
$reviewLength = function_exists('mb_strlen') ? mb_strlen($ulasan) : strlen($ulasan);
if (!$idProduk || !$rating || $rating < 1 || $rating > 5 || $ulasan === '' || $reviewLength > 1500) {
    user_flash('error', 'Isi rating 1–5 dan ulasan maksimal 1.500 karakter.');
    user_redirect($idProduk ? '/product?id=' . $idProduk : '/products');
}

$productStmt = mysqli_prepare($koneksi, 'SELECT id_produk FROM produk WHERE id_produk = ? LIMIT 1');
mysqli_stmt_bind_param($productStmt, 'i', $idProduk);
mysqli_stmt_execute($productStmt);
if (!mysqli_fetch_assoc(mysqli_stmt_get_result($productStmt))) user_redirect('/products');

$idUser = (int) $_SESSION['id_user'];
$stmt = mysqli_prepare($koneksi, 'INSERT INTO ulasan_produk (id_produk, id_user, rating, ulasan) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE rating = VALUES(rating), ulasan = VALUES(ulasan), updated_at = CURRENT_TIMESTAMP');
mysqli_stmt_bind_param($stmt, 'iiis', $idProduk, $idUser, $rating, $ulasan);
if (mysqli_stmt_execute($stmt)) {
    user_flash('success', 'Terima kasih, ulasan produk berhasil disimpan.');
} else {
    user_flash('error', 'Ulasan belum dapat disimpan. Silakan coba kembali.');
}
user_redirect('/product?id=' . $idProduk);
