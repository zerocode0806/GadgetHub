<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    echo "<script>showError('Akses ditolak! Hanya admin yang bisa mengakses halaman ini.', '/');</script>";
    exit();
}
$usersResult = mysqli_query($koneksi, 'SELECT * FROM user ORDER BY level DESC, nama ASC');
$users = [];
if ($usersResult) {
    while ($row = mysqli_fetch_assoc($usersResult)) {
        $users[] = $row;
    }
}
require APP_ROOT . '/resources/views/admin/user.php';
