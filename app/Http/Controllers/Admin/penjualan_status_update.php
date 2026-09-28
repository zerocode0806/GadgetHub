<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
require_once APP_ROOT . '/app/Support/admin_helpers.php';
admin_require_roles(['admin', 'petugas']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    admin_redirect('/admin/sales');
}
admin_verify_csrf();
$allowed_statuses = ['Proses', 'Dikirim', 'Selesai', 'Dibatalkan'];
$order_id = filter_input(INPUT_POST, 'id_penjualan', FILTER_VALIDATE_INT);
$status = $_POST['status'] ?? '';
if (!$order_id || !in_array($status, $allowed_statuses, true)) {
    admin_set_flash('error', 'Status pesanan tidak valid.');
    admin_redirect('/admin/sales');
}

$update_stmt = mysqli_prepare($koneksi, 'UPDATE penjualan SET status = ? WHERE id_penjualan = ?');
mysqli_stmt_bind_param($update_stmt, 'si', $status, $order_id);
$updated = mysqli_stmt_execute($update_stmt);
admin_set_flash($updated ? 'success' : 'error', $updated ? 'Status pesanan berhasil diperbarui.' : 'Status pesanan gagal diperbarui.');
admin_redirect('/admin/sales');
