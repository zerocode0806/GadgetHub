<?php
require_once APP_ROOT . '/app/Support/koneksi.php';

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function user_site_settings(): array
{
    global $koneksi;
    $result = mysqli_query($koneksi, 'SELECT business_name, logo FROM settings WHERE id = 1 LIMIT 1');
    return $result ? (mysqli_fetch_assoc($result) ?: []) : [];
}

function user_is_logged_in(): bool
{
    return isset($_SESSION['id_user'], $_SESSION['level']);
}

function user_require_login(): void
{
    if (!user_is_logged_in()) {
        header('Location: /login');
        exit;
    }
}

function user_require_customer(): void
{
    user_require_login();

    if ($_SESSION['level'] !== 'user') {
        if (user_is_ajax_request()) {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Silakan masuk sebagai customer terlebih dahulu.']);
            exit;
        }
        header('Location: /dashboard');
        exit;
    }
}

function user_csrf_token(): string
{
    if (empty($_SESSION['user_csrf'])) {
        $_SESSION['user_csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['user_csrf'];
}

function user_verify_csrf(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['user_csrf'] ?? '', $token)) {
        http_response_code(419);
        exit('Permintaan tidak valid. Silakan muat ulang halaman.');
    }
}

function user_cart(): array
{
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    return $_SESSION['cart'];
}

function user_cart_count(): int
{
    return array_sum(array_map('intval', user_cart()));
}

function user_redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

function user_is_ajax_request(): bool
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

function user_flash(string $type, string $message): void
{
    $_SESSION['user_flash'] = ['type' => $type, 'message' => $message];
}

function user_take_flash(): ?array
{
    $flash = $_SESSION['user_flash'] ?? null;
    unset($_SESSION['user_flash']);
    return $flash;
}

function user_image(?string $filename): string
{
    $filename = trim((string) $filename);
    if ($filename === '' || !is_file(APP_ROOT . '/public/uploads/' . basename($filename))) {
        return 'data:image/svg+xml,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 480"><rect width="600" height="480" fill="#eef4f2"/><path d="M190 330l70-80 55 55 45-50 90 75H190z" fill="#9ab5ae"/><circle cx="390" cy="180" r="34" fill="#f2b84b"/><text x="300" y="410" fill="#50706a" font-family="sans-serif" font-size="26" text-anchor="middle">No image</text></svg>');
    }

    return '/uploads/' . rawurlencode(basename($filename));
}

function user_money($amount): string
{
    return 'Rp ' . number_format((int) $amount, 0, ',', '.');
}

function user_order_status_label(?string $status): string
{
    return match ($status) {
        'Proses' => 'Diproses',
        'Selsesai' => 'Selesai',
        default => $status ?: 'Diproses',
    };
}
