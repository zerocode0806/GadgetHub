<?php
$footerCategories = $site_settings['categories'] ?? [];
if (!$footerCategories) $footerCategories = ['Smartphone', 'Audio', 'Wearable', 'Kamera & Video'];
$footerPhone = preg_replace('/\D+/', '', (string) ($site_settings['phone'] ?? ''));
if (str_starts_with($footerPhone, '0')) $footerPhone = '62' . substr($footerPhone, 1);
$footerEmail = trim((string) ($site_settings['email'] ?? ''));
?>
</main>
<footer class="user-footer">
    <div class="footer-grid">
        <section class="footer-column">
            <h2>Kategori</h2>
            <?php foreach ($footerCategories as $footerCategory): ?>
                <a href="/products?kategori=<?= urlencode($footerCategory); ?>"><?= e($footerCategory); ?></a>
            <?php endforeach; ?>
        </section>
        <section class="footer-column">
            <h2>Dukungan</h2>
            <span>Panduan Pengguna</span>
            <?php if ($footerEmail !== ''): ?><a href="mailto:<?= e($footerEmail); ?>?subject=Pertanyaan%20GadgetHub">FAQ</a><?php else: ?><span>FAQ</span><?php endif; ?>
            <?php if ($footerPhone !== ''): ?><a href="https://wa.me/<?= e($footerPhone); ?>">Live Chat</a><?php else: ?><span>Live Chat</span><?php endif; ?>
        </section>
        <section class="footer-column">
            <h2>Layanan</h2>
            <?php if ($footerPhone !== ''): ?><a href="https://wa.me/<?= e($footerPhone); ?>?text=Informasi%20garansi">Garansi</a><a href="https://wa.me/<?= e($footerPhone); ?>?text=Informasi%20service">Service</a><?php else: ?><span>Garansi</span><span>Service</span><?php endif; ?>
        </section>
        <section class="footer-column">
            <h2>Tentang</h2>
            <span>Tentang Kami</span>
            <?php if ($footerEmail !== ''): ?><a href="mailto:<?= e($footerEmail); ?>?subject=Kebijakan%20Privasi">Kebijakan Privasi</a><?php else: ?><span>Kebijakan Privasi</span><?php endif; ?>
        </section>
        <section class="footer-column footer-contact">
            <h2>Ikuti Media Sosial Kami</h2>
            <div class="footer-social" aria-label="Ikuti media sosial kami">
                <span aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></span>
                <span aria-label="Instagram"><i class="fa-brands fa-instagram"></i></span>
                <span aria-label="YouTube"><i class="fa-brands fa-youtube"></i></span>
            </div>
            <hr>
            <h3>Customer Service</h3>
            <?php if ($footerPhone !== ''): ?>
                <a href="https://wa.me/<?= e($footerPhone); ?>"><i class="fa-brands fa-whatsapp"></i> Chat Langsung</a>
                <a href="tel:+<?= e($footerPhone); ?>"><i class="fa-solid fa-phone"></i> (+<?= e($footerPhone); ?>)</a>
            <?php else: ?>
                <span>Kontak layanan belum tersedia.</span>
            <?php endif; ?>
        </section>
    </div>
    <div class="footer-copyright">Copyright &copy; <?= date('Y'); ?> <?= e($site_name); ?>. All Rights Reserved.</div>
</footer>
<div class="toast" id="user-toast" role="status" aria-live="polite"></div>
<script>
function showUserToast(message, isError) {
    const toast = document.getElementById('user-toast');
    if (!toast) return;
    toast.textContent = message;
    toast.classList.toggle('error', Boolean(isError));
    toast.classList.add('show');
    window.clearTimeout(window.userToastTimer);
    window.userToastTimer = window.setTimeout(function () { toast.classList.remove('show'); }, 2800);
}

document.querySelectorAll('.ajax-cart-form').forEach(function (form) {
    form.addEventListener('submit', async function (event) {
        if (event.submitter && event.submitter.name === 'buy_now') return;
        event.preventDefault();
        const button = form.querySelector('button[type="submit"]');
        if (button) button.disabled = true;
        try {
            const response = await fetch(form.action, { method: 'POST', body: new FormData(form), headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const result = await response.json();
            if (!response.ok || !result.success) throw new Error(result.message || 'Produk gagal ditambahkan.');
            const cartCount = document.getElementById('cart-count');
            if (cartCount) { cartCount.textContent = result.cart_count; cartCount.hidden = result.cart_count < 1; }
            showUserToast(result.message, false);
        } catch (error) { showUserToast(error.message, true); }
        finally { if (button) button.disabled = false; }
    });
});

document.querySelectorAll('[data-confirm]').forEach(function (element) {
    element.addEventListener('click', function (event) {
        if (!window.confirm(element.dataset.confirm)) event.preventDefault();
    });
});
</script>
</body>
</html>
