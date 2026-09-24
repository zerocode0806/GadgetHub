</main>
<footer class="user-footer"><?= e($site_name); ?> &copy; <?= date('Y'); ?>. Belanja praktis, transaksi tercatat.</footer>
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
