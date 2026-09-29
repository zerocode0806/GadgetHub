<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
require_once APP_ROOT . '/app/Support/user_helpers.php';
user_require_customer();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') user_redirect('/cart');
user_verify_csrf();
$id = filter_input(INPUT_POST, 'id_produk', FILTER_VALIDATE_INT);
if ($id && user_cart_set_quantity($id, 0)) {
    user_flash('success', 'Produk dihapus dari keranjang.');
} else {
    user_flash('error', 'Produk gagal dihapus dari keranjang.');
}
user_redirect('/cart');
