<?php
require_once APP_ROOT . '/app/Support/koneksi.php';


// Periksa apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: /login");
    exit;
}

// Ambil data pengaturan bisnis
$query = "SELECT business_name FROM settings WHERE id = 1";
$result = mysqli_query($koneksi, $query);
$settings = mysqli_fetch_assoc($result);

// Cek level user yang login
$level = $_SESSION['level']; // Bisa 'admin' atau 'petugas'

if ($level === 'user') {
    header('Location: /shop');
    exit;
}
$page = $_GET['page'] ?? 'home';
$adminPages = ['home', 'user', 'user_tambah', 'user_ubah', 'user_hapus', 'pelanggan', 'pelanggan_detail', 'pelanggan_tambah', 'pelanggan_ubah', 'pelanggan_hapus', 'produk', 'produk_tambah', 'produk_ubah', 'produk_hapus', 'settings', 'pembelian', 'pembelian_tambah', 'penjualan_detail', 'penjualan_hapus', 'penjualan_status_update', 'checkout'];
$page = in_array($page, $adminPages, true) ? $page : 'home';
$pageController = $page === 'checkout'
    ? APP_ROOT . '/app/Http/Controllers/storefront/checkout.php'
    : APP_ROOT . '/app/Http/Controllers/admin/' . $page . '.php';
ob_start();
if (is_file($pageController)) {
    require $pageController;
}
$page_content = ob_get_clean();
require APP_ROOT . '/resources/views/admin/dashboard.php';
