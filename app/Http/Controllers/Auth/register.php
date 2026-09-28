<?php
require_once APP_ROOT . '/app/Support/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $level = 'user';

    if ($first_name === '' || $last_name === '' || $username === '' || strlen($password) < 8) {
        echo "<script>alert('Data wajib diisi dan password minimal 8 karakter.'); window.location = '/register';</script>";
        exit();
    }

    // Validasi password
    if ($password !== $confirm_password) {
        echo "<script>alert('Password dan Konfirmasi Password tidak cocok!'); window.location = 'register.html';</script>";
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Gabungkan nama depan dan belakang
    $nama = $first_name . ' ' . $last_name;

    // Set level ke admin
    // $level = 'admin';

    $stmt = mysqli_prepare($koneksi, "INSERT INTO user (nama, username, password, level) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'ssss', $nama, $username, $hashed_password, $level);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        echo "<script>alert('Akun berhasil dibuat!'); window.location = '/login';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan, coba lagi!'); window.location = '/register';</script>";
    }
}
require APP_ROOT . '/resources/views/auth/register.php';
