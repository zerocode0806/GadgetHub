<?php
require_once APP_ROOT . '/app/Support/koneksi.php';

// Check if 'id' is passed in the URL
if (isset($_GET['id'])) {
    $id_user = $_GET['id'];

    // Fetch user data based on 'id'
    $query = mysqli_query($koneksi, "SELECT * FROM user WHERE id_user = '$id_user'");
    $data = mysqli_fetch_array($query);

    // Check if user data is found
    if (!$data) {
        echo "<script>alert('User tidak ditemukan!'); window.location = '/admin/users';</script>";
        exit();
    }
} else {
    echo "<script>alert('ID tidak valid!'); window.location = '/admin/users';</script>";
    exit();
}

// Update data if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $level = $_POST['level'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>alert('Password dan Konfirmasi Password tidak cocok!'); window.location = '/admin/users/edit?id=$id_user';</script>";
        exit();
    }

    // Hash password if it's set
    $hashed_password = empty($password) ? $data['password'] : password_hash($password, PASSWORD_DEFAULT);

    // Combine first and last name
    $nama = $first_name . ' ' . $last_name;

    // Update the user data in the database
    $sql = "UPDATE user SET nama = '$nama', username = '$username', password = '$hashed_password', level = '$level' WHERE id_user = '$id_user'";
    $result = mysqli_query($koneksi, $sql);

    if ($result) {
        echo "<script>alert('User berhasil diperbarui!'); window.location = '/admin/users';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan, coba lagi!'); window.location = '/admin/users/edit?id=$id_user';</script>";
    }
}
require APP_ROOT . '/resources/views/admin/user_ubah.php';
