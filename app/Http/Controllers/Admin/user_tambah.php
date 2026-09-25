<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
if (isset($_POST['first_name'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $level = $_POST['level'];

    // Validasi password
    if ($password !== $confirm_password) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Password dan Konfirmasi Password tidak cocok!'
                });
              </script>";
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Gabungkan nama depan dan belakang
    $nama = $first_name . ' ' . $last_name;

    // Insert data ke database
    $query = mysqli_query($koneksi, "INSERT INTO user (nama, username, password, level) 
                                      VALUES ('$nama', '$username', '$hashed_password', '$level')");
    if ($query) {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'User berhasil ditambahkan',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = '/admin/users';
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Gagal menambahkan user'
                });
              </script>";
    }
}
require APP_ROOT . '/resources/views/admin/user_tambah.php';
