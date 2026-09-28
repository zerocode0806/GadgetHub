<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
require_once APP_ROOT . '/app/Support/admin_helpers.php';
admin_require_roles(['admin']);

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    admin_set_flash('error', 'ID akun staf tidak valid.');
    admin_redirect('/admin/users');
}
$load = mysqli_prepare($koneksi, "SELECT id_user, nama, username, level FROM user WHERE id_user = ? AND level IN ('admin', 'petugas') LIMIT 1");
mysqli_stmt_bind_param($load, 'i', $id);
mysqli_stmt_execute($load);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($load));
if (!$data) {
    admin_set_flash('error', 'Akun staf tidak ditemukan.');
    admin_redirect('/admin/users');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    admin_verify_csrf();
    $nama = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = (string) ($_POST['password'] ?? '');
    $confirm_password = (string) ($_POST['confirm_password'] ?? '');
    $level = $_POST['level'] ?? '';

    if ($nama === '' || $username === '' || !in_array($level, ['admin', 'petugas'], true) || ($password !== '' && (strlen($password) < 8 || $password !== $confirm_password))) {
        admin_set_flash('error', 'Data tidak valid. Password baru harus minimal 8 karakter dan konfirmasi harus cocok.');
        admin_redirect('/admin/users/edit?id=' . $id);
    }
    if ($id === (int) $_SESSION['id_user'] && $level !== $data['level']) {
        admin_set_flash('error', 'Peran akun yang sedang digunakan tidak dapat diubah dari halaman ini.');
        admin_redirect('/admin/users/edit?id=' . $id);
    }
    $check = mysqli_prepare($koneksi, 'SELECT id_user FROM user WHERE username = ? AND id_user <> ? LIMIT 1');
    mysqli_stmt_bind_param($check, 'si', $username, $id);
    mysqli_stmt_execute($check);
    if (mysqli_num_rows(mysqli_stmt_get_result($check)) > 0) {
        admin_set_flash('error', 'Username sudah digunakan oleh akun lain.');
        admin_redirect('/admin/users/edit?id=' . $id);
    }

    if ($password !== '') {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $update = mysqli_prepare($koneksi, "UPDATE user SET nama = ?, username = ?, password = ?, level = ? WHERE id_user = ? AND level IN ('admin', 'petugas')");
        mysqli_stmt_bind_param($update, 'ssssi', $nama, $username, $hash, $level, $id);
    } else {
        $update = mysqli_prepare($koneksi, "UPDATE user SET nama = ?, username = ?, level = ? WHERE id_user = ? AND level IN ('admin', 'petugas')");
        mysqli_stmt_bind_param($update, 'sssi', $nama, $username, $level, $id);
    }
    if (mysqli_stmt_execute($update)) {
        if ($id === (int) $_SESSION['id_user']) {
            $_SESSION['username'] = $username;
        }
        admin_set_flash('success', 'Akun staf berhasil diperbarui.');
        admin_redirect('/admin/users');
    }
    admin_set_flash('error', 'Akun staf gagal diperbarui.');
    admin_redirect('/admin/users/edit?id=' . $id);
}

$admin_flash = $_SESSION['admin_flash'] ?? null;
unset($_SESSION['admin_flash']);
require APP_ROOT . '/resources/views/admin/user_ubah.php';
