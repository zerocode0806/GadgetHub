<div class="container-fluid px-4">
    <?php if ($admin_flash): ?><div class="alert alert-<?= $admin_flash['type'] === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert"><?= htmlspecialchars($admin_flash['message'], ENT_QUOTES, 'UTF-8'); ?><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button></div><?php endif; ?>
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div><h1 class="mt-4" style="color:#1d3557"><i class="fas fa-box-open me-2"></i>Pesanan</h1><p class="text-muted mb-0">Kelola pesanan storefront dan perbarui status pemrosesan.</p></div>
        <?php if ($level === 'admin'): ?><a href="/admin/export" class="btn btn-success"><i class="fas fa-file-excel me-2"></i>Export Excel</a><?php endif; ?>
    </div>
    <div class="card shadow-sm border-0 mb-4"><div class="card-body">
        <form action="/admin/sales" method="get" class="row g-3 align-items-center">
            <div class="col-md-9"><input type="search" name="search" class="form-control" placeholder="Cari nomor pesanan, pelanggan, tanggal, metode, atau status" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>"></div>
            <div class="col-md-3"><button class="btn btn-primary w-100" type="submit"><i class="fas fa-search me-2"></i>Cari Pesanan</button></div>
        </form>
    </div></div>

    <div class="card shadow-sm border-0"><div class="card-body"><div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Pesanan</th><th>Tanggal</th><th>Pelanggan</th><th>Metode pembayaran</th><th class="text-end">Total</th><th>Status &amp; pemrosesan</th><th class="text-center">Detail</th></tr></thead>
            <tbody>
            <?php if (!$query || mysqli_num_rows($query) === 0): ?><tr><td colspan="7" class="text-center text-muted py-4">Tidak ada pesanan yang cocok dengan pencarian.</td></tr>
            <?php else: while ($order = mysqli_fetch_assoc($query)): ?>
                <?php $status = $order['status'] ?: 'Proses'; ?>
                <tr>
                    <td><strong>#<?= (int) $order['id_penjualan']; ?></strong></td>
                    <td><?= $order['tanggal_penjualan'] ? date('d/m/Y', strtotime($order['tanggal_penjualan'])) : '—'; ?></td>
                    <td><?= htmlspecialchars($order['nama_pelanggan'] ?: 'Pelanggan umum', ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?= htmlspecialchars($order['metode'] ?: '—', ENT_QUOTES, 'UTF-8'); ?></td>
                    <td class="text-end">Rp <?= number_format((float) $order['total_harga'], 0, ',', '.'); ?></td>
                    <td>
                        <form method="post" action="/admin/sales/status" class="d-flex flex-wrap align-items-center gap-2">
                            <input type="hidden" name="admin_csrf" value="<?= htmlspecialchars($admin_csrf, ENT_QUOTES, 'UTF-8'); ?>">
                            <input type="hidden" name="id_penjualan" value="<?= (int) $order['id_penjualan']; ?>">
                            <select class="form-select form-select-sm" name="status" aria-label="Status pesanan #<?= (int) $order['id_penjualan']; ?>" style="max-width:150px">
                                <option value="Proses" <?= $status === 'Proses' || $status === '' ? 'selected' : ''; ?>>Diproses</option>
                                <option value="Dikirim" <?= $status === 'Dikirim' ? 'selected' : ''; ?>>Dikirim</option>
                                <option value="Selesai" <?= $status === 'Selesai' || $status === 'Selsesai' ? 'selected' : ''; ?>>Selesai</option>
                                <option value="Dibatalkan" <?= $status === 'Dibatalkan' ? 'selected' : ''; ?>>Dibatalkan</option>
                            </select>
                            <button class="btn btn-sm btn-primary" type="submit">Simpan</button>
                        </form>
                    </td>
                    <td class="text-center"><a class="btn btn-sm btn-outline-info" href="/admin/sales/detail?id=<?= (int) $order['id_penjualan']; ?>" aria-label="Detail pesanan #<?= (int) $order['id_penjualan']; ?>"><i class="fas fa-eye"></i></a></td>
                </tr>
            <?php endwhile; endif; ?>
            </tbody>
        </table>
    </div>
    <?php if ($jumlahHalaman > 1): ?><nav class="mt-4" aria-label="Halaman pesanan"><ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= $jumlahHalaman; $i++): ?><li class="page-item <?= $i === $halamanAktif ? 'active' : ''; ?>"><a class="page-link" href="/admin/sales?halaman=<?= $i; ?>&amp;search=<?= urlencode($search); ?>"><?= $i; ?></a></li><?php endfor; ?>
    </ul></nav><?php endif; ?>
    </div></div>
</div>
