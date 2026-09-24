<?php
require_once APP_ROOT . '/app/Support/user_helpers.php';
user_require_customer();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') user_redirect('keranjang.php');
user_verify_csrf();
$id = filter_input(INPUT_POST, 'id_produk', FILTER_VALIDATE_INT);
if ($id) unset($_SESSION['cart'][$id]);
user_flash('success', 'Produk dihapus dari keranjang.');
user_redirect('keranjang.php');
