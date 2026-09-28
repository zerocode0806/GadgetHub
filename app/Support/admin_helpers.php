<?php

function admin_require_roles(array $roles = ['admin']): void
{
    if (!isset($_SESSION['id_user'], $_SESSION['level'])) {
        header('Location: /login');
        exit;
    }

    if (!in_array($_SESSION['level'], $roles, true)) {
        http_response_code(403);
        exit('Akses ditolak.');
    }
}

function admin_csrf_token(): string
{
    if (empty($_SESSION['admin_csrf'])) {
        $_SESSION['admin_csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['admin_csrf'];
}

function admin_verify_csrf(): void
{
    $token = $_POST['admin_csrf'] ?? '';
    if (!hash_equals($_SESSION['admin_csrf'] ?? '', $token)) {
        http_response_code(419);
        exit('Permintaan tidak valid. Silakan muat ulang halaman.');
    }
}

function admin_set_flash(string $type, string $message): void
{
    $_SESSION['admin_flash'] = ['type' => $type, 'message' => $message];
}

function admin_redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}
