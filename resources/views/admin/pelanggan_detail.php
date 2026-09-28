<div class="container-fluid px-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="mt-4" style="color:#1d3557"><i class="fas fa-user me-2"></i>Detail Pelanggan</h1>
            <span class="badge <?= $is_account ? 'bg-primary' : 'bg-secondary'; ?>"><?= $is_account ? 'Akun storefront' : 'Data tanpa akun'; ?></span>
        </div>
        <div class="d-flex gap-2">
            <?php if ($is_account || !$customer['has_orders']): ?><a href="/admin/customers/edit?id=<?= (int) $customer['id']; ?>&type=<?= htmlspecialchars($customer_type, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-outline-primary"><i class="fas fa-edit me-1"></i>Edit</a><?php endif; ?>
            <a href="/admin/customers" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i>Kembali</a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 h-100"><div class="card-body">
                <h5 class="card-title mb-3">Profil pelanggan</h5>
                <dl class="row mb-0">
                    <dt class="col-sm-4">Nama</dt><dd class="col-sm-8"><?= htmlspecialchars($customer['nama'] ?: '—', ENT_QUOTES, 'UTF-8'); ?></dd>
                    <?php if ($is_account): ?><dt class="col-sm-4">Username</dt><dd class="col-sm-8">@<?= htmlspecialchars($customer['username'], ENT_QUOTES, 'UTF-8'); ?></dd><?php endif; ?>
                    <dt class="col-sm-4">Telepon</dt><dd class="col-sm-8"><?= htmlspecialchars($customer['no_telepon'] ?: '—', ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt class="col-sm-4">Alamat profil</dt><dd class="col-sm-8"><?= nl2br(htmlspecialchars($customer['alamat'] ?: '—', ENT_QUOTES, 'UTF-8')); ?></dd>
                </dl>
            </div></div>
        </div>
        <div class="col-lg-7">
            <div class="row g-3 h-100">
                <div class="col-sm-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="text-muted">Total pesanan</div><div class="display-6 fw-bold text-primary"><?= number_format($total_pembelian); ?></div></div></div></div>
                <div class="col-sm-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="text-muted">Total nilai pesanan</div><div class="display-6 fw-bold text-success">Rp <?= number_format($total_nilai, 0, ',', '.'); ?></div></div></div></div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                <h5 class="mb-0">Riwayat pesanan</h5>
                <form method="get" action="/admin/customers/detail" class="d-flex flex-wrap gap-2">
                    <input type="hidden" name="id" value="<?= (int) $customer['id']; ?>">
                    <input type="hidden" name="type" value="<?= htmlspecialchars($customer_type, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="date" name="tanggal_awal" class="form-control form-control-sm" aria-label="Tanggal awal" value="<?= htmlspecialchars($tanggal_awal, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="date" name="tanggal_akhir" class="form-control form-control-sm" aria-label="Tanggal akhir" value="<?= htmlspecialchars($tanggal_akhir, ENT_QUOTES, 'UTF-8'); ?>">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter me-1"></i>Filter</button>
                    <?php if ($tanggal_awal !== ''): ?><a class="btn btn-outline-secondary btn-sm" href="/admin/customers/detail?id=<?= (int) $customer['id']; ?>&type=<?= htmlspecialchars($customer_type, ENT_QUOTES, 'UTF-8'); ?>">Reset</a><?php endif; ?>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light"><tr><th>Pesanan</th><th>Tanggal</th><th>Pemroses</th><th>Metode</th><th>Item</th><th>Status</th><th class="text-end">Total</th><th></th></tr></thead>
                    <tbody>
                    <?php if (mysqli_num_rows($query_pembelian) === 0): ?>
                        <tr><td colspan="8" class="text-center text-muted py-4">Belum ada pesanan pada rentang tanggal ini.</td></tr>
                    <?php else: while ($order = mysqli_fetch_assoc($query_pembelian)): ?>
                        <tr>
                            <td>#<?= (int) $order['id_penjualan']; ?></td>
                            <td><?= $order['tanggal_penjualan'] ? date('d/m/Y', strtotime($order['tanggal_penjualan'])) : '—'; ?></td>
                            <td><?= htmlspecialchars($order['nama_kasir'] ?: '—', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?= htmlspecialchars($order['metode'] ?: '—', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?= (int) $order['total_items']; ?></td>
                            <td><span class="badge bg-<?= $order['status'] === 'Selesai' ? 'success' : ($order['status'] === 'Dibatalkan' ? 'danger' : 'warning text-dark'); ?>"><?= htmlspecialchars($order['status'] ?: 'Proses', ENT_QUOTES, 'UTF-8'); ?></span></td>
                            <td class="text-end">Rp <?= number_format((float) $order['total_harga'], 0, ',', '.'); ?></td>
                            <td><a class="btn btn-sm btn-outline-info" href="/admin/sales/detail?id=<?= (int) $order['id_penjualan']; ?>">Detail</a></td>
                        </tr>
                    <?php endwhile; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
