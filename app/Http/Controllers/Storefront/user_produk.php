<?php
require_once APP_ROOT . '/app/Support/user_helpers.php';
$page_title = 'Produk';
$active_page = 'produk';
$search = trim($_GET['q'] ?? '');
$sort = $_GET['sort'] ?? 'terbaru';
$allowed_sorts = ['termurah' => 'harga ASC', 'termahal' => 'harga DESC', 'terbaru' => 'id_produk DESC'];
$order_by = $allowed_sorts[$sort] ?? $allowed_sorts['terbaru'];
$page = max(1, (int) ($_GET['halaman'] ?? 1));
$per_page = 12;
$offset = ($page - 1) * $per_page;
$where = '';
if ($search !== '') $where = 'WHERE nama_produk LIKE ? OR deskripsi_produk LIKE ?';
$count_stmt = mysqli_prepare($koneksi, "SELECT COUNT(*) AS total FROM produk $where");
if ($search !== '') { $like = '%' . $search . '%'; mysqli_stmt_bind_param($count_stmt, 'ss', $like, $like); }
mysqli_stmt_execute($count_stmt);
$total = (int) mysqli_fetch_assoc(mysqli_stmt_get_result($count_stmt))['total'];
$stmt = mysqli_prepare($koneksi, "SELECT id_produk, nama_produk, deskripsi_produk, harga, stok, gambar_produk FROM produk $where ORDER BY $order_by LIMIT ?, ?");
if ($search !== '') mysqli_stmt_bind_param($stmt, 'ssii', $like, $like, $offset, $per_page); else mysqli_stmt_bind_param($stmt, 'ii', $offset, $per_page);
mysqli_stmt_execute($stmt);
$products = mysqli_stmt_get_result($stmt);
$pages = (int) ceil($total / $per_page);
include APP_ROOT . '/resources/views/storefront/header.php';
?>
<div class="page-title"><h1>Semua produk</h1><div class="muted">Pilih kebutuhanmu dari katalog yang tersedia.</div></div>
<form class="filter-bar" method="get"><input type="search" name="q" value="<?= e($search); ?>" placeholder="Cari nama atau deskripsi produk"><select name="sort" aria-label="Urutkan produk"><option value="terbaru" <?= $sort === 'terbaru' ? 'selected' : ''; ?>>Produk terbaru</option><option value="termurah" <?= $sort === 'termurah' ? 'selected' : ''; ?>>Harga termurah</option><option value="termahal" <?= $sort === 'termahal' ? 'selected' : ''; ?>>Harga termahal</option></select><button class="btn btn-primary" type="submit"><i class="fa-solid fa-filter"></i>&nbsp; Terapkan</button></form>
<?php if ($total === 0): ?><div class="panel"><p class="muted">Produk yang kamu cari belum tersedia.</p></div><?php else: ?><div class="product-grid">
<?php while ($product = mysqli_fetch_assoc($products)): ?><article class="product-card"><a href="user_produk_detail.php?id=<?= (int) $product['id_produk']; ?>"><img class="product-image" src="<?= e(user_image($product['gambar_produk'])); ?>" alt="<?= e($product['nama_produk']); ?>"></a><div class="product-body"><h3><?= e($product['nama_produk']); ?></h3><div class="price"><?= user_money($product['harga']); ?></div><div class="stock <?= (int) $product['stok'] < 1 ? 'out' : ''; ?>"><?= (int) $product['stok'] > 0 ? 'Stok ' . (int) $product['stok'] : 'Stok habis'; ?></div><div class="product-actions"><?php if ((int) $product['stok'] > 0): ?><form method="post" action="keranjang_tambah.php"><input type="hidden" name="csrf_token" value="<?= e(user_csrf_token()); ?>"><input type="hidden" name="id_produk" value="<?= (int) $product['id_produk']; ?>"><input type="hidden" name="jumlah" value="1"><input type="hidden" name="buy_now" value="1"><button class="btn btn-light" type="submit">Beli Sekarang</button></form><form class="ajax-cart-form" method="post" action="keranjang_tambah.php"><input type="hidden" name="csrf_token" value="<?= e(user_csrf_token()); ?>"><input type="hidden" name="id_produk" value="<?= (int) $product['id_produk']; ?>"><input type="hidden" name="jumlah" value="1"><button class="btn btn-primary" type="submit" aria-label="Tambah ke keranjang"><i class="fa-solid fa-cart-plus"></i></button></form><?php endif; ?></div></div></article><?php endwhile; ?></div><?php endif; ?>
<?php if ($pages > 1): ?><div class="pagination"><?php for ($i = 1; $i <= $pages; $i++): ?><a class="<?= $i === $page ? 'current' : ''; ?>" href="?q=<?= urlencode($search); ?>&sort=<?= urlencode($sort); ?>&halaman=<?= $i; ?>"><?= $i; ?></a><?php endfor; ?></div><?php endif; ?>
<?php include APP_ROOT . '/resources/views/storefront/footer.php'; ?>
