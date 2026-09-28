<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
require_once APP_ROOT . '/app/Support/admin_helpers.php';
admin_require_roles(['admin']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    admin_redirect('/admin/users');
}
admin_verify_csrf();
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    admin_set_flash('error', 'ID akun tidak valid.');
    admin_redirect('/admin/users');
}
if ($id === (int) $_SESSION['id_user']) {
    admin_set_flash('error', 'Akun yang sedang digunakan tidak dapat dihapus.');
    admin_redirect('/admin/users');
}

$load = mysqli_prepare($koneksi, "SELECT id_user FROM user WHERE id_user = ? AND level IN ('admin', 'petugas') LIMIT 1");
mysqli_stmt_bind_param($load, 'i', $id);
mysqli_stmt_execute($load);
if (!mysqli_fetch_assoc(mysqli_stmt_get_result($load))) {
    admin_set_flash('error', 'Akun staf tidak ditemukan.');
    admin_redirect('/admin/users');
}

$orders = mysqli_prepare($koneksi, 'SELECT COUNT(*) AS total FROM penjualan WHERE id_kasir = ?');
mysqli_stmt_bind_param($orders, 'i', $id);
mysqli_stmt_execute($orders);
$order_count = (int) (mysqli_fetch_assoc(mysqli_stmt_get_result($orders))['total'] ?? 0);
$reviews = mysqli_prepare($koneksi, 'SELECT COUNT(*) AS total FROM ulasan_produk WHERE id_user = ?');
mysqli_stmt_bind_param($reviews, 'i', $id);
mysqli_stmt_execute($reviews);
$review_count = (int) (mysqli_fetch_assoc(mysqli_stmt_get_result($reviews))['total'] ?? 0);
if ($order_count > 0 || $review_count > 0) {
    admin_set_flash('error', 'Akun staf memiliki riwayat transaksi atau ulasan, sehingga tidak dapat dihapus. Ubah perannya atau pertahankan akun untuk menjaga histori.');
    admin_redirect('/admin/users');
}

mysqli_begin_transaction($koneksi);
try {
    foreach (['cart', 'keranjang'] as $table) {
        $deleteCart = mysqli_prepare($koneksi, "DELETE FROM `$table` WHERE id_user = ?");
        mysqli_stmt_bind_param($deleteCart, 'i', $id);
        if (!mysqli_stmt_execute($deleteCart)) {
            throw new RuntimeException('Gagal membersihkan data akun.');
        }
    }
    $deleteUser = mysqli_prepare($koneksi, "DELETE FROM user WHERE id_user = ? AND level IN ('admin', 'petugas')");
    mysqli_stmt_bind_param($deleteUser, 'i', $id);
    if (!mysqli_stmt_execute($deleteUser) || mysqli_stmt_affected_rows($deleteUser) !== 1) {
        throw new RuntimeException('Akun staf gagal dihapus.');
    }
    mysqli_commit($koneksi);
    admin_set_flash('success', 'Akun staf berhasil dihapus.');
} catch (Throwable $error) {
    mysqli_rollback($koneksi);
    admin_set_flash('error', $error->getMessage());
}

admin_redirect('/admin/users');
