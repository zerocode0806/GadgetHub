<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
require_once APP_ROOT . '/app/Support/admin_helpers.php';
admin_require_roles(['admin']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    admin_verify_csrf();
    $nama = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = (string) ($_POST['password'] ?? '');
    $confirm_password = (string) ($_POST['confirm_password'] ?? '');
    $level = $_POST['level'] ?? '';

    if ($nama === '' || $username === '' || !in_array($level, ['admin', 'petugas'], true) || strlen($password) < 8 || $password !== $confirm_password) {
        admin_set_flash('error', 'Lengkapi data staf, pilih peran yang valid, dan gunakan password minimal 8 karakter yang cocok.');
        admin_redirect('/admin/users/create');
    }
    $check = mysqli_prepare($koneksi, 'SELECT id_user FROM user WHERE username = ? LIMIT 1');
    mysqli_stmt_bind_param($check, 's', $username);
    mysqli_stmt_execute($check);
    if (mysqli_num_rows(mysqli_stmt_get_result($check)) > 0) {
        admin_set_flash('error', 'Username sudah digunakan.');
        admin_redirect('/admin/users/create');
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = mysqli_prepare($koneksi, 'INSERT INTO user (nama, username, password, level) VALUES (?, ?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'ssss', $nama, $username, $hash, $level);
    if (mysqli_stmt_execute($stmt)) {
        admin_set_flash('success', 'Akun staf berhasil ditambahkan.');
        admin_redirect('/admin/users');
    }
    admin_set_flash('error', 'Akun staf gagal ditambahkan.');
    admin_redirect('/admin/users/create');
}

$admin_flash = $_SESSION['admin_flash'] ?? null;
unset($_SESSION['admin_flash']);
require APP_ROOT . '/resources/views/admin/user_tambah.php';
