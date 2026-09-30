<?php
$category = trim((string) ($product['kategori_produk'] ?? 'Aksesori')) ?: 'Aksesori';
$description = trim((string) ($product['deskripsi_produk'] ?? '')) ?: 'Produk pilihan dari toko kami.';
$specifications = trim((string) ($product['spesifikasi_produk'] ?? ''));
$reviewCount = (int) ($ratingStats['total'] ?? 0);
$averageRating = (float) ($ratingStats['average'] ?? 0);
?>
<a class="btn btn-light detail-back" href="/products"><i class="fa-solid fa-arrow-left"></i>&nbsp; Kembali ke produk</a>
<section class="detail-layout" aria-labelledby="detail-product-name">
    <div class="detail-media">
        <img class="detail-image" src="<?= e(user_image($product['gambar_produk'])); ?>" alt="<?= e($product['nama_produk']); ?>">
    </div>
    <div class="detail-copy">
        <span class="detail-category"><?= e($category); ?></span>
        <h1 id="detail-product-name"><?= e($product['nama_produk']); ?></h1>
        <div class="price detail-price"><?= user_money($product['harga']); ?></div>
        <p class="detail-summary"><?= e($description); ?></p>
        <p class="stock <?= (int) $product['stok'] < 1 ? 'out' : ''; ?>">
            <?= (int) $product['stok'] > 0 ? 'Tersedia ' . (int) $product['stok'] . ' unit' : 'Stok habis'; ?>
        </p>

        <?php if ((int) $product['stok'] > 0): ?>
            <form class="ajax-cart-form detail-order-form" method="post" action="/cart/add">
                <input type="hidden" name="csrf_token" value="<?= e(user_csrf_token()); ?>">
                <input type="hidden" name="id_produk" value="<?= (int) $product['id_produk']; ?>">
                <div class="quantity detail-quantity">
                    <label for="jumlah">Jumlah</label>
                    <div class="quantity-control">
                        <button type="button" data-quantity-change="-1" aria-label="Kurangi jumlah">−</button>
                        <output id="jumlah-output">1</output>
                        <button type="button" data-quantity-change="1" aria-label="Tambah jumlah">+</button>
                    </div>
                    <input id="jumlah" name="jumlah" type="hidden" value="1" min="1" max="<?= (int) $product['stok']; ?>">
                </div>
                <p class="detail-total">Total: <strong id="detail-total-label"><?= user_money($product['harga']); ?></strong></p>
                <div class="detail-actions">
                    <button class="btn btn-primary btn-block" type="submit"><i class="fa-solid fa-cart-plus"></i>&nbsp; Tambah ke keranjang</button>
                    <button class="btn btn-buy-now btn-block" type="submit" name="buy_now" value="1" formaction="/checkout/customer"><i class="fa-solid fa-bolt"></i>&nbsp; Beli Sekarang</button>
                </div>
            </form>
        <?php else: ?>
            <button class="btn btn-light btn-block" disabled>Stok habis</button>
        <?php endif; ?>
    </div>
</section>

