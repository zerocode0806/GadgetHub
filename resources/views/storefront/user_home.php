<section class="hero">
    <h1>Belanja mudah, cepat, dan praktis.</h1>
    <p>Temukan kebutuhan pilihanmu dan pesan langsung dari toko kami dengan proses yang sederhana.</p>
    <a class="btn btn-primary" href="/products">Jelajahi produk <i class="fa-solid fa-arrow-right" style="margin-left:8px"></i></a>
</section>
<section>
    <div class="section-head"><div><h2>Produk terbaru</h2><span>Pilihan yang tersedia hari ini</span></div><a class="muted" href="/products">Lihat semua <i class="fa-solid fa-arrow-right"></i></a></div>
    <div class="product-grid">
    <?php while ($product = mysqli_fetch_assoc($products)): ?>
        <article class="product-card">
            <a href="/product?id=<?= (int) $product['id_produk']; ?>"><img class="product-image" src="<?= e(user_image($product['gambar_produk'])); ?>" alt="<?= e($product['nama_produk']); ?>"></a>
            <div class="product-body"><h3><?= e($product['nama_produk']); ?></h3><div class="price"><?= user_money($product['harga']); ?></div><div class="stock <?= (int) $product['stok'] < 1 ? 'out' : ''; ?>"><?= (int) $product['stok'] > 0 ? 'Stok ' . (int) $product['stok'] : 'Stok habis'; ?></div><div class="product-actions"><?php if ((int) $product['stok'] > 0): ?><form method="post" action="/cart/add"><input type="hidden" name="csrf_token" value="<?= e(user_csrf_token()); ?>"><input type="hidden" name="id_produk" value="<?= (int) $product['id_produk']; ?>"><input type="hidden" name="jumlah" value="1"><input type="hidden" name="buy_now" value="1"><button class="btn btn-light" type="submit">Beli Sekarang</button></form><form class="ajax-cart-form" method="post" action="/cart/add"><input type="hidden" name="csrf_token" value="<?= e(user_csrf_token()); ?>"><input type="hidden" name="id_produk" value="<?= (int) $product['id_produk']; ?>"><input type="hidden" name="jumlah" value="1"><button class="btn btn-primary" type="submit" aria-label="Tambah ke keranjang"><i class="fa-solid fa-cart-plus"></i></button></form><?php endif; ?></div></div>
        </article>
    <?php endwhile; ?>
    </div>
</section>
<?php include APP_ROOT . '/resources/views/storefront/footer.php'; ?>
