<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
require_once APP_ROOT . '/app/Support/user_helpers.php';
user_require_customer();
$cart = user_cart();
if (!$cart) { user_flash('error', 'Keranjang kamu masih kosong.'); user_redirect('/cart'); }
$id_user = (int) $_SESSION['id_user'];
$stmt = mysqli_prepare($koneksi, 'SELECT nama, username, no_telepon, alamat FROM user WHERE id_user = ? LIMIT 1'); mysqli_stmt_bind_param($stmt, 'i', $id_user); mysqli_stmt_execute($stmt); $customer = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
$items = []; $total = 0;
foreach ($cart as $id => $quantity) { $id = (int) $id; $quantity = (int) $quantity; $product_stmt = mysqli_prepare($koneksi, 'SELECT id_produk, nama_produk, harga, stok FROM produk WHERE id_produk = ? LIMIT 1'); mysqli_stmt_bind_param($product_stmt, 'i', $id); mysqli_stmt_execute($product_stmt); $product = mysqli_fetch_assoc(mysqli_stmt_get_result($product_stmt)); if (!$product) continue; $product['jumlah'] = $quantity; $product['subtotal'] = (int) $product['harga'] * $quantity; $total += $product['subtotal']; $items[] = $product; }
if (!$items) { $_SESSION['cart'] = []; user_redirect('/cart'); }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    user_verify_csrf();
    $nama = trim($_POST['nama'] ?? ''); $telepon = trim($_POST['no_telepon'] ?? ''); $alamat = trim($_POST['alamat'] ?? ''); $metode = $_POST['metode'] ?? 'Cash';
    if ($nama === '' || $telepon === '' || $alamat === '' || !in_array($metode, ['Cash', 'Transfer', 'E-Wallet'], true)) { user_flash('error', 'Lengkapi data pengiriman dan metode pembayaran.'); user_redirect('/checkout/customer'); }
    mysqli_begin_transaction($koneksi);
    try {
        $customer_stmt = mysqli_prepare($koneksi, 'INSERT INTO pelanggan (nama_pelanggan, alamat, no_telepon) VALUES (?, ?, ?)'); mysqli_stmt_bind_param($customer_stmt, 'sss', $nama, $alamat, $telepon); if (!mysqli_stmt_execute($customer_stmt)) throw new RuntimeException('Gagal menyimpan data pelanggan.'); $id_pelanggan = mysqli_insert_id($koneksi);
        $sale_stmt = mysqli_prepare($koneksi, "INSERT INTO penjualan (tanggal_penjualan, id_kasir, total_harga, id_pelanggan, bayar, kembali, metode, status) VALUES (NOW(), ?, ?, ?, ?, 0, ?, 'Proses')"); $paid = $total; mysqli_stmt_bind_param($sale_stmt, 'iiiis', $id_user, $total, $id_pelanggan, $paid, $metode); if (!mysqli_stmt_execute($sale_stmt)) throw new RuntimeException('Gagal menyimpan pesanan.'); $id_penjualan = mysqli_insert_id($koneksi);
        foreach ($cart as $id => $quantity) {
            $id = (int) $id; $quantity = (int) $quantity; $lock_stmt = mysqli_prepare($koneksi, 'SELECT nama_produk, harga, stok FROM produk WHERE id_produk = ? FOR UPDATE'); mysqli_stmt_bind_param($lock_stmt, 'i', $id); mysqli_stmt_execute($lock_stmt); $locked = mysqli_fetch_assoc(mysqli_stmt_get_result($lock_stmt)); if (!$locked || $quantity < 1 || $quantity > (int) $locked['stok']) throw new RuntimeException('Stok produk berubah. Silakan periksa keranjang.');
            $subtotal = (int) $locked['harga'] * $quantity; $detail_stmt = mysqli_prepare($koneksi, 'INSERT INTO detail_penjualan (id_penjualan, id_produk, jumlah_produk, sub_total) VALUES (?, ?, ?, ?)'); mysqli_stmt_bind_param($detail_stmt, 'iiii', $id_penjualan, $id, $quantity, $subtotal); if (!mysqli_stmt_execute($detail_stmt)) throw new RuntimeException('Gagal menyimpan detail pesanan.');
            $stock_stmt = mysqli_prepare($koneksi, 'UPDATE produk SET stok = stok - ? WHERE id_produk = ? AND stok >= ?'); mysqli_stmt_bind_param($stock_stmt, 'iii', $quantity, $id, $quantity); if (!mysqli_stmt_execute($stock_stmt) || mysqli_stmt_affected_rows($stock_stmt) !== 1) throw new RuntimeException('Stok produk tidak mencukupi.');
        }
        mysqli_commit($koneksi); $_SESSION['cart'] = []; $_SESSION['last_order_id'] = $id_penjualan; user_redirect('/orders/detail?id=' . $id_penjualan . '&success=1');
    } catch (Throwable $error) { mysqli_rollback($koneksi); user_flash('error', $error->getMessage()); user_redirect('/checkout/customer'); }
}
$page_title = 'Checkout';
$site_settings = user_site_settings();
include APP_ROOT . '/resources/views/storefront/header.php';
require APP_ROOT . '/resources/views/storefront/user_checkout.php';
