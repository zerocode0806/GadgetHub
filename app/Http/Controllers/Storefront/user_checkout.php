<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
require_once APP_ROOT . '/app/Support/user_helpers.php';

// Tombol "Beli Sekarang" mengirim produk/jumlah langsung ke endpoint checkout.
// Intent disimpan di session agar bisa melewati login tanpa menambah/mengubah keranjang.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['buy_now'] ?? '') === '1') {
    user_verify_csrf();

    $buy_now_product_id = filter_input(INPUT_POST, 'id_produk', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    $buy_now_quantity = filter_input(INPUT_POST, 'jumlah', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if (!$buy_now_product_id || !$buy_now_quantity) {
        user_flash('error', 'Produk atau jumlah untuk pembelian langsung tidak valid.');
        user_redirect('/products');
    }

    $stock_stmt = mysqli_prepare($koneksi, 'SELECT stok FROM produk WHERE id_produk = ? LIMIT 1');
    mysqli_stmt_bind_param($stock_stmt, 'i', $buy_now_product_id);
    mysqli_stmt_execute($stock_stmt);
    $buy_now_product = mysqli_fetch_assoc(mysqli_stmt_get_result($stock_stmt));
    if (!$buy_now_product || (int) $buy_now_product['stok'] < 1 || $buy_now_quantity > (int) $buy_now_product['stok']) {
        user_flash('error', 'Stok produk tidak mencukupi untuk jumlah yang dipilih.');
        user_redirect('/product?id=' . $buy_now_product_id);
    }

    if (user_is_logged_in() && ($_SESSION['level'] ?? '') !== 'user') {
        user_flash('error', 'Silakan masuk menggunakan akun pelanggan untuk berbelanja.');
        user_redirect('/dashboard');
    }

    $_SESSION['checkout_buy_now'] = [
        'id_produk' => (int) $buy_now_product_id,
        'jumlah' => (int) $buy_now_quantity,
    ];

    if (!user_is_logged_in()) {
        user_redirect('/login');
    }
    user_redirect('/checkout/customer');
}

user_require_customer();
$id_user = (int) $_SESSION['id_user'];

$pending_buy_now = $_SESSION['checkout_buy_now'] ?? null;
$is_buy_now = is_array($pending_buy_now)
    && isset($pending_buy_now['id_produk'], $pending_buy_now['jumlah'])
    && (int) $pending_buy_now['id_produk'] > 0
    && (int) $pending_buy_now['jumlah'] > 0;

if ($is_buy_now) {
    $buy_now_product_id = (int) $pending_buy_now['id_produk'];
    $buy_now_quantity = (int) $pending_buy_now['jumlah'];
    $buy_now_stmt = mysqli_prepare($koneksi, 'SELECT id_produk, nama_produk, harga, stok FROM produk WHERE id_produk = ? LIMIT 1');
    mysqli_stmt_bind_param($buy_now_stmt, 'i', $buy_now_product_id);
    mysqli_stmt_execute($buy_now_stmt);
    $buy_now_product = mysqli_fetch_assoc(mysqli_stmt_get_result($buy_now_stmt));

    if (!$buy_now_product || $buy_now_quantity > (int) $buy_now_product['stok']) {
        unset($_SESSION['checkout_buy_now']);
        user_flash('error', 'Stok produk berubah atau produk tidak lagi tersedia. Silakan pilih produk kembali.');
        user_redirect('/products');
    }
    $cart = [$buy_now_product_id => $buy_now_quantity];
} else {
    unset($_SESSION['checkout_buy_now']);
    $cart = user_cart();
}

if (!$cart) {
    user_flash('error', 'Keranjang kamu masih kosong.');
    user_redirect('/cart');
}

$stmt = mysqli_prepare($koneksi, 'SELECT nama, username, no_telepon, alamat FROM user WHERE id_user = ? LIMIT 1');
mysqli_stmt_bind_param($stmt, 'i', $id_user);
mysqli_stmt_execute($stmt);
$customer = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

$items = [];
$total = 0;
foreach ($cart as $id => $quantity) {
    $id = (int) $id;
    $quantity = (int) $quantity;
    $product_stmt = mysqli_prepare($koneksi, 'SELECT id_produk, nama_produk, harga, stok FROM produk WHERE id_produk = ? LIMIT 1');
    mysqli_stmt_bind_param($product_stmt, 'i', $id);
    mysqli_stmt_execute($product_stmt);
    $product = mysqli_fetch_assoc(mysqli_stmt_get_result($product_stmt));
    if (!$product) {
        continue;
    }
    $product['jumlah'] = $quantity;
    $product['subtotal'] = (int) $product['harga'] * $quantity;
    $total += $product['subtotal'];
    $items[] = $product;
}

if (!$items) {
    if ($is_buy_now) {
        unset($_SESSION['checkout_buy_now']);
        user_flash('error', 'Produk tidak lagi tersedia.');
        user_redirect('/products');
    }
    user_cart_clear();
    user_redirect('/cart');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    user_verify_csrf();
    $nama = trim($_POST['nama'] ?? '');
    $telepon = trim($_POST['no_telepon'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $metode = $_POST['metode'] ?? 'Cash On Delivery (COD)';
    $metode_pembayaran_valid = ['Cash On Delivery (COD)', 'QRIS', 'Bank'];

    if ($nama === '' || $telepon === '' || $alamat === '' || !in_array($metode, $metode_pembayaran_valid, true)) {
        user_flash('error', 'Lengkapi data pengiriman dan metode pembayaran.');
        user_redirect('/checkout/customer');
    }

    mysqli_begin_transaction($koneksi);
    try {
        $profile_stmt = mysqli_prepare($koneksi, "UPDATE user SET nama = ?, no_telepon = ?, alamat = ? WHERE id_user = ? AND level = 'user'");
        mysqli_stmt_bind_param($profile_stmt, 'sssi', $nama, $telepon, $alamat, $id_user);
        if (!mysqli_stmt_execute($profile_stmt)) {
            throw new RuntimeException('Gagal memperbarui profil pelanggan.');
        }

        $customer_stmt = mysqli_prepare($koneksi, 'INSERT INTO pelanggan (id_user, nama_pelanggan, alamat, no_telepon) VALUES (?, ?, ?, ?)');
        mysqli_stmt_bind_param($customer_stmt, 'isss', $id_user, $nama, $alamat, $telepon);
        if (!mysqli_stmt_execute($customer_stmt)) {
            throw new RuntimeException('Gagal menyimpan data pelanggan.');
        }
        $id_pelanggan = mysqli_insert_id($koneksi);

        $sale_stmt = mysqli_prepare($koneksi, "INSERT INTO penjualan (tanggal_penjualan, id_kasir, total_harga, id_pelanggan, bayar, kembali, metode, status) VALUES (NOW(), ?, ?, ?, ?, 0, ?, 'Proses')");
        $paid = $total;
        mysqli_stmt_bind_param($sale_stmt, 'iiiis', $id_user, $total, $id_pelanggan, $paid, $metode);
        if (!mysqli_stmt_execute($sale_stmt)) {
            throw new RuntimeException('Gagal menyimpan pesanan.');
        }
        $id_penjualan = mysqli_insert_id($koneksi);

        foreach ($cart as $id => $quantity) {
            $id = (int) $id;
            $quantity = (int) $quantity;
            $lock_stmt = mysqli_prepare($koneksi, 'SELECT nama_produk, harga, stok FROM produk WHERE id_produk = ? FOR UPDATE');
            mysqli_stmt_bind_param($lock_stmt, 'i', $id);
            mysqli_stmt_execute($lock_stmt);
            $locked = mysqli_fetch_assoc(mysqli_stmt_get_result($lock_stmt));
            if (!$locked || $quantity < 1 || $quantity > (int) $locked['stok']) {
                throw new RuntimeException('Stok produk berubah. Silakan periksa kembali pesanan.');
            }

            $subtotal = (int) $locked['harga'] * $quantity;
            $detail_stmt = mysqli_prepare($koneksi, 'INSERT INTO detail_penjualan (id_penjualan, id_produk, jumlah_produk, sub_total) VALUES (?, ?, ?, ?)');
            mysqli_stmt_bind_param($detail_stmt, 'iiii', $id_penjualan, $id, $quantity, $subtotal);
            if (!mysqli_stmt_execute($detail_stmt)) {
                throw new RuntimeException('Gagal menyimpan detail pesanan.');
            }

            $stock_stmt = mysqli_prepare($koneksi, 'UPDATE produk SET stok = stok - ? WHERE id_produk = ? AND stok >= ?');
            mysqli_stmt_bind_param($stock_stmt, 'iii', $quantity, $id, $quantity);
            if (!mysqli_stmt_execute($stock_stmt) || mysqli_stmt_affected_rows($stock_stmt) !== 1) {
                throw new RuntimeException('Stok produk tidak mencukupi.');
            }
        }

        // Pembelian langsung tidak mengubah keranjang yang sudah ada.
        if (!$is_buy_now) {
            $clear_cart_stmt = mysqli_prepare($koneksi, 'DELETE FROM keranjang WHERE id_user = ?');
            mysqli_stmt_bind_param($clear_cart_stmt, 'i', $id_user);
            if (!mysqli_stmt_execute($clear_cart_stmt)) {
                throw new RuntimeException('Gagal mengosongkan keranjang setelah pesanan dibuat.');
            }
        }

        mysqli_commit($koneksi);
        if ($is_buy_now) {
            unset($_SESSION['checkout_buy_now']);
        } else {
            $_SESSION['cart'] = [];
        }
        $_SESSION['last_order_id'] = $id_penjualan;
        user_redirect('/orders/detail?id=' . $id_penjualan . '&success=1');
    } catch (Throwable $error) {
        mysqli_rollback($koneksi);
        user_flash('error', $error->getMessage());
        user_redirect('/checkout/customer');
    }
}

$page_title = $is_buy_now ? 'Checkout — Beli Sekarang' : 'Checkout';
$site_settings = user_site_settings();
include APP_ROOT . '/resources/views/storefront/header.php';
require APP_ROOT . '/resources/views/storefront/user_checkout.php';
