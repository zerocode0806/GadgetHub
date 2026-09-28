<div class="container-fluid px-4 category-admin-page">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mt-4"><i class="fas fa-tags me-2"></i>Manajemen Kategori</h1>
            <p class="text-muted mb-0">Tambahkan kategori di sini; kategori baru langsung tersedia pada form produk dan filter storefront.</p>
        </div>
        <a href="/admin/products" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Produk</a>
    </div>

    <?php if ($categoryNotice !== ''): ?>
        <div class="alert alert-<?= htmlspecialchars($categoryNoticeType, ENT_QUOTES, 'UTF-8'); ?>" role="status"><?= htmlspecialchars($categoryNotice, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3"><h2 class="h5 mb-0">Tambah kategori</h2></div>
        <div class="card-body">
            <form method="post" action="/admin/categories" class="row g-3 align-items-end">
                <div class="col-md-8">
                    <label for="nama_kategori" class="form-label">Nama kategori</label>
                    <input id="nama_kategori" name="nama_kategori" type="text" class="form-control" maxlength="100" required placeholder="Contoh: Kamera & Video">
                </div>
                <div class="col-md-4"><button class="btn btn-primary w-100" type="submit"><i class="fas fa-plus me-2"></i>Simpan kategori</button></div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3"><h2 class="h5 mb-0">Kategori tersedia</h2></div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead><tr><th>Nama kategori</th><th class="text-end">Jumlah produk</th></tr></thead>
                <tbody>
                <?php if ($categoriesResult && mysqli_num_rows($categoriesResult) > 0): ?>
                    <?php while ($category = mysqli_fetch_assoc($categoriesResult)): ?>
                        <tr><td><span class="badge bg-light text-dark border"><?= htmlspecialchars($category['nama_kategori'], ENT_QUOTES, 'UTF-8'); ?></span></td><td class="text-end"><?= (int) $category['jumlah_produk']; ?></td></tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="2" class="text-center text-muted py-4">Belum ada kategori. Tambahkan kategori pertama di atas.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
