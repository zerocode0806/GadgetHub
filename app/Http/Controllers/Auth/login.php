<?php
require_once APP_ROOT . '/app/Support/koneksi.php';

$login_notice = (string) ($_SESSION['login_notice'] ?? '');
unset($_SESSION['login_notice']);
$login_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['password'])) {
    $username = trim((string) $_POST['username']);
    $password = (string) $_POST['password'];

    $stmt = mysqli_prepare($koneksi, 'SELECT id_user, nama, username, password, level FROM user WHERE username = ? LIMIT 1');
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);

        if ($data && password_verify($password, (string) $data['password'])) {
            session_regenerate_id(true);
            $_SESSION['id_user'] = (int) $data['id_user'];
            $_SESSION['username'] = (string) $data['username'];
            $_SESSION['level'] = (string) $data['level'];

            if ($_SESSION['level'] === 'user') {
                $pending = $_SESSION['checkout_buy_now'] ?? null;
                $redirect = is_array($pending)
                    && isset($pending['id_produk'], $pending['jumlah'])
                    && (int) $pending['id_produk'] > 0
                    && (int) $pending['jumlah'] > 0
                    ? '/checkout/customer'
                    : '/shop';
            } else {
                unset($_SESSION['checkout_buy_now']);
                $redirect = '/dashboard';
            }

            header('Location: ' . $redirect, true, 303);
            exit;
        }

        $login_error = 'Username atau kata sandi tidak sesuai.';
    } else {
        $login_error = 'Login belum dapat diproses. Silakan coba kembali.';
    }
}

require APP_ROOT . '/resources/views/auth/login.php';
