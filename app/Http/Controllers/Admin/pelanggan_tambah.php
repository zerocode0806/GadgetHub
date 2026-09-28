<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
require_once APP_ROOT . '/app/Support/admin_helpers.php';
admin_require_roles(['admin']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    admin_verify_csrf();
    $nama = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $telepon = trim($_POST['no_telepon'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $password = (string) ($_POST['password'] ?? '');
    $confirm_password = (string) ($_POST['confirm_password'] ?? '');

    if ($nama === '' || $username === '' || strlen($password) < 8 || $password !== $confirm_password) {
        admin_set_flash('error', 'Nama, username, password minimal 8 karakter, dan konfirmasi password wajib valid.');
        admin_redirect('/admin/customers/create');
    }
    if ($telepon !== '' && !preg_match('/^[0-9+()\s-]{7,30}$/', $telepon)) {
        admin_set_flash('error', 'Format nomor telepon tidak valid.');
        admin_redirect('/admin/customers/create');
    }

    $check = mysqli_prepare($koneksi, 'SELECT id_user FROM user WHERE username = ? LIMIT 1');
    mysqli_stmt_bind_param($check, 's', $username);
    mysqli_stmt_execute($check);
    if (mysqli_num_rows(mysqli_stmt_get_result($check)) > 0) {
        admin_set_flash('error', 'Username sudah digunakan.');
        admin_redirect('/admin/customers/create');
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $level = 'user';
    $stmt = mysqli_prepare($koneksi, 'INSERT INTO user (nama, username, no_telepon, alamat, password, level) VALUES (?, ?, ?, ?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'ssssss', $nama, $username, $telepon, $alamat, $hash, $level);
    if (mysqli_stmt_execute($stmt)) {
        admin_set_flash('success', 'Akun pelanggan berhasil dibuat. Pelanggan dapat masuk melalui storefront.');
        admin_redirect('/admin/customers');
    }

    admin_set_flash('error', 'Akun pelanggan gagal dibuat. Periksa kembali data yang dimasukkan.');
    admin_redirect('/admin/customers/create');
}

$admin_flash = $_SESSION['admin_flash'] ?? null;
unset($_SESSION['admin_flash']);
require APP_ROOT . '/resources/views/admin/pelanggan_tambah.php';
