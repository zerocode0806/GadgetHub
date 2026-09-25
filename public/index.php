<?php
require_once dirname(__DIR__) . '/app/Support/bootstrap.php';
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$path = '/' . trim(rawurldecode($path), '/');
if ($path === '//') $path = '/';
if (PHP_SAPI === 'cli-server' && $path !== '/' && $path !== '/index.php' && is_file(PUBLIC_PATH . $path)) {
    return false;
}
$routes = require APP_ROOT . '/routes/web.php';
$adminPages = ['home', 'user', 'user_tambah', 'user_ubah', 'user_hapus', 'pelanggan', 'pelanggan_detail', 'pelanggan_tambah', 'pelanggan_ubah', 'pelanggan_hapus', 'produk', 'produk_tambah', 'produk_ubah', 'produk_hapus', 'settings', 'pembelian', 'pembelian_tambah', 'penjualan_detail', 'penjualan_hapus', 'penjualan_status_update'];
$legacy = basename($path);
if (str_ends_with(strtolower($legacy), '.php')) {
    $legacyName = substr($legacy, 0, -4);
    if (in_array($legacyName, $adminPages, true)) {
        $_GET['page'] = $legacyName;
        $path = '/dashboard';
    } else {
        $legacyRoutes = [
            'index' => '/', 'login' => '/login', 'register' => '/register', 'logout' => '/logout',
            'dashboard' => '/dashboard', 'cetak_struk' => '/receipt', 'struk_makan' => '/receipt/print',
            'download_data_pemb_excel' => '/admin/export', 'search_produk' => '/api/products/search',
            'user_home' => '/shop', 'user_produk' => '/products', 'user_produk_detail' => '/product',
            'keranjang' => '/cart', 'keranjang_tambah' => '/cart/add', 'keranjang_ubah' => '/cart/update',
            'keranjang_hapus' => '/cart/remove', 'checkout' => '/checkout', 'user_checkout' => '/checkout/customer',
            'pesanan' => '/orders', 'pesanan_detail' => '/orders/detail', 'profile' => '/profile',
            'penjualan_status_update' => '/admin/sales/status', 'penjualan_hapus' => '/admin/sales/delete',
            'pelanggan_hapus' => '/admin/customers/delete', 'produk_hapus' => '/admin/products/delete',
            'user_hapus' => '/admin/users/delete', 'kasir' => '/admin/users', 'kasir_tambah' => '/admin/users',
            'kasir_ubah' => '/admin/users', 'kasir_hapus' => '/admin/users',
            'cart_hapus' => '/admin/cart/clear',
        ];
        $path = $legacyRoutes[$legacyName] ?? '/';
    }
}
if (isset($_GET['page']) && $path === '/') {
    // Legacy ?page= URLs from old bookmarks continue to open inside the admin shell.
    $path = '/dashboard';
}
$target = $routes[$path] ?? null;
if (!$target) {
    http_response_code(404);
    $title = 'Halaman tidak ditemukan';
    require APP_ROOT . '/resources/views/errors/404.php';
    exit;
}
if (is_array($target) && isset($target['view'])) {
    $view = APP_ROOT . '/resources/views/' . $target['view'];
    if (!is_file($view)) { http_response_code(500); exit('View route tidak ditemukan.'); }
    require $view;
    exit;
}
$page = is_array($target) ? ($target['page'] ?? null) : null;
if ($page !== null) {
    $_GET['page'] = $page;
    $target = $target['controller'];
}
$controller = APP_ROOT . '/app/Http/Controllers/' . $target;
if (!is_file($controller)) { http_response_code(500); exit('Controller route tidak ditemukan.'); }
require $controller;
