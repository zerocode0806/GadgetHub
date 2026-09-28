<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
require_once APP_ROOT . '/app/Support/admin_helpers.php';

admin_require_roles(['admin', 'petugas']);

$level = $_SESSION['level'];
$settingsResult = mysqli_query($koneksi, 'SELECT business_name FROM settings WHERE id = 1 LIMIT 1');
$settings = $settingsResult ? (mysqli_fetch_assoc($settingsResult) ?: []) : [];

$page = $_GET['page'] ?? 'home';
$allAdminPages = [
    'home',
    'user', 'user_tambah', 'user_ubah',
    'pelanggan', 'pelanggan_detail', 'pelanggan_tambah', 'pelanggan_ubah',
    'produk', 'produk_tambah', 'produk_ubah', 'kategori', 'settings',
    'pembelian', 'penjualan_detail',
];
$petugasPages = ['home', 'pembelian', 'penjualan_detail'];
$allowedPages = $level === 'admin' ? $allAdminPages : $petugasPages;

if (!in_array($page, $allowedPages, true)) {
    http_response_code(403);
    exit('Akses ditolak. Halaman ini hanya tersedia untuk admin.');
}

$pageController = APP_ROOT . '/app/Http/Controllers/admin/' . $page . '.php';

ob_start();
if (is_file($pageController)) {
    require $pageController;
} else {
    http_response_code(404);
    echo 'Halaman tidak ditemukan.';
}
$page_content = ob_get_clean();

require APP_ROOT . '/resources/views/admin/dashboard.php';
