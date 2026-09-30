<?php
require_once APP_ROOT . '/app/Support/user_helpers.php';

if (isset($_SESSION['id_user'], $_SESSION['level'])) {
    user_redirect($_SESSION['level'] === 'user' ? '/shop' : '/dashboard');
}

$register_error = '';
$register_values = [
    'nama' => '',
    'username' => '',
    'no_telepon' => '',
    'alamat' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    user_verify_csrf();

    $register_values['nama'] = trim((string) ($_POST['nama'] ?? ''));
    $register_values['username'] = strtolower(trim((string) ($_POST['username'] ?? '')));
    $register_values['no_telepon'] = trim((string) ($_POST['no_telepon'] ?? ''));
    $register_values['alamat'] = trim((string) ($_POST['alamat'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $confirm_password = (string) ($_POST['confirm_password'] ?? '');

    $phone_digits = preg_replace('/\D+/', '', $register_values['no_telepon']);
    $name_length = function_exists('mb_strlen') ? mb_strlen($register_values['nama'], 'UTF-8') : strlen($register_values['nama']);
    $address_length = function_exists('mb_strlen') ? mb_strlen($register_values['alamat'], 'UTF-8') : strlen($register_values['alamat']);
    $password_length = function_exists('mb_strlen') ? mb_strlen($password, 'UTF-8') : strlen($password);

    if ($name_length < 2 || $name_length > 120) {
        $register_error = 'Masukkan nama lengkap (maksimal 120 karakter).';
    } elseif (!preg_match('/^[a-zA-Z0-9._-]{3,30}$/', $register_values['username'])) {
        $register_error = 'Username harus terdiri dari 3–30 karakter: huruf, angka, titik, garis bawah, atau tanda hubung.';
    } elseif (!preg_match('/^[0-9+()\s.-]+$/', $register_values['no_telepon']) || strlen($phone_digits) < 8 || strlen($phone_digits) > 15) {
        $register_error = 'Masukkan nomor telepon yang valid (8–15 digit).';
    } elseif ($address_length > 1000) {
        $register_error = 'Alamat terlalu panjang (maksimal 1.000 karakter).';
    } elseif ($password_length < 8 || strlen($password) > 72) {
        $register_error = 'Kata sandi harus 8–72 karakter.';
    } elseif ($password !== $confirm_password) {
        $register_error = 'Konfirmasi kata sandi belum cocok.';
    } else {
        $check = mysqli_prepare($koneksi, 'SELECT id_user FROM user WHERE username = ? LIMIT 1');
        if (!$check) {
            $register_error = 'Pendaftaran belum dapat diproses. Silakan coba kembali.';
        } else {
            mysqli_stmt_bind_param($check, 's', $register_values['username']);
            mysqli_stmt_execute($check);
            $username_exists = mysqli_num_rows(mysqli_stmt_get_result($check)) > 0;
            mysqli_stmt_close($check);

            if ($username_exists) {
                $register_error = 'Username tersebut sudah digunakan. Coba username lain.';
            } else {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $level = 'user';
                $insert = mysqli_prepare($koneksi, 'INSERT INTO user (nama, username, no_telepon, alamat, password, level) VALUES (?, ?, ?, ?, ?, ?)');
                if (!$insert) {
                    $register_error = 'Pendaftaran belum dapat diproses. Silakan coba kembali.';
                } else {
                    mysqli_stmt_bind_param(
                        $insert,
                        'ssssss',
                        $register_values['nama'],
                        $register_values['username'],
                        $register_values['no_telepon'],
                        $register_values['alamat'],
                        $password_hash,
                        $level
                    );

                    if (mysqli_stmt_execute($insert)) {
                        mysqli_stmt_close($insert);
                        session_regenerate_id(true);
                        unset($_SESSION['id_user'], $_SESSION['username'], $_SESSION['level']);
                        $_SESSION['login_notice'] = 'Akun berhasil dibuat. Silakan masuk menggunakan username dan kata sandi yang baru didaftarkan.';
                        user_redirect('/login');
                    }

                    mysqli_stmt_close($insert);
                    $register_error = 'Akun belum berhasil dibuat. Periksa kembali data dan coba lagi.';
                }
            }
        }
    }
}

require APP_ROOT . '/resources/views/auth/register.php';
