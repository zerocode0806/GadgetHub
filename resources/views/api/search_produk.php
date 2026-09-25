<?php if ($pro): while ($produk = mysqli_fetch_array($pro)): ?>
<div class="col-md-4 mb-4">
    <div class="card product-card">
        <img src="/uploads/<?= htmlspecialchars($produk['gambar_produk'], ENT_QUOTES, 'UTF-8'); ?>" class="card-img-top" alt="<?= htmlspecialchars($produk['nama_produk'], ENT_QUOTES, 'UTF-8'); ?>">
        <div class="product-card-body">
            <h5 class="product-title"><?= htmlspecialchars($produk['nama_produk'], ENT_QUOTES, 'UTF-8') . ' (Stok: ' . (int) $produk['stok'] . ')'; ?></h5>
            <p class="product-price">IDR <?= number_format((int) $produk['harga'], 0, ',', '.'); ?></p>
            <p class="product-description"><?= nl2br(htmlspecialchars($produk['deskripsi_produk'], ENT_QUOTES, 'UTF-8')); ?></p>
            <div class="quantity-control">
                <form method="POST" action="/api/products/search">
                    <input type="hidden" name="id_produk" value="<?= (int) $produk['id_produk']; ?>">
                    <input type="hidden" name="nama_produk" value="<?= htmlspecialchars($produk['nama_produk'], ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="harga" value="<?= (int) $produk['harga']; ?>">
                    <input type="hidden" name="stok" value="<?= (int) $produk['stok']; ?>">
                    <input type="number" class="form-control" name="jumlah" min="1" max="<?= (int) $produk['stok']; ?>" value="1">
                    <button type="submit" class="btn btn-secondary"><i class="fas fa-cart-plus" aria-hidden="true"></i> Tambah ke Keranjang</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endwhile; endif; ?>