<section class="product-tabs" aria-label="Informasi produk">
    <div class="tab-headers" role="tablist" aria-label="Detail produk">
        <button class="tab-btn active" id="tab-description-button" type="button" role="tab" aria-selected="true" aria-controls="tab-description" data-product-tab="description">Deskripsi</button>
        <button class="tab-btn" id="tab-specification-button" type="button" role="tab" aria-selected="false" aria-controls="tab-specification" data-product-tab="specification">Spesifikasi</button>
    </div>
    <div class="tab-content">
        <div class="tab-panel active" id="tab-description" role="tabpanel" aria-labelledby="tab-description-button" data-product-panel="description">
            <p><?= nl2br(e($description)); ?></p>
        </div>
        <div class="tab-panel" id="tab-specification" role="tabpanel" aria-labelledby="tab-specification-button" data-product-panel="specification" hidden>
            <?php if ($specifications !== ''): ?>
                <p><?= nl2br(e($specifications)); ?></p>
            <?php else: ?>
                <p class="muted">Spesifikasi produk belum ditambahkan. Silakan lengkapi dari menu Edit Produk.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="product-reviews" aria-labelledby="reviews-title">
    <div class="reviews-heading">
        <div>
            <h2 id="reviews-title"><?= $reviewCount; ?> Ulasan</h2>
            <div class="reviews-average" aria-label="Rating rata-rata <?= number_format($averageRating, 1); ?> dari 5">
                <strong><?= number_format($averageRating, 1); ?></strong>
                <?php for ($star = 1; $star <= 5; $star++): ?><i class="fa-solid fa-star <?= $star <= (int) round($averageRating) ? 'is-on' : 'is-off'; ?>" aria-hidden="true"></i><?php endfor; ?>
            </div>
        </div>
        <span class="muted">Ulasan pelanggan</span>
    </div>

    <?php if (isset($_SESSION['id_user']) && ($_SESSION['level'] ?? '') === 'user'): ?>
        <form class="review-form" method="post" action="/product/review">
            <input type="hidden" name="csrf_token" value="<?= e(user_csrf_token()); ?>">
            <input type="hidden" name="id_produk" value="<?= (int) $product['id_produk']; ?>">
            <label for="review-rating">Rating Anda</label>
            <select id="review-rating" name="rating" required>
                <?php for ($ratingOption = 5; $ratingOption >= 1; $ratingOption--): ?>
                    <option value="<?= $ratingOption; ?>" <?= (int) ($userReview['rating'] ?? 5) === $ratingOption ? 'selected' : ''; ?>><?= $ratingOption; ?> dari 5 bintang</option>
                <?php endfor; ?>
            </select>
            <label for="review-text">Ulasan produk</label>
            <textarea id="review-text" name="ulasan" rows="3" maxlength="1500" required placeholder="Ceritakan pengalaman Anda menggunakan produk ini..."><?= e($userReview['ulasan'] ?? ''); ?></textarea>
            <button class="btn btn-primary" type="submit"><?= $userReview ? 'Perbarui ulasan' : 'Kirim ulasan'; ?></button>
            <small class="muted">Satu ulasan per akun; Anda bisa memperbaruinya nanti.</small>
        </form>
    <?php elseif (!isset($_SESSION['id_user'])): ?>
        <p class="review-login-note"><a href="/login">Masuk sebagai customer</a> untuk memberikan rating dan ulasan.</p>
    <?php endif; ?>

    <div class="review-list">
        <?php if ($reviews && mysqli_num_rows($reviews) > 0): ?>
            <?php while ($review = mysqli_fetch_assoc($reviews)): ?>
                <?php $reviewName = trim((string) ($review['nama'] ?? '')) ?: (trim((string) ($review['username'] ?? '')) ?: 'Customer'); ?>
                <article class="review-item">
                    <div class="review-avatar" aria-hidden="true"><?= e(strtoupper(substr($reviewName, 0, 1))); ?></div>
                    <div class="review-body">
                        <div class="review-meta"><strong><?= e($reviewName); ?></strong><span class="review-stars" aria-label="Rating <?= (int) $review['rating']; ?> dari 5">
                            <?php for ($star = 1; $star <= 5; $star++): ?><i class="fa-solid fa-star <?= $star <= (int) $review['rating'] ? 'is-on' : 'is-off'; ?>" aria-hidden="true"></i><?php endfor; ?>
                        </span></div>
                        <div class="review-product-name"><?= e($product['nama_produk']); ?></div>
                        <p><?= nl2br(e($review['ulasan'])); ?></p>
                    </div>
                    <time class="review-date" datetime="<?= e(date('Y-m-d', strtotime($review['created_at']))); ?>"><?= e(date('d-m-Y', strtotime($review['created_at']))); ?></time>
                </article>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="muted no-reviews">Belum ada ulasan. Jadilah yang pertama memberikan rating untuk produk ini.</p>
        <?php endif; ?>
    </div>
</section>

<script>
(function () {
    const input = document.getElementById('jumlah');
    const output = document.getElementById('jumlah-output');
    const totalLabel = document.getElementById('detail-total-label');
    if (input && output && totalLabel) {
        const unitPrice = <?= (int) $product['harga']; ?>;
        const max = Number(input.max);
        function updateQuantity(value) {
            const quantity = Math.min(max, Math.max(1, value));
            input.value = quantity;
            output.textContent = quantity;
            totalLabel.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(unitPrice * quantity);
        }
        document.querySelectorAll('[data-quantity-change]').forEach(function (button) {
            button.addEventListener('click', function () {
                updateQuantity(Number(input.value) + Number(button.dataset.quantityChange));
            });
        });
    }

    const tabButtons = document.querySelectorAll('[data-product-tab]');
    const tabPanels = document.querySelectorAll('[data-product-panel]');
    tabButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            const selected = button.dataset.productTab;
            tabButtons.forEach(function (tab) {
                const isActive = tab === button;
                tab.classList.toggle('active', isActive);
                tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
            });
            tabPanels.forEach(function (panel) {
                const isActive = panel.dataset.productPanel === selected;
                panel.hidden = !isActive;
                panel.classList.toggle('active', isActive);
            });
        });
    });
})();
</script>
<?php include APP_ROOT . '/resources/views/storefront/footer.php'; ?>
