<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
require_once APP_ROOT . '/app/Support/admin_helpers.php';
admin_require_roles(['admin']);

$customer_type = ($_GET['type'] ?? 'account') === 'guest' ? 'guest' : 'account';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    admin_set_flash('error', 'Pelanggan tidak ditemukan.');
    admin_redirect('/admin/customers');
}

if ($customer_type === 'account') {
    $customer_stmt = mysqli_prepare($koneksi, "SELECT id_user AS customer_id, nama, username, no_telepon, alamat FROM user WHERE id_user = ? AND level = 'user' LIMIT 1");
} else {
    $customer_stmt = mysqli_prepare($koneksi, 'SELECT id_pelanggan AS customer_id, nama_pelanggan AS nama, NULL AS username, no_telepon, alamat FROM pelanggan WHERE id_pelanggan = ? AND id_user IS NULL LIMIT 1');
}
mysqli_stmt_bind_param($customer_stmt, 'i', $id);
mysqli_stmt_execute($customer_stmt);
$customer = mysqli_fetch_assoc(mysqli_stmt_get_result($customer_stmt));
if (!$customer) {
    http_response_code(404);
    exit('Pelanggan tidak ditemukan.');
}
$is_account = $customer_type === 'account';

$valid_date = static function (string $value): string {
    $date = DateTime::createFromFormat('!Y-m-d', $value);
    return $date && $date->format('Y-m-d') === $value ? $value : '';
};
$tanggal_awal = $valid_date(trim($_GET['tanggal_awal'] ?? ''));
$tanggal_akhir = $valid_date(trim($_GET['tanggal_akhir'] ?? ''));
if ($tanggal_awal === '' || $tanggal_akhir === '') {
    $tanggal_awal = '';
    $tanggal_akhir = '';
} elseif ($tanggal_awal > $tanggal_akhir) {
    [$tanggal_awal, $tanggal_akhir] = [$tanggal_akhir, $tanggal_awal];
}

if ($is_account) {
    $condition = "(c.id_user = ? OR (s.id_kasir = ? AND buyer.level = 'user'))";
    $date_sql = $tanggal_awal !== '' ? ' AND DATE(s.tanggal_penjualan) BETWEEN ? AND ?' : '';
    $stats_sql = "SELECT COUNT(DISTINCT s.id_penjualan) AS total_pembelian, COALESCE(SUM(s.total_harga), 0) AS total_nilai FROM penjualan s LEFT JOIN pelanggan c ON c.id_pelanggan = s.id_pelanggan LEFT JOIN user buyer ON buyer.id_user = s.id_kasir WHERE $condition$date_sql";
    $history_sql = "SELECT s.*, buyer.nama AS nama_kasir, buyer.level AS level_pembuat, (SELECT COALESCE(SUM(d.jumlah_produk), 0) FROM detail_penjualan d WHERE d.id_penjualan = s.id_penjualan) AS total_items FROM penjualan s LEFT JOIN pelanggan c ON c.id_pelanggan = s.id_pelanggan LEFT JOIN user buyer ON buyer.id_user = s.id_kasir WHERE $condition$date_sql ORDER BY s.tanggal_penjualan DESC, s.id_penjualan DESC";
    $stats_stmt = mysqli_prepare($koneksi, $stats_sql);
    $history_stmt = mysqli_prepare($koneksi, $history_sql);
    if ($tanggal_awal !== '') {
        mysqli_stmt_bind_param($stats_stmt, 'iiss', $id, $id, $tanggal_awal, $tanggal_akhir);
        mysqli_stmt_bind_param($history_stmt, 'iiss', $id, $id, $tanggal_awal, $tanggal_akhir);
    } else {
        mysqli_stmt_bind_param($stats_stmt, 'ii', $id, $id);
        mysqli_stmt_bind_param($history_stmt, 'ii', $id, $id);
    }
} else {
    $phone = trim((string) ($customer['no_telepon'] ?? ''));
    $phoneKey = str_replace([' ', '-', '+', '(', ')'], '', $phone);
    $guestKey = $phoneKey !== '' ? 'tel:' . $phoneKey : 'nama:' . strtolower(trim((string) $customer['nama']));
    $contactExpression = "CASE WHEN TRIM(COALESCE(c2.no_telepon, '')) <> '' THEN CONCAT('tel:', REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(TRIM(c2.no_telepon), ' ', ''), '-', ''), '+', ''), '(', ''), ')', '')) ELSE CONCAT('nama:', LOWER(TRIM(COALESCE(c2.nama_pelanggan, '')))) END";
    $condition = "s.id_pelanggan IN (SELECT c2.id_pelanggan FROM pelanggan c2 WHERE c2.id_user IS NULL AND $contactExpression = ? AND NOT EXISTS (SELECT 1 FROM penjualan old_order INNER JOIN user old_buyer ON old_buyer.id_user = old_order.id_kasir AND old_buyer.level = 'user' WHERE old_order.id_pelanggan = c2.id_pelanggan))";
    $date_sql = $tanggal_awal !== '' ? ' AND DATE(s.tanggal_penjualan) BETWEEN ? AND ?' : '';
    $stats_sql = "SELECT COUNT(DISTINCT s.id_penjualan) AS total_pembelian, COALESCE(SUM(s.total_harga), 0) AS total_nilai FROM penjualan s WHERE $condition$date_sql";
    $history_sql = "SELECT s.*, buyer.nama AS nama_kasir, buyer.level AS level_pembuat, (SELECT COALESCE(SUM(d.jumlah_produk), 0) FROM detail_penjualan d WHERE d.id_penjualan = s.id_penjualan) AS total_items FROM penjualan s LEFT JOIN user buyer ON buyer.id_user = s.id_kasir WHERE $condition$date_sql ORDER BY s.tanggal_penjualan DESC, s.id_penjualan DESC";
    $stats_stmt = mysqli_prepare($koneksi, $stats_sql);
    $history_stmt = mysqli_prepare($koneksi, $history_sql);
    if ($tanggal_awal !== '') {
        mysqli_stmt_bind_param($stats_stmt, 'sss', $guestKey, $tanggal_awal, $tanggal_akhir);
        mysqli_stmt_bind_param($history_stmt, 'sss', $guestKey, $tanggal_awal, $tanggal_akhir);
    } else {
        mysqli_stmt_bind_param($stats_stmt, 's', $guestKey);
        mysqli_stmt_bind_param($history_stmt, 's', $guestKey);
    }
}
mysqli_stmt_execute($stats_stmt);
$stats = mysqli_fetch_assoc(mysqli_stmt_get_result($stats_stmt)) ?: ['total_pembelian' => 0, 'total_nilai' => 0];
$total_pembelian = (int) $stats['total_pembelian'];
$total_nilai = (float) $stats['total_nilai'];
mysqli_stmt_execute($history_stmt);
$query_pembelian = mysqli_stmt_get_result($history_stmt);

$customer['customer_type'] = $customer_type;
$customer['id'] = $id;
$customer['has_orders'] = $total_pembelian > 0;
require APP_ROOT . '/resources/views/admin/pelanggan_detail.php';
