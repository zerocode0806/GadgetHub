<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
require_once APP_ROOT . '/app/Support/admin_helpers.php';
admin_require_roles(['admin']);

$usersResult = mysqli_query($koneksi, "SELECT id_user, nama, username, level FROM user WHERE level IN ('admin', 'petugas') ORDER BY level, nama");
$users = [];
if ($usersResult) {
    while ($row = mysqli_fetch_assoc($usersResult)) {
        $users[] = $row;
    }
}
$admin_flash = $_SESSION['admin_flash'] ?? null;
unset($_SESSION['admin_flash']);
require APP_ROOT . '/resources/views/admin/user.php';
