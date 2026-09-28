<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
require_once APP_ROOT . '/app/Support/admin_helpers.php';
admin_require_roles(['admin']);

$search = trim($_GET['search'] ?? '');
$like = '%' . $search . '%';
$customers = [];

$account_orders = "
    SELECT linked.id_user, COUNT(*) AS total_pembelian, COALESCE(SUM(linked.total_harga), 0) AS total_harga
    FROM (
        SELECT c.id_user, s.id_penjualan, s.total_harga
        FROM pelanggan c
        INNER JOIN penjualan s ON s.id_pelanggan = c.id_pelanggan
        WHERE c.id_user IS NOT NULL
        UNION
        SELECT s.id_kasir AS id_user, s.id_penjualan, s.total_harga
        FROM penjualan s
        INNER JOIN user buyer ON buyer.id_user = s.id_kasir AND buyer.level = 'user'
    ) linked
    GROUP BY linked.id_user
";
$account_sql = "
    SELECT u.id_user AS customer_id, u.id_user, 'account' AS customer_type,
           u.nama AS nama_pelanggan, u.username, u.no_telepon, u.alamat,
           COALESCE(order_totals.total_pembelian, 0) AS total_pembelian,
           COALESCE(order_totals.total_harga, 0) AS total_harga
    FROM user u
    LEFT JOIN ($account_orders) order_totals ON order_totals.id_user = u.id_user
    WHERE u.level = 'user'
      AND (? = '' OR u.nama LIKE ? OR u.username LIKE ? OR u.no_telepon LIKE ?)
    ORDER BY u.nama ASC
";
$account_stmt = mysqli_prepare($koneksi, $account_sql);
mysqli_stmt_bind_param($account_stmt, 'ssss', $search, $like, $like, $like);
mysqli_stmt_execute($account_stmt);
$account_result = mysqli_stmt_get_result($account_stmt);
while ($row = mysqli_fetch_assoc($account_result)) {
    $customers[] = $row;
}

$guest_sql = "
    SELECT MIN(contacts.id_pelanggan) AS customer_id, NULL AS id_user, 'guest' AS customer_type,
           MAX(contacts.nama_pelanggan) AS nama_pelanggan, NULL AS username,
           MAX(contacts.no_telepon) AS no_telepon, MAX(contacts.alamat) AS alamat,
           COUNT(DISTINCT s.id_penjualan) AS total_pembelian,
           COALESCE(SUM(s.total_harga), 0) AS total_harga
    FROM (
        SELECT c.id_pelanggan, c.nama_pelanggan, c.no_telepon, c.alamat,
               CASE
                   WHEN TRIM(COALESCE(c.no_telepon, '')) <> '' THEN CONCAT('tel:', REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(TRIM(c.no_telepon), ' ', ''), '-', ''), '+', ''), '(', ''), ')', ''))
                   ELSE CONCAT('nama:', LOWER(TRIM(COALESCE(c.nama_pelanggan, ''))))
               END AS contact_key
        FROM pelanggan c
        WHERE c.id_user IS NULL
          AND NOT EXISTS (
              SELECT 1
              FROM penjualan old_order
              INNER JOIN user old_buyer ON old_buyer.id_user = old_order.id_kasir AND old_buyer.level = 'user'
              WHERE old_order.id_pelanggan = c.id_pelanggan
          )
    ) contacts
    LEFT JOIN penjualan s ON s.id_pelanggan = contacts.id_pelanggan
    WHERE (? = '' OR contacts.nama_pelanggan LIKE ? OR contacts.no_telepon LIKE ?)
    GROUP BY contacts.contact_key
    ORDER BY MAX(contacts.nama_pelanggan) ASC
";
$guest_stmt = mysqli_prepare($koneksi, $guest_sql);
mysqli_stmt_bind_param($guest_stmt, 'sss', $search, $like, $like);
mysqli_stmt_execute($guest_stmt);
$guest_result = mysqli_stmt_get_result($guest_stmt);
while ($row = mysqli_fetch_assoc($guest_result)) {
    $customers[] = $row;
}

usort($customers, static fn (array $a, array $b): int => strcasecmp((string) $a['nama_pelanggan'], (string) $b['nama_pelanggan']));
$admin_flash = $_SESSION['admin_flash'] ?? null;
unset($_SESSION['admin_flash']);
require APP_ROOT . '/resources/views/admin/pelanggan.php';
