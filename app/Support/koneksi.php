<?php
// Pastikan session hanya dipanggil sekali
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$koneksi = mysqli_connect("localhost", "root", "", "ukk_kasir");

// Periksa koneksi
if (mysqli_connect_errno()) {
    echo "Koneksi database gagal: " . mysqli_connect_error();
    exit();
}

// Atur charset ke UTF-8
mysqli_set_charset($koneksi, "utf8");
?>
