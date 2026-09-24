<?php
require_once APP_ROOT . '/app/Support/user_helpers.php';
user_require_customer();
$id_user = (int) $_SESSION['id_user'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    user_verify_csrf();
    $nama = trim($_POST['nama'] ?? ''); $username = trim($_POST['username'] ?? ''); $telepon = trim($_POST['no_telepon'] ?? ''); $alamat = trim($_POST['alamat'] ?? '');
    if ($nama === '' || $username === '') { user_flash('error', 'Nama dan username wajib diisi.'); user_redirect('profile.php'); }
    if ($telepon !== '' && !preg_match('/^[0-9+()\s-]{7,30}$/', $telepon)) { user_flash('error', 'Format nomor telepon tidak valid.'); user_redirect('profile.php'); }
    $check = mysqli_prepare($koneksi, 'SELECT id_user FROM user WHERE username = ? AND id_user <> ? LIMIT 1'); mysqli_stmt_bind_param($check, 'si', $username, $id_user); mysqli_stmt_execute($check); if (mysqli_num_rows(mysqli_stmt_get_result($check)) > 0) { user_flash('error', 'Username sudah digunakan.'); user_redirect('profile.php'); }
    $update = mysqli_prepare($koneksi, 'UPDATE user SET nama = ?, username = ?, no_telepon = ?, alamat = ? WHERE id_user = ? AND level = \'user\''); mysqli_stmt_bind_param($update, 'ssssi', $nama, $username, $telepon, $alamat, $id_user);
    if (mysqli_stmt_execute($update)) { $_SESSION['username'] = $username; user_flash('success', 'Profil berhasil diperbarui.'); } else user_flash('error', 'Profil gagal diperbarui.'); user_redirect('profile.php');
}
$stmt = mysqli_prepare($koneksi, 'SELECT nama, username, no_telepon, alamat FROM user WHERE id_user = ? AND level = \'user\' LIMIT 1'); mysqli_stmt_bind_param($stmt, 'i', $id_user); mysqli_stmt_execute($stmt); $profile = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)); if (!$profile) user_redirect('logout.php');
$page_title = 'Profil'; include APP_ROOT . '/resources/views/storefront/header.php';
?>
<div class="page-title"><h1>Profil saya</h1><div class="muted">Kelola data yang dipakai untuk pesananmu.</div></div>
<section class="profile-layout">
    <div class="profile-hero panel"><div class="profile-avatar"><i class="fa-regular fa-user"></i></div><h2><?= e($profile['nama']); ?></h2><p><?= e($profile['username']); ?></p><span class="profile-role">Customer</span></div>
    <div class="panel profile-form-panel"><h2>Informasi akun</h2><form method="post"><input type="hidden" name="csrf_token" value="<?= e(user_csrf_token()); ?>"><div class="form-group"><label for="nama">Nama</label><input class="form-control" id="nama" name="nama" value="<?= e($profile['nama']); ?>" maxlength="255" required></div><div class="form-group"><label for="username">Username</label><input class="form-control" id="username" name="username" value="<?= e($profile['username']); ?>" maxlength="255" required></div><div class="form-group"><label for="no_telepon">Nomor telepon</label><input class="form-control" id="no_telepon" name="no_telepon" value="<?= e($profile['no_telepon']); ?>" maxlength="30" inputmode="tel"></div><div class="form-group"><label for="alamat">Alamat</label><textarea class="form-control" id="alamat" name="alamat" rows="4" maxlength="1000"><?= e($profile['alamat']); ?></textarea></div><div class="profile-actions"><button class="btn btn-primary" type="submit"><i class="fa-solid fa-check"></i>&nbsp; Simpan perubahan</button><a class="btn btn-light" href="logout.php">Keluar</a></div></form></div>
</section>
<?php include APP_ROOT . '/resources/views/storefront/footer.php'; ?>
