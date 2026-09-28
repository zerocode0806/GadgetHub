<div class="container-fluid px-4">
    <h1 class="mt-4 mb-4" style="color:#1d3557">Ringkasan Ecommerce</h1>
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6"><div class="card border-start border-5 border-primary shadow-sm h-100"><div class="card-body"><div class="text-muted small">Akun Pelanggan</div><div class="fs-4 fw-bold"><?= number_format($customer_count); ?></div><a href="/admin/customers" class="small text-decoration-none">Lihat pelanggan <i class="fas fa-arrow-right ms-1"></i></a></div></div></div>
        <div class="col-xl-3 col-md-6"><div class="card border-start border-5 border-success shadow-sm h-100"><div class="card-body"><div class="text-muted small">Produk</div><div class="fs-4 fw-bold"><?= number_format($product_count); ?></div><?php if ($is_admin): ?><a href="/admin/products" class="small text-decoration-none">Lihat produk <i class="fas fa-arrow-right ms-1"></i></a><?php endif; ?></div></div></div>
        <div class="col-xl-3 col-md-6"><div class="card border-start border-5 border-warning shadow-sm h-100"><div class="card-body"><div class="text-muted small">Pesanan</div><div class="fs-4 fw-bold"><?= number_format($sales_count); ?></div><a href="/admin/sales" class="small text-decoration-none">Lihat pesanan <i class="fas fa-arrow-right ms-1"></i></a></div></div></div>
        <div class="col-xl-3 col-md-6"><div class="card border-start border-5 border-info shadow-sm h-100"><div class="card-body"><div class="text-muted small">Total Nilai Pesanan</div><div class="fs-4 fw-bold">Rp <?= number_format((float) $total_sales, 0, ',', '.'); ?></div><a href="/admin/sales" class="small text-decoration-none">Lihat pesanan <i class="fas fa-arrow-right ms-1"></i></a></div></div></div>
    </div>

    <div class="row g-3">
        <div class="col-xl-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center"><strong><i class="fas fa-store me-2"></i>Informasi Toko</strong><?php if ($is_admin): ?><a class="btn btn-sm btn-primary" href="/admin/settings"><i class="fas fa-edit me-1"></i>Edit</a><?php endif; ?></div>
                <div class="card-body"><div class="row g-3 align-items-center">
                    <div class="col-md-8"><dl class="row mb-0">
                        <dt class="col-sm-4">Nama Toko</dt><dd class="col-sm-8"><?= htmlspecialchars($settings['business_name'] ?? '—', ENT_QUOTES, 'UTF-8'); ?></dd>
                        <dt class="col-sm-4">Alamat</dt><dd class="col-sm-8"><?= htmlspecialchars($settings['address'] ?? '—', ENT_QUOTES, 'UTF-8'); ?></dd>
                        <dt class="col-sm-4">Telepon</dt><dd class="col-sm-8"><?= htmlspecialchars($settings['phone'] ?? '—', ENT_QUOTES, 'UTF-8'); ?></dd>
                    </dl></div>
                    <?php if (!empty($settings['logo'])): ?><div class="col-md-4 text-center"><img src="/uploads/<?= rawurlencode(basename($settings['logo'])); ?>" alt="Logo toko" class="img-fluid rounded" style="max-height:130px"></div><?php endif; ?>
                </div></div>
            </div>
        </div>
        <?php if ($is_admin): ?><div class="col-xl-4"><div class="card shadow-sm border-0 h-100"><div class="card-header bg-white"><strong><i class="fas fa-bolt me-2"></i>Aksi Cepat</strong></div><div class="card-body"><div class="list-group">
            <a href="/admin/products/create" class="list-group-item list-group-item-action"><i class="fas fa-box me-2"></i>Tambah Produk</a>
            <a href="/admin/customers/create" class="list-group-item list-group-item-action"><i class="fas fa-user-plus me-2"></i>Tambah Akun Pelanggan</a>
            <a href="/admin/users/create" class="list-group-item list-group-item-action"><i class="fas fa-user-shield me-2"></i>Tambah Akun Staf</a>
            <a href="/admin/settings" class="list-group-item list-group-item-action"><i class="fas fa-cog me-2"></i>Pengaturan Toko</a>
        </div></div></div></div><?php endif; ?>
    </div>
</div>
