<div class="page-title"><h1>Keranjang kamu</h1><div class="muted">Harga dan stok akan diverifikasi kembali saat checkout.</div></div>
<?php if (!$items): ?><div class="panel"><p>Keranjang kamu masih kosong.</p><a class="btn btn-primary" href="/products">Mulai belanja</a></div><?php else: ?><div class="checkout-layout"><section class="panel"><h2>Produk pilihanmu</h2><div id="cart-items"><?php foreach ($items as $item): ?><article class="cart-row" data-cart-item data-product-id="<?= (int) $item['id_produk']; ?>" data-unit-price="<?= (int) $item['harga']; ?>" data-max-stock="<?= (int) $item['stok']; ?>"><img class="cart-thumb" src="<?= e(user_image($item['gambar_produk'])); ?>" alt="<?= e($item['nama_produk']); ?>"><div class="cart-product"><h3><?= e($item['nama_produk']); ?></h3><small><?= user_money($item['harga']); ?> / unit</small><div class="cart-subtotal">Subtotal: <strong data-subtotal><?= user_money($item['subtotal']); ?></strong></div></div><div class="quantity cart-quantity"><span class="quantity-label">Quantity</span><div class="quantity-control"><button type="button" data-cart-change="-1" aria-label="Kurangi jumlah <?= e($item['nama_produk']); ?>">-</button><output data-quantity><?= (int) $item['jumlah']; ?></output><button type="button" data-cart-change="1" aria-label="Tambah jumlah <?= e($item['nama_produk']); ?>">+</button></div></div><strong class="line-price" data-subtotal><?= user_money($item['subtotal']); ?></strong><form class="remove" method="post" action="/cart/remove"><input type="hidden" name="csrf_token" value="<?= e(user_csrf_token()); ?>"><input type="hidden" name="id_produk" value="<?= (int) $item['id_produk']; ?>"><button class="btn btn-danger" type="submit" aria-label="Hapus produk"><i class="fa-solid fa-trash"></i></button></form></article><?php endforeach; ?></div></section><aside class="panel cart-summary"><h2>Ringkasan</h2><div class="cart-total"><span>Total</span><span id="cart-total"><?= user_money($total); ?></span></div><a class="btn btn-primary btn-block" style="margin-top:20px" href="/checkout/customer">Lanjut checkout <i class="fa-solid fa-arrow-right" style="margin-left:8px"></i></a></aside></div><?php endif; ?>
<script>
(function () {
    const formatter = new Intl.NumberFormat('id-ID');
    const csrf = '<?= e(user_csrf_token()); ?>';
    const totalElement = document.getElementById('cart-total');
    const items = Array.from(document.querySelectorAll('[data-cart-item]'));
    if (!totalElement || !items.length) return;

    function money(value) { return 'Rp ' + formatter.format(value); }
    function refreshTotal() {
        let total = 0;
        items.forEach(function (item) { total += Number(item.dataset.unitPrice) * Number(item.querySelector('[data-quantity]').textContent); });
        totalElement.textContent = money(total);
    }
    function setBusy(item, busy) { item.querySelectorAll('button').forEach(function (button) { button.disabled = busy; }); }
    function updateItem(item, quantity) {
        const output = item.querySelector('[data-quantity]');
        const subtotalElements = item.querySelectorAll('[data-subtotal]');
        const unitPrice = Number(item.dataset.unitPrice);
        output.textContent = quantity;
        subtotalElements.forEach(function (element) { element.textContent = money(unitPrice * quantity); });
        refreshTotal();
    }
    items.forEach(function (item) {
        item.querySelectorAll('[data-cart-change]').forEach(function (button) {
            button.addEventListener('click', async function () {
                const output = item.querySelector('[data-quantity]');
                const current = Number(output.textContent);
                const max = Number(item.dataset.maxStock);
                const next = Math.min(max, Math.max(1, current + Number(button.dataset.cartChange)));
                if (next === current || max < 1) return;
                setBusy(item, true);
                const body = new FormData(); body.append('csrf_token', csrf); body.append('id_produk', item.dataset.productId); body.append('jumlah', next);
                try {
                    const response = await fetch('/cart/update', { method: 'POST', body: body, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    const result = await response.json();
                    if (!response.ok || !result.success) throw new Error(result.message || 'Keranjang gagal diperbarui.');
                    updateItem(item, result.quantity); if (result.total !== undefined) totalElement.textContent = money(result.total); showUserToast(result.message, false);
                } catch (error) { showUserToast(error.message, true); }
                finally { setBusy(item, false); }
            });
        });
    });
})();
</script>
<?php include APP_ROOT . '/resources/views/storefront/footer.php'; ?>
