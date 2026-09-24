<?php
require_once APP_ROOT . '/app/Support/user_helpers.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) user_redirect('user_produk.php');
$stmt = mysqli_prepare($koneksi, "SELECT * FROM produk WHERE id_produk = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$product = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$product) { http_response_code(404); exit('Produk tidak ditemukan.'); }
$page_title = $product['nama_produk'];
$active_page = 'produk';
include APP_ROOT . '/resources/views/storefront/header.php';
?>
<a class="btn btn-light" href="user_produk.php" style="margin-bottom:22px"><i class="fa-solid fa-arrow-left"></i>&nbsp; Kembali</a>
<div class="detail-layout"><div><img class="detail-image" src="<?= e(user_image($product['gambar_produk'])); ?>" alt="<?= e($product['nama_produk']); ?>"></div><div class="detail-copy"><div class="muted">Informasi produk</div><h1><?= e($product['nama_produk']); ?></h1><div class="price">Harga satuan: <?= user_money($product['harga']); ?></div><p class="muted"><?= nl2br(e($product['deskripsi_produk'] ?: 'Produk pilihan dari toko kami.')); ?></p><p class="stock <?= (int) $product['stok'] < 1 ? 'out' : ''; ?>"><?= (int) $product['stok'] > 0 ? 'Tersedia ' . (int) $product['stok'] . ' unit' : 'Stok habis'; ?></p><?php if ((int) $product['stok'] > 0): ?><form class="ajax-cart-form" method="post" action="keranjang_tambah.php"><input type="hidden" name="csrf_token" value="<?= e(user_csrf_token()); ?>"><input type="hidden" name="id_produk" value="<?= (int) $product['id_produk']; ?>"><div class="quantity"><label>Jumlah</label><div class="quantity-control"><button type="button" data-quantity-change="-1" aria-label="Kurangi jumlah">-</button><output id="jumlah-output">1</output><button type="button" data-quantity-change="1" aria-label="Tambah jumlah">+</button></div><input id="jumlah" name="jumlah" type="hidden" value="1" min="1" max="<?= (int) $product['stok']; ?>"></div><p class="muted">Total: <strong id="detail-total-label"><?= user_money($product['harga']); ?></strong></p><button class="btn btn-primary btn-block" type="submit"><i class="fa-solid fa-cart-plus"></i>&nbsp; Tambah ke keranjang</button></form><?php else: ?><button class="btn btn-light btn-block" disabled>Stok habis</button><?php endif; ?></div></div>
<script>
(function () {
	const input = document.getElementById('jumlah');
	const output = document.getElementById('jumlah-output');
	const totalLabel = document.getElementById('detail-total-label');
	if (!input || !output || !totalLabel) return;
	const unitPrice = <?= (int) $product['harga']; ?>;
	const max = Number(input.max);
	function updateQuantity(value) {
		const quantity = Math.min(max, Math.max(1, value));
		input.value = quantity;
		output.textContent = quantity;
		totalLabel.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(unitPrice * quantity);
	}
	document.querySelectorAll('[data-quantity-change]').forEach(function (button) { button.addEventListener('click', function () { updateQuantity(Number(input.value) + Number(button.dataset.quantityChange)); }); });
})();
</script>
<?php include APP_ROOT . '/resources/views/storefront/footer.php'; ?>
