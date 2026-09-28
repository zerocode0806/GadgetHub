<div class="page-title">
    <h1>Pesanan #<?= (int) $order['id_penjualan']; ?></h1>
    <div class="muted"><?= e(date('d F Y', strtotime($order['tanggal_penjualan']))); ?></div>
</div>

<div class="checkout-layout">
    <section class="panel">
        <h2>Produk</h2>
        <?php while ($item = mysqli_fetch_assoc($details)): ?>
            <div class="cart-total" style="border-top:0;margin:0;padding:12px 0;font-size:.95rem">
                <span><?= e($item['nama_produk'] ?: 'Produk'); ?> x <?= (int) $item['jumlah_produk']; ?></span>
                <strong><?= user_money($item['sub_total']); ?></strong>
            </div>
        <?php endwhile; ?>
        <div class="cart-total">
            <span>Total</span>
            <span><?= user_money($order['total_harga']); ?></span>
        </div>
    </section>

    <aside class="panel">
        <h2>Status pesanan</h2>
        <p>
            <span class="status <?= in_array($order['status'], ['Proses', 'Dibatalkan'], true) ? 'process' : ''; ?>">
                <?= e(user_order_status_label($order['status'])); ?>
            </span>
        </p>
        <p class="muted">Metode pembayaran: <strong><?= e($order['metode'] ?: 'Cash On Delivery (COD)'); ?></strong></p>

        <?php if ($show_demo_payment && $order['metode'] === 'QRIS'): ?>
            <div style="margin:20px 0;padding:20px;border:1px solid #e5e7eb;border-radius:12px;text-align:center">
                <h3 style="margin:0 0 8px">QRIS (Simulasi Demo)</h3>
                <p class="muted" style="margin:0 0 16px">Pindai kode ini untuk melihat referensi demo. Kode ini bukan QRIS pembayaran dan tidak memproses transaksi.</p>
                <div id="demo-qrcode" data-qr-text="<?= e($demo_qr_payload); ?>" style="display:inline-flex;min-width:220px;min-height:220px;align-items:center;justify-content:center;padding:10px;background:#fff;border:1px solid #eee;border-radius:8px"></div>
                <p class="muted" style="margin:12px 0 0">Referensi demo pesanan #<?= (int) $order['id_penjualan']; ?></p>
            </div>
            <script src="/js/qrcode.min.js"></script>
            <script>
                (function () {
                    var target = document.getElementById('demo-qrcode');
                    if (target && window.QRCode) {
                        new QRCode(target, {
                            text: target.getAttribute('data-qr-text'),
                            width: 200,
                            height: 200,
                            colorDark: '#111827',
                            colorLight: '#ffffff',
                            correctLevel: QRCode.CorrectLevel.M
                        });
                    } else if (target) {
                        target.textContent = 'Referensi demo pesanan #<?= (int) $order['id_penjualan']; ?>';
                    }
                }());
            </script>
        <?php elseif ($show_demo_payment && $order['metode'] === 'Bank'): ?>
            <div style="margin:20px 0;padding:20px;border:1px solid #e5e7eb;border-radius:12px">
                <h3 style="margin:0 0 8px">Virtual Account (Simulasi Demo)</h3>
                <p class="muted" style="margin:0 0 12px">Nomor berikut hanya nomor contoh untuk demo, bukan rekening pembayaran sungguhan.</p>
                <div style="padding:14px;background:#f6f7f8;border-radius:8px;font-size:1.25rem;font-weight:700;letter-spacing:.08em;text-align:center;overflow-wrap:anywhere">
                    <?= e($demo_virtual_account); ?>
                </div>
                <p class="muted" style="margin:12px 0 0">Tagihan: <strong><?= user_money($order['total_harga']); ?></strong></p>
            </div>
        <?php endif; ?>

        <hr>
        <h2>Alamat pengiriman</h2>
        <p>
            <strong><?= e($order['nama_pelanggan']); ?></strong><br>
            <?= nl2br(e($order['alamat'])); ?><br>
            <?= e($order['no_telepon']); ?>
        </p>
    </aside>
</div>

<?php include APP_ROOT . '/resources/views/storefront/footer.php'; ?>
