<?php
require_once APP_ROOT . '/app/Support/user_helpers.php';
user_require_customer();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') user_redirect('keranjang.php');
user_verify_csrf();
$is_ajax = user_is_ajax_request();
$id = filter_input(INPUT_POST, 'id_produk', FILTER_VALIDATE_INT);
$jumlah = filter_input(INPUT_POST, 'jumlah', FILTER_VALIDATE_INT);
if (!$id || $jumlah === false) {
	if ($is_ajax) { http_response_code(422); header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Jumlah produk tidak valid.']); exit; }
	user_redirect('keranjang.php');
}
if ($jumlah <= 0) {
	unset($_SESSION['cart'][$id]);
	if ($is_ajax) { header('Content-Type: application/json'); echo json_encode(['success' => true, 'removed' => true, 'cart_count' => user_cart_count(), 'message' => 'Produk dihapus dari keranjang.']); exit; }
	user_flash('success', 'Produk dihapus dari keranjang.'); user_redirect('keranjang.php');
}
$stmt = mysqli_prepare($koneksi, 'SELECT harga, stok FROM produk WHERE id_produk = ? LIMIT 1'); mysqli_stmt_bind_param($stmt, 'i', $id); mysqli_stmt_execute($stmt); $product = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$product || $jumlah > (int) $product['stok']) {
	if ($is_ajax) { http_response_code(409); header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Jumlah melebihi stok terbaru.', 'max' => (int) ($product['stok'] ?? 0)]); exit; }
	user_flash('error', 'Jumlah melebihi stok terbaru.'); user_redirect('keranjang.php');
}
$_SESSION['cart'][$id] = $jumlah;
if ($is_ajax) {
	$server_total = 0;
	foreach ($_SESSION['cart'] as $cart_id => $cart_quantity) {
		$total_stmt = mysqli_prepare($koneksi, 'SELECT harga FROM produk WHERE id_produk = ? LIMIT 1');
		$cart_id = (int) $cart_id;
		mysqli_stmt_bind_param($total_stmt, 'i', $cart_id);
		mysqli_stmt_execute($total_stmt);
		$cart_product = mysqli_fetch_assoc(mysqli_stmt_get_result($total_stmt));
		if ($cart_product) $server_total += (int) $cart_product['harga'] * (int) $cart_quantity;
	}
	header('Content-Type: application/json');
	echo json_encode(['success' => true, 'quantity' => $jumlah, 'subtotal' => (int) $product['harga'] * $jumlah, 'total' => $server_total, 'cart_count' => user_cart_count(), 'message' => 'Jumlah keranjang diperbarui.']);
	exit;
}
user_flash('success', 'Keranjang diperbarui.');
user_redirect('keranjang.php');
