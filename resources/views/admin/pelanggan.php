<div class="container-fluid px-4">
    <?php if ($admin_flash): ?>
        <div class="alert alert-<?= $admin_flash['type'] === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($admin_flash['message'], ENT_QUOTES, 'UTF-8'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="mt-4" style="color:#1d3557"><i class="fas fa-users me-2"></i>Pelanggan</h1>
            <p class="text-muted mb-0">Satu akun pelanggan ditampilkan sekali. Riwayat pesanan dan total belanja digabung berdasarkan akun.</p>
        </div>
        <a href="/admin/customers/create" class="btn btn-primary"><i class="fas fa-user-plus me-2"></i>Tambah Akun Pelanggan</a>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="/admin/customers" method="get" class="row g-3 align-items-center">
                <div class="col-md-9">
                    <input type="search" name="search" class="form-control" placeholder="Cari nama, username, atau nomor telepon" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>">
                </div>
                <div class="col-md-3"><button class="btn btn-primary w-100" type="submit"><i class="fas fa-search me-2"></i>Cari</button></div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr><th>Pelanggan</th><th>Jenis</th><th>Kontak</th><th class="text-center">Pesanan</th><th class="text-end">Total Belanja</th><th class="text-end">Aksi</th></tr>
                    </thead>
                    <tbody>
                    <?php if (!$customers): ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data pelanggan.</td></tr>
                    <?php else: foreach ($customers as $customer): ?>
                        <?php $isAccount = $customer['customer_type'] === 'account'; $lockedGuest = !$isAccount && (int) $customer['total_pembelian'] > 0; ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($customer['nama_pelanggan'] ?: 'Tanpa nama', ENT_QUOTES, 'UTF-8'); ?></strong>
                                <?php if ($isAccount): ?><div class="small text-muted">@<?= htmlspecialchars($customer['username'], ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
                            </td>
                            <td><span class="badge <?= $isAccount ? 'bg-primary' : 'bg-secondary'; ?>"><?= $isAccount ? 'Akun storefront' : 'Data tanpa akun'; ?></span></td>
                            <td>
                                <?= htmlspecialchars($customer['no_telepon'] ?: '—', ENT_QUOTES, 'UTF-8'); ?>
                                <?php if (!empty($customer['alamat'])): ?><div class="small text-muted text-truncate" style="max-width:260px"><?= htmlspecialchars($customer['alamat'], ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
                            </td>
                            <td class="text-center"><?= (int) $customer['total_pembelian']; ?></td>
                            <td class="text-end">Rp <?= number_format((float) $customer['total_harga'], 0, ',', '.'); ?></td>
                            <td>
                                <div class="d-flex justify-content-end gap-1">
                                    <a class="btn btn-sm btn-outline-info" href="/admin/customers/detail?id=<?= (int) $customer['customer_id']; ?>&type=<?= $isAccount ? 'account' : 'guest'; ?>" title="Detail pesanan"><i class="fas fa-eye"></i></a>
                                    <?php if (!$lockedGuest): ?><a class="btn btn-sm btn-outline-primary" href="/admin/customers/edit?id=<?= (int) $customer['customer_id']; ?>&type=<?= $isAccount ? 'account' : 'guest'; ?>" title="Edit"><i class="fas fa-edit"></i></a><?php else: ?><button class="btn btn-sm btn-outline-secondary" type="button" disabled title="Data snapshot pesanan lama"><i class="fas fa-lock"></i></button><?php endif; ?>
                                    <form method="post" action="/admin/customers/delete" onsubmit="return confirm('Hapus data pelanggan ini? Data dengan transaksi atau ulasan tidak dapat dihapus.');">
                                        <input type="hidden" name="admin_csrf" value="<?= htmlspecialchars(admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="id" value="<?= (int) $customer['customer_id']; ?>">
                                        <input type="hidden" name="type" value="<?= $isAccount ? 'account' : 'guest'; ?>">
                                        <button class="btn btn-sm btn-outline-danger" type="submit" title="Hapus" <?= $lockedGuest ? 'disabled' : ''; ?>><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
            <p class="small text-muted mt-3 mb-0">Akun pelanggan yang mempunyai pesanan atau ulasan tidak dapat dihapus agar data riwayat tetap utuh. Alamat yang tersimpan di pesanan lama tidak berubah saat profil diedit.</p>
        </div>
    </div>
</div>
