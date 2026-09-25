<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
$kasirResult = mysqli_query($koneksi, 'SELECT * FROM pelanggan');
$kasirList = [];
if ($kasirResult) {
    while ($row = mysqli_fetch_assoc($kasirResult)) {
        $kasirList[] = $row;
    }
}
require APP_ROOT . '/resources/views/admin/kasir.php';
