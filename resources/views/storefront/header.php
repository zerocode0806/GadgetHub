<?php
$site_name = $site_settings['business_name'] ?? 'Aplikasi Kasir';
$flash = user_take_flash();
$page_title = $page_title ?? $site_name;
$active_page = $active_page ?? '';
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($page_title); ?> - <?= e($site_name); ?></title>
    <link rel="stylesheet" href="/assets/css/user.css">
    <link rel="stylesheet" href="/css/theme.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body class="user-site">
<header class="user-nav">
    <div class="nav-inner">
        <a class="brand" href="/shop"><span class="brand-mark"><i class="fa-solid fa-basket-shopping"></i></span><?= e($site_name); ?></a>
        <nav class="nav-links" id="user-nav-links">
            <a class="<?= $active_page === 'home' ? 'active' : ''; ?>" href="/shop">Home</a>
            <a class="<?= $active_page === 'produk' ? 'active' : ''; ?>" href="/products">Produk</a>
            <?php if (isset($_SESSION['level']) && $_SESSION['level'] === 'user'): ?><a class="<?= $active_page === 'pesanan' ? 'active' : ''; ?>" href="/orders">Pesanan</a><?php endif; ?>
        </nav>
        <div class="nav-actions">
            <form class="search-mini" action="/products" method="get"><i class="fa-solid fa-magnifying-glass muted"></i><input name="q" value="<?= e($_GET['q'] ?? ''); ?>" aria-label="Cari produk" placeholder="Cari produk"></form>
            <a class="icon-link" href="/cart" aria-label="Keranjang"><i class="fa-solid fa-cart-shopping"></i><span id="cart-count" class="cart-count"<?= user_cart_count() < 1 ? ' hidden' : ''; ?>><?= user_cart_count(); ?></span></a>
            <?php if (isset($_SESSION['id_user'])): ?><a class="user-pill" href="/profile"><i class="fa-regular fa-user"></i> <?= e($_SESSION['username']); ?></a><?php else: ?><a class="btn btn-primary" href="/login">Masuk</a><?php endif; ?>
            <button class="menu-toggle" type="button" aria-label="Buka navigasi" onclick="document.getElementById('user-nav-links').classList.toggle('open')"><i class="fa-solid fa-bars"></i></button>
        </div>
    </div>
</header>
<main class="user-main">
<?php if ($flash): ?><div class="alert alert-<?= e($flash['type']); ?>"><?= e($flash['message']); ?></div><?php endif; ?>
