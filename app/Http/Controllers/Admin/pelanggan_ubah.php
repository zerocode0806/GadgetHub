<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
require_once APP_ROOT . '/app/Support/admin_helpers.php';
admin_require_roles(['admin']);

$customer_type = ($_GET['type'] ?? $_POST['type'] ?? 'account') === 'guest' ? 'guest' : 'account';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    admin_set_flash('error', 'ID pelanggan tidak valid.');
    admin_redirect('/admin/customers');
}

if ($customer_type === 'account') {
    $load = mysqli_prepare($koneksi, "SELECT id_user AS customer_id, nama, username, no_telepon, alamat FROM user WHERE id_user = ? AND level = 'user' LIMIT 1");
} else {
    $load = mysqli_prepare($koneksi, 'SELECT id_pelanggan AS customer_id, nama_pelanggan AS nama, NULL AS username, no_telepon, alamat FROM pelanggan WHERE id_pelanggan = ? AND id_user IS NULL LIMIT 1');
}
mysqli_stmt_bind_param($load, 'i', $id);
mysqli_stmt_execute($load);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($load));
if (!$data) {
    admin_set_flash('error', 'Pelanggan tidak ditemukan.');
    admin_redirect('/admin/customers');
}

$guestKey = '';
if ($customer_type === 'guest') {
    $phone = trim((string) ($data['no_telepon'] ?? ''));
    $phoneKey = str_replace([' ', '-', '+', '(', ')'], '', $phone);
    $guestKey = $phoneKey !== '' ? 'tel:' . $phoneKey : 'nama:' . strtolower(trim((string) $data['nama']));
    $contactExpression = "CASE WHEN TRIM(COALESCE(c.no_telepon, '')) <> '' THEN CONCAT('tel:', REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(TRIM(c.no_telepon), ' ', ''), '-', ''), '+', ''), '(', ''), ')', '')) ELSE CONCAT('nama:', LOWER(TRIM(COALESCE(c.nama_pelanggan, '')))) END";
    $orderCheck = mysqli_prepare($koneksi, "SELECT COUNT(DISTINCT s.id_penjualan) AS total FROM pelanggan c LEFT JOIN penjualan s ON s.id_pelanggan = c.id_pelanggan WHERE c.id_user IS NULL AND $contactExpression = ? AND NOT EXISTS (SELECT 1 FROM penjualan old_order INNER JOIN user old_buyer ON old_buyer.id_user = old_order.id_kasir AND old_buyer.level = 'user' WHERE old_order.id_pelanggan = c.id_pelanggan)");
    mysqli_stmt_bind_param($orderCheck, 's', $guestKey);
    mysqli_stmt_execute($orderCheck);
    $guestOrderCount = (int) (mysqli_fetch_assoc(mysqli_stmt_get_result($orderCheck))['total'] ?? 0);
    if ($guestOrderCount > 0) {
        admin_set_flash('error', 'Data kontak lama ini merupakan snapshot transaksi dan tidak dapat diedit. Edit akun pelanggan untuk memperbarui profil tanpa mengubah alamat pesanan sebelumnya.');
        admin_redirect('/admin/customers/detail?id=' . $id . '&type=guest');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    admin_verify_csrf();
    $nama = trim($_POST['nama'] ?? '');
    $telepon = trim($_POST['no_telepon'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    if ($nama === '' || ($telepon !== '' && !preg_match('/^[0-9+()\s-]{7,30}$/', $telepon))) {
        admin_set_flash('error', 'Nama wajib diisi dan format nomor telepon harus valid.');
        admin_redirect('/admin/customers/edit?id=' . $id . '&type=' . $customer_type);
    }

    if ($customer_type === 'account') {
        $username = trim($_POST['username'] ?? '');
        $password = (string) ($_POST['password'] ?? '');
        $confirm_password = (string) ($_POST['confirm_password'] ?? '');
        if ($username === '' || ($password !== '' && (strlen($password) < 8 || $password !== $confirm_password))) {
            admin_set_flash('error', 'Username wajib diisi. Password baru harus minimal 8 karakter dan konfirmasi harus cocok.');
            admin_redirect('/admin/customers/edit?id=' . $id . '&type=account');
        }
        $check = mysqli_prepare($koneksi, 'SELECT id_user FROM user WHERE username = ? AND id_user <> ? LIMIT 1');
        mysqli_stmt_bind_param($check, 'si', $username, $id);
        mysqli_stmt_execute($check);
        if (mysqli_num_rows(mysqli_stmt_get_result($check)) > 0) {
            admin_set_flash('error', 'Username sudah digunakan oleh akun lain.');
            admin_redirect('/admin/customers/edit?id=' . $id . '&type=account');
        }
        if ($password !== '') {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $update = mysqli_prepare($koneksi, "UPDATE user SET nama = ?, username = ?, no_telepon = ?, alamat = ?, password = ? WHERE id_user = ? AND level = 'user'");
            mysqli_stmt_bind_param($update, 'sssssi', $nama, $username, $telepon, $alamat, $hash, $id);
        } else {
            $update = mysqli_prepare($koneksi, "UPDATE user SET nama = ?, username = ?, no_telepon = ?, alamat = ? WHERE id_user = ? AND level = 'user'");
            mysqli_stmt_bind_param($update, 'ssssi', $nama, $username, $telepon, $alamat, $id);
        }
    } else {
        $update = mysqli_prepare($koneksi, "UPDATE pelanggan c SET nama_pelanggan = ?, no_telepon = ?, alamat = ? WHERE c.id_user IS NULL AND $contactExpression = ? AND NOT EXISTS (SELECT 1 FROM penjualan old_order INNER JOIN user old_buyer ON old_buyer.id_user = old_order.id_kasir AND old_buyer.level = 'user' WHERE old_order.id_pelanggan = c.id_pelanggan)");
        mysqli_stmt_bind_param($update, 'ssss', $nama, $telepon, $alamat, $guestKey);
    }

    if (mysqli_stmt_execute($update)) {
        admin_set_flash('success', 'Data pelanggan berhasil diperbarui.');
        admin_redirect('/admin/customers');
    }
    admin_set_flash('error', 'Data pelanggan gagal diperbarui.');
    admin_redirect('/admin/customers/edit?id=' . $id . '&type=' . $customer_type);
}

$data['customer_type'] = $customer_type;
$admin_flash = $_SESSION['admin_flash'] ?? null;
unset($_SESSION['admin_flash']);
require APP_ROOT . '/resources/views/admin/pelanggan_ubah.php';
