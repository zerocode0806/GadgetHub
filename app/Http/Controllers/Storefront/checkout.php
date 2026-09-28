<?php
// This endpoint used to expose the old cashier/POS checkout. Ecommerce
// customers use /checkout/customer; staff manage orders from /admin/sales.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['level']) && $_SESSION['level'] === 'user') {
    header('Location: /checkout/customer');
} elseif (isset($_SESSION['level']) && in_array($_SESSION['level'], ['admin', 'petugas'], true)) {
    header('Location: /admin/sales');
} else {
    header('Location: /login');
}
exit;
