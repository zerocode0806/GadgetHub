<?php
include 'koneksi.php';

if (!isset($_SESSION['id_user'], $_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    http_response_code(403);
    exit('Akses ditolak.');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php?page=pembelian');
    exit;
}

$allowed_statuses = ['Proses', 'Dikirim', 'Selesai', 'Dibatalkan', 'Selsesai'];
$order_id = filter_input(INPUT_POST, 'id_penjualan', FILTER_VALIDATE_INT);
$status = $_POST['status'] ?? '';
$csrf = $_POST['admin_csrf'] ?? '';

if (!hash_equals($_SESSION['admin_csrf'] ?? '', $csrf) || !$order_id || !in_array($status, $allowed_statuses, true)) {
    $_SESSION['admin_flash'] = ['type' => 'error', 'message' => 'Status pesanan tidak valid.'];
    header('Location: dashboard.php?page=pembelian');
    exit;
}

$update_stmt = mysqli_prepare($koneksi, 'UPDATE penjualan SET status = ? WHERE id_penjualan = ?');
mysqli_stmt_bind_param($update_stmt, 'si', $status, $order_id);
$_SESSION['admin_flash'] = mysqli_stmt_execute($update_stmt)
    ? ['type' => 'success', 'message' => 'Status pesanan berhasil diperbarui.']
    : ['type' => 'error', 'message' => 'Status pesanan gagal diperbarui.'];

header('Location: dashboard.php?page=pembelian');
exit;