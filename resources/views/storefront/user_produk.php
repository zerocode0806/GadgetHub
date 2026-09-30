<div class="page-title"><h1>Semua produk</h1><div class="muted">Pilih kategori dan cari produk berdasarkan nama, deskripsi, atau spesifikasi.</div></div>
<form class="filter-bar product-filter-bar" method="get" action="/products">
    <input type="search" name="q" value="<?= e($search); ?>" placeholder="Cari nama, kategori, atau spesifikasi produk" aria-label="Cari produk">
    <select name="kategori" aria-label="Filter kategori">
        <option value="">Semua kategori</option>
        <?php foreach ($categories as $category): ?>
            <option value="<?= e($category); ?>" <?= $categoryFilter === $category ? 'selected' : ''; ?>><?= e($category); ?></option>
        <?php endforeach; ?>
    </select>
    <select name="sort" aria-label="Urutkan produk">
        <option value="terbaru" <?= $sort === 'terbaru' ? 'selected' : ''; ?>>Produk terbaru</option>
        <option value="termurah" <?= $sort === 'termurah' ? 'selected' : ''; ?>>Harga termurah</option>
        <option value="termahal" <?= $sort === 'termahal' ? 'selected' : ''; ?>>Harga termahal</option>
    </select>
    <button class="btn btn-primary" type="submit"><i class="fa-solid fa-filter"></i>&nbsp; Terapkan</button>
</form>
<?php if ($total === 0): ?>
    <div class="panel"><p class="muted">Produk yang kamu cari belum tersedia.</p></div>
<?php else: ?>
    <div class="product-grid">
        <?php while ($product = mysqli_fetch_assoc($products)): ?>
            <article class="product-card">
                <a href="/product?id=<?= (int) $product['id_produk']; ?>"><img class="product-image" src="<?= e(user_image($product['gambar_produk'])); ?>" alt="<?= e($product['nama_produk']); ?>"></a>
                <div class="product-body">
                    <span class="product-category"><?= e($product['kategori_produk'] ?? 'Aksesori'); ?></span>
                    <h3><?= e($product['nama_produk']); ?></h3>
                    <div class="price"><?= user_money($product['harga']); ?></div>
                    <div class="stock <?= (int) $product['stok'] < 1 ? 'out' : ''; ?>"><?= (int) $product['stok'] > 0 ? 'Stok ' . (int) $product['stok'] : 'Stok habis'; ?></div>
                    <div class="product-actions">
                        <?php if ((int) $product['stok'] > 0): ?>
                            <form method="post" action="/checkout/customer"><input type="hidden" name="csrf_token" value="<?= e(user_csrf_token()); ?>"><input type="hidden" name="id_produk" value="<?= (int) $product['id_produk']; ?>"><input type="hidden" name="jumlah" value="1"><input type="hidden" name="buy_now" value="1"><button class="btn btn-light" type="submit">Beli Sekarang</button></form>
                            <form class="ajax-cart-form" method="post" action="/cart/add"><input type="hidden" name="csrf_token" value="<?= e(user_csrf_token()); ?>"><input type="hidden" name="id_produk" value="<?= (int) $product['id_produk']; ?>"><input type="hidden" name="jumlah" value="1"><button class="btn btn-primary" type="submit" aria-label="Tambah ke keranjang"><i class="fa-solid fa-cart-plus"></i></button></form>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
<?php endif; ?>
<?php if ($pages > 1): ?>
    <div class="pagination">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
            <a class="<?= $i === $page ? 'current' : ''; ?>" href="?q=<?= urlencode($search); ?>&kategori=<?= urlencode($categoryFilter); ?>&sort=<?= urlencode($sort); ?>&halaman=<?= $i; ?>"><?= $i; ?></a>
        <?php endfor; ?>
    </div>
<?php endif; ?>
<?php include APP_ROOT . '/resources/views/storefront/footer.php'; ?>
