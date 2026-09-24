<?php
require_once APP_ROOT . '/app/Support/user_helpers.php';
user_require_customer();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') user_redirect('user_produk.php');
user_verify_csrf();
$is_ajax = user_is_ajax_request();
$id = filter_input(INPUT_POST, 'id_produk', FILTER_VALIDATE_INT);
$jumlah = filter_input(INPUT_POST, 'jumlah', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if (!$id || !$jumlah) {
	if ($is_ajax) { http_response_code(422); header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Jumlah produk tidak valid.']); exit; }
	user_flash('error', 'Jumlah produk tidak valid.'); user_redirect('user_produk.php');
}
$stmt = mysqli_prepare($koneksi, 'SELECT stok FROM produk WHERE id_produk = ? LIMIT 1'); mysqli_stmt_bind_param($stmt, 'i', $id); mysqli_stmt_execute($stmt); $product = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$product || (int) $product['stok'] < 1) {
	if ($is_ajax) { http_response_code(409); header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Produk sedang tidak tersedia.']); exit; }
	user_flash('error', 'Produk sedang tidak tersedia.'); user_redirect('user_produk.php');
}
$current = (int) ($_SESSION['cart'][$id] ?? 0); $new_quantity = $current + $jumlah;
if ($new_quantity > (int) $product['stok']) {
	if ($is_ajax) { http_response_code(409); header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Jumlah melebihi stok yang tersedia.']); exit; }
	user_flash('error', 'Jumlah melebihi stok yang tersedia.'); user_redirect('user_produk_detail.php?id=' . $id);
}
$_SESSION['cart'][$id] = $new_quantity;
if ($is_ajax) {
	header('Content-Type: application/json');
	echo json_encode(['success' => true, 'message' => $jumlah . ' produk berhasil ditambahkan ke keranjang.', 'cart_count' => user_cart_count()]);
	exit;
}
user_flash('success', 'Produk ditambahkan ke keranjang.');
if (isset($_POST['buy_now']) && $_POST['buy_now'] === '1') user_redirect('keranjang.php');
user_redirect('user_produk.php');
