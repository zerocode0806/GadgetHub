<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
require_once APP_ROOT . '/app/Support/admin_helpers.php';
admin_require_roles(['admin']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    admin_redirect('/admin/customers');
}
admin_verify_csrf();
$customer_type = ($_POST['type'] ?? 'account') === 'guest' ? 'guest' : 'account';
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    admin_set_flash('error', 'ID pelanggan tidak valid.');
    admin_redirect('/admin/customers');
}

if ($customer_type === 'account') {
    $account = mysqli_prepare($koneksi, "SELECT id_user FROM user WHERE id_user = ? AND level = 'user' LIMIT 1");
    mysqli_stmt_bind_param($account, 'i', $id);
    mysqli_stmt_execute($account);
    if (!mysqli_fetch_assoc(mysqli_stmt_get_result($account))) {
        admin_set_flash('error', 'Akun pelanggan tidak ditemukan.');
        admin_redirect('/admin/customers');
    }

    $orders = mysqli_prepare($koneksi, 'SELECT COUNT(DISTINCT s.id_penjualan) AS total FROM penjualan s LEFT JOIN pelanggan c ON c.id_pelanggan = s.id_pelanggan WHERE c.id_user = ? OR s.id_kasir = ?');
    mysqli_stmt_bind_param($orders, 'ii', $id, $id);
    mysqli_stmt_execute($orders);
    $order_count = (int) (mysqli_fetch_assoc(mysqli_stmt_get_result($orders))['total'] ?? 0);
    $reviews = mysqli_prepare($koneksi, 'SELECT COUNT(*) AS total FROM ulasan_produk WHERE id_user = ?');
    mysqli_stmt_bind_param($reviews, 'i', $id);
    mysqli_stmt_execute($reviews);
    $review_count = (int) (mysqli_fetch_assoc(mysqli_stmt_get_result($reviews))['total'] ?? 0);

    if ($order_count > 0 || $review_count > 0) {
        admin_set_flash('error', 'Akun pelanggan tidak dapat dihapus karena memiliki riwayat pesanan atau ulasan. Riwayat tersebut harus tetap tersimpan.');
        admin_redirect('/admin/customers');
    }

    mysqli_begin_transaction($koneksi);
    try {
        foreach (['cart', 'keranjang'] as $table) {
            $deleteCart = mysqli_prepare($koneksi, "DELETE FROM `$table` WHERE id_user = ?");
            mysqli_stmt_bind_param($deleteCart, 'i', $id);
            if (!mysqli_stmt_execute($deleteCart)) {
                throw new RuntimeException('Gagal membersihkan keranjang pelanggan.');
            }
        }
        $delete_snapshots = mysqli_prepare($koneksi, 'DELETE FROM pelanggan WHERE id_user = ?');
        mysqli_stmt_bind_param($delete_snapshots, 'i', $id);
        if (!mysqli_stmt_execute($delete_snapshots)) {
            throw new RuntimeException('Gagal menghapus data pelanggan.');
        }
        $delete_account = mysqli_prepare($koneksi, "DELETE FROM user WHERE id_user = ? AND level = 'user'");
        mysqli_stmt_bind_param($delete_account, 'i', $id);
        if (!mysqli_stmt_execute($delete_account) || mysqli_stmt_affected_rows($delete_account) !== 1) {
            throw new RuntimeException('Gagal menghapus akun pelanggan.');
        }
        mysqli_commit($koneksi);
        admin_set_flash('success', 'Akun pelanggan berhasil dihapus.');
    } catch (Throwable $error) {
        mysqli_rollback($koneksi);
        admin_set_flash('error', $error->getMessage());
    }
} else {
    $contact = mysqli_prepare($koneksi, 'SELECT nama_pelanggan, no_telepon FROM pelanggan WHERE id_pelanggan = ? AND id_user IS NULL LIMIT 1');
    mysqli_stmt_bind_param($contact, 'i', $id);
    mysqli_stmt_execute($contact);
    $guest = mysqli_fetch_assoc(mysqli_stmt_get_result($contact));
    if (!$guest) {
        admin_set_flash('error', 'Data pelanggan tidak ditemukan.');
        admin_redirect('/admin/customers');
    }
    $phone = trim((string) ($guest['no_telepon'] ?? ''));
    $phoneKey = str_replace([' ', '-', '+', '(', ')'], '', $phone);
    $guestKey = $phoneKey !== '' ? 'tel:' . $phoneKey : 'nama:' . strtolower(trim((string) $guest['nama_pelanggan']));
    $contactExpression = "CASE WHEN TRIM(COALESCE(c.no_telepon, '')) <> '' THEN CONCAT('tel:', REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(TRIM(c.no_telepon), ' ', ''), '-', ''), '+', ''), '(', ''), ')', '')) ELSE CONCAT('nama:', LOWER(TRIM(COALESCE(c.nama_pelanggan, '')))) END";
    $orders = mysqli_prepare($koneksi, "SELECT COUNT(DISTINCT s.id_penjualan) AS total FROM pelanggan c LEFT JOIN penjualan s ON s.id_pelanggan = c.id_pelanggan WHERE c.id_user IS NULL AND $contactExpression = ? AND NOT EXISTS (SELECT 1 FROM penjualan old_order INNER JOIN user old_buyer ON old_buyer.id_user = old_order.id_kasir AND old_buyer.level = 'user' WHERE old_order.id_pelanggan = c.id_pelanggan)");
    mysqli_stmt_bind_param($orders, 's', $guestKey);
    mysqli_stmt_execute($orders);
    $order_count = (int) (mysqli_fetch_assoc(mysqli_stmt_get_result($orders))['total'] ?? 0);
    if ($order_count > 0) {
        admin_set_flash('error', 'Data kontak ini memiliki riwayat pesanan dan tidak dapat dihapus.');
        admin_redirect('/admin/customers');
    }
    $delete = mysqli_prepare($koneksi, "DELETE c FROM pelanggan c WHERE c.id_user IS NULL AND $contactExpression = ? AND NOT EXISTS (SELECT 1 FROM penjualan old_order INNER JOIN user old_buyer ON old_buyer.id_user = old_order.id_kasir AND old_buyer.level = 'user' WHERE old_order.id_pelanggan = c.id_pelanggan)");
    mysqli_stmt_bind_param($delete, 's', $guestKey);
    if (mysqli_stmt_execute($delete) && mysqli_stmt_affected_rows($delete) > 0) {
        admin_set_flash('success', 'Data kontak pelanggan berhasil dihapus.');
    } else {
        admin_set_flash('error', 'Data pelanggan tidak ditemukan atau gagal dihapus.');
    }
}

admin_redirect('/admin/customers');
