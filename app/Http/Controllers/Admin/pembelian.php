<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
require_once APP_ROOT . '/app/Support/admin_helpers.php';
admin_require_roles(['admin', 'petugas']);

$id_user = (int) $_SESSION['id_user'];
$level = $_SESSION['level'];
$admin_csrf = admin_csrf_token();
$admin_flash = $_SESSION['admin_flash'] ?? null;
unset($_SESSION['admin_flash']);
$search = trim($_GET['search'] ?? '');
$page = filter_input(INPUT_GET, 'halaman', FILTER_VALIDATE_INT);
$halamanAktif = max(1, (int) ($page ?: 1));
$jumlahDataPerhalaman = 10;
$awalData = ($halamanAktif - 1) * $jumlahDataPerhalaman;

$where = '';
if ($search !== '') {
    $safeSearch = mysqli_real_escape_string($koneksi, $search);
    $where = " WHERE (CAST(s.id_penjualan AS CHAR) LIKE '%$safeSearch%' OR s.tanggal_penjualan LIKE '%$safeSearch%' OR buyer.nama LIKE '%$safeSearch%' OR c.nama_pelanggan LIKE '%$safeSearch%' OR s.metode LIKE '%$safeSearch%' OR s.status LIKE '%$safeSearch%')";
}
$from = ' FROM penjualan s LEFT JOIN user buyer ON buyer.id_user = s.id_kasir LEFT JOIN pelanggan c ON c.id_pelanggan = s.id_pelanggan';
$countResult = mysqli_query($koneksi, 'SELECT COUNT(*) AS total' . $from . $where);
$totalData = (int) (mysqli_fetch_assoc($countResult)['total'] ?? 0);
$jumlahHalaman = max(1, (int) ceil($totalData / $jumlahDataPerhalaman));
$halamanAktif = min($halamanAktif, $jumlahHalaman);
$awalData = ($halamanAktif - 1) * $jumlahDataPerhalaman;
$query = mysqli_query($koneksi, 'SELECT s.*, buyer.nama AS nama_kasir, buyer.level AS level_pembuat, c.nama_pelanggan' . $from . $where . ' ORDER BY s.id_penjualan DESC, s.tanggal_penjualan DESC LIMIT ' . (int) $awalData . ', ' . (int) $jumlahDataPerhalaman);
require APP_ROOT . '/resources/views/admin/pembelian.php';
