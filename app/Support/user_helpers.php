<?php
require_once APP_ROOT . '/app/Support/koneksi.php';

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function user_site_settings(): array
{
    global $koneksi;
    $result = mysqli_query($koneksi, 'SELECT business_name, logo, email, address, phone FROM settings WHERE id = 1 LIMIT 1');
    $settings = $result ? (mysqli_fetch_assoc($result) ?: []) : [];
    $settings['categories'] = [];
    $categoryResult = mysqli_query($koneksi, "SELECT nama_kategori FROM kategori ORDER BY CASE nama_kategori WHEN 'Smartphone' THEN 1 WHEN 'Audio' THEN 2 WHEN 'Wearable' THEN 3 WHEN 'Kamera & Video' THEN 4 ELSE 99 END, nama_kategori ASC");
    if ($categoryResult) {
        while ($category = mysqli_fetch_assoc($categoryResult)) $settings['categories'][] = $category['nama_kategori'];
    }
    return $settings;
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
    $expected = $_SESSION['user_csrf'] ?? '';
    $token = $_POST['csrf_token'] ?? '';
    if ($expected === '' || $token === '' || !hash_equals($expected, $token)) {
        http_response_code(419);
        exit('Permintaan tidak valid. Silakan muat ulang halaman.');
    }
}

function user_cart(): array
{
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['id_user'], $_SESSION['level']) && $_SESSION['level'] === 'user') {
        global $koneksi;
        $id_user = (int) $_SESSION['id_user'];
        $cart_owner = $_SESSION['cart_owner_user_id'] ?? null;
        $legacy_session_cart = $cart_owner === null ? $_SESSION['cart'] : [];
        if ($cart_owner !== null && (int) $cart_owner !== $id_user) {
            $_SESSION['cart'] = [];
        }

        $stmt = mysqli_prepare($koneksi, 'SELECT id_produk, jumlah FROM keranjang WHERE id_user = ? AND jumlah > 0');
        if ($stmt && mysqli_stmt_bind_param($stmt, 'i', $id_user) && mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            $saved_cart = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $saved_cart[(int) $row['id_produk']] = (int) $row['jumlah'];
            }

            foreach ($legacy_session_cart as $product_id => $legacy_quantity) {
                $product_id = (int) $product_id;
                $legacy_quantity = (int) $legacy_quantity;
                if ($product_id < 1 || $legacy_quantity < 1) {
                    continue;
                }
                $product_stmt = mysqli_prepare($koneksi, 'SELECT stok FROM produk WHERE id_produk = ? LIMIT 1');
                if (!$product_stmt || !mysqli_stmt_bind_param($product_stmt, 'i', $product_id) || !mysqli_stmt_execute($product_stmt)) {
                    continue;
                }
                $product = mysqli_fetch_assoc(mysqli_stmt_get_result($product_stmt));
                if (!$product) {
                    continue;
                }
                $quantity = min(max($saved_cart[$product_id] ?? 0, $legacy_quantity), (int) $product['stok']);
                if ($quantity > 0 && user_cart_set_quantity($product_id, $quantity)) {
                    $saved_cart[$product_id] = $quantity;
                }
            }

            $_SESSION['cart'] = $saved_cart;
            $_SESSION['cart_owner_user_id'] = $id_user;
        }
    }

    return $_SESSION['cart'];
}

function user_cart_set_quantity(int $id_produk, int $jumlah): bool
{
    if ($id_produk < 1 || $jumlah < 0) {
        return false;
    }

    if (isset($_SESSION['id_user'], $_SESSION['level']) && $_SESSION['level'] === 'user') {
        global $koneksi;
        $id_user = (int) $_SESSION['id_user'];

        if ($jumlah === 0) {
            $stmt = mysqli_prepare($koneksi, 'DELETE FROM keranjang WHERE id_user = ? AND id_produk = ?');
            if (!$stmt || !mysqli_stmt_bind_param($stmt, 'ii', $id_user, $id_produk) || !mysqli_stmt_execute($stmt)) {
                return false;
            }
            unset($_SESSION['cart'][$id_produk]);
            return true;
        }

        $stmt = mysqli_prepare($koneksi, 'INSERT INTO keranjang (id_user, id_produk, jumlah) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE jumlah = ?');
        if (!$stmt || !mysqli_stmt_bind_param($stmt, 'iiii', $id_user, $id_produk, $jumlah, $jumlah) || !mysqli_stmt_execute($stmt)) {
            return false;
        }
        $_SESSION['cart'][$id_produk] = $jumlah;
        return true;
    }

    if ($jumlah === 0) {
        unset($_SESSION['cart'][$id_produk]);
    } else {
        $_SESSION['cart'][$id_produk] = $jumlah;
    }
    return true;
}

function user_cart_clear(): bool
{
    if (isset($_SESSION['id_user'], $_SESSION['level']) && $_SESSION['level'] === 'user') {
        global $koneksi;
        $id_user = (int) $_SESSION['id_user'];
        $stmt = mysqli_prepare($koneksi, 'DELETE FROM keranjang WHERE id_user = ?');
        if (!$stmt || !mysqli_stmt_bind_param($stmt, 'i', $id_user) || !mysqli_stmt_execute($stmt)) {
            return false;
        }
    }

    $_SESSION['cart'] = [];
    return true;
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
