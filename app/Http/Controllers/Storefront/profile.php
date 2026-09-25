<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
require_once APP_ROOT . '/app/Support/user_helpers.php';
user_require_customer();
$id_user = (int) $_SESSION['id_user'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    user_verify_csrf();
    $nama = trim($_POST['nama'] ?? ''); $username = trim($_POST['username'] ?? ''); $telepon = trim($_POST['no_telepon'] ?? ''); $alamat = trim($_POST['alamat'] ?? '');
    if ($nama === '' || $username === '') { user_flash('error', 'Nama dan username wajib diisi.'); user_redirect('/profile'); }
    if ($telepon !== '' && !preg_match('/^[0-9+()\s-]{7,30}$/', $telepon)) { user_flash('error', 'Format nomor telepon tidak valid.'); user_redirect('/profile'); }
    $check = mysqli_prepare($koneksi, 'SELECT id_user FROM user WHERE username = ? AND id_user <> ? LIMIT 1'); mysqli_stmt_bind_param($check, 'si', $username, $id_user); mysqli_stmt_execute($check); if (mysqli_num_rows(mysqli_stmt_get_result($check)) > 0) { user_flash('error', 'Username sudah digunakan.'); user_redirect('/profile'); }
    $update = mysqli_prepare($koneksi, 'UPDATE user SET nama = ?, username = ?, no_telepon = ?, alamat = ? WHERE id_user = ? AND level = \'user\''); mysqli_stmt_bind_param($update, 'ssssi', $nama, $username, $telepon, $alamat, $id_user);
    if (mysqli_stmt_execute($update)) { $_SESSION['username'] = $username; user_flash('success', 'Profil berhasil diperbarui.'); } else user_flash('error', 'Profil gagal diperbarui.'); user_redirect('/profile');
}
$stmt = mysqli_prepare($koneksi, 'SELECT nama, username, no_telepon, alamat FROM user WHERE id_user = ? AND level = \'user\' LIMIT 1'); mysqli_stmt_bind_param($stmt, 'i', $id_user); mysqli_stmt_execute($stmt); $profile = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)); if (!$profile) user_redirect('/logout');
$page_title = 'Profil'; $site_settings = user_site_settings();
include APP_ROOT . '/resources/views/storefront/header.php';
require APP_ROOT . '/resources/views/storefront/profile.php';
