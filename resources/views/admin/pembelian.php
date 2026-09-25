<div class="container-fluid px-4">
    <?php if ($admin_flash): ?><div class="alert alert-<?= $admin_flash['type'] === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert"><i class="fas fa-<?= $admin_flash['type'] === 'success' ? 'check' : 'triangle-exclamation'; ?> me-2"></i><?= htmlspecialchars($admin_flash['message'], ENT_QUOTES, 'UTF-8'); ?><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button></div><?php endif; ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4" style="color: #1d3557;">
            <i class="fas fa-shopping-cart me-2"></i> Data Pembelian
        </h1>
        <div>
            <?php if ($level == 'admin'): ?>
                <a href="/admin/export" class="btn btn-success me-2">
                    <i class="fas fa-file-excel me-2"></i>Export Excel
                </a>
            <?php endif; ?>
            <a href="/admin/sales/create" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i>Tambah Pembelian
            </a>
        </div>
    </div>

    <!-- Search and Filter Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="" method="GET" class="row g-3 align-items-center">
                <input type="hidden" name="page" value="pembelian">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Cari berdasarkan kasir atau nama pelanggan..." 
                               value="<?= $search; ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-primary w-100" type="submit">
                        <i class="fas fa-search me-2"></i>Cari Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Purchases List -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">#</th>
                            <th>Tanggal</th>
                            <th>Kasir</th>
                            <th>Pelanggan</th>
                            <th class="text-end">Total</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = $awalData + 1;
                        if (mysqli_num_rows($query) > 0):
                            while ($data = mysqli_fetch_array($query)):
                        ?>
                            <tr>
                                <td class="text-center"><?= $no++; ?></td>
                                <td><?= date('d/m/Y', strtotime($data['tanggal_penjualan'])); ?></td>
                                <td><?= $data['nama_kasir']; ?></td>
                                <td><?= $data['nama_pelanggan'] ?? 'Umum'; ?></td>
                                <td class="text-end">Rp <?= number_format($data['total_harga'], 0, ',', '.'); ?></td>
                                <td class="text-center">
                                    <?php if ($level === 'admin'): ?><form method="post" action="/admin/sales/status" class="d-inline-flex align-items-center gap-1"><input type="hidden" name="admin_csrf" value="<?= htmlspecialchars($_SESSION['admin_csrf'], ENT_QUOTES, 'UTF-8'); ?>"><input type="hidden" name="id_penjualan" value="<?= (int) $data['id_penjualan']; ?>"><select class="form-select form-select-sm" name="status" aria-label="Status pesanan #<?= (int) $data['id_penjualan']; ?>"><option value="Proses" <?= in_array($data['status'], ['Proses', ''], true) ? 'selected' : ''; ?>>Diproses</option><option value="Dikirim" <?= $data['status'] === 'Dikirim' ? 'selected' : ''; ?>>Dikirim</option><option value="Selesai" <?= in_array($data['status'], ['Selesai', 'Selsesai'], true) ? 'selected' : ''; ?>>Selesai</option><option value="Dibatalkan" <?= $data['status'] === 'Dibatalkan' ? 'selected' : ''; ?>>Dibatalkan</option></select><button class="btn btn-sm btn-primary" type="submit">Simpan</button></form><?php else: ?><span class="badge bg-success"><?= htmlspecialchars($data['status'] ?: 'Proses', ENT_QUOTES, 'UTF-8'); ?></span><?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="/admin/sales/detail?&id=<?= $data['id_penjualan']; ?>" 
                                       class="btn btn-sm btn-info me-1" 
                                       data-bs-toggle="tooltip" 
                                       title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="/admin/sales/delete?&id=<?= $data['id_penjualan']; ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Yakin ingin menghapus data ini?')"
                                       data-bs-toggle="tooltip" 
                                       title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php 
                            endwhile;
                        else:
                        ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <img src="/assets/img/no-data.png" alt="No Data" style="max-width: 200px;" class="mb-3">
                                    <p class="text-muted">Tidak ada data pembelian</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($jumlahHalaman > 1): ?>
                <nav class="mt-4" aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php if ($halamanAktif > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="/admin/sales?halaman=<?= $halamanAktif - 1 ?>&search=<?= $search ?>">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $jumlahHalaman; $i++): ?>
                            <li class="page-item <?= ($i == $halamanAktif) ? 'active' : '' ?>">
                                <a class="page-link" href="/admin/sales?halaman=<?= $i; ?>&search=<?= $search; ?>">
                                    <?= $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($halamanAktif < $jumlahHalaman): ?>
                            <li class="page-item">
                                <a class="page-link" href="/admin/sales?halaman=<?= $halamanAktif + 1 ?>&search=<?= $search ?>">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 10px;
    }
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    .page-link {
        color: #1d3557;
    }
    .page-item.active .page-link {
        background-color: #1d3557;
        border-color: #1d3557;
    }
    .btn-primary {
        background-color: #1d3557;
        border-color: #1d3557;
    }
    .btn-primary:hover {
        background-color: #152640;
        border-color: #152640;
    }
    .badge {
        padding: 0.5em 0.8em;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
    }
    .input-group-text {
        background-color: #f8f9fa;
    }
    @media (max-width: 768px) {
        .btn-sm {
            padding: 0.25rem 0.4rem;
        }
        .table {
            font-size: 0.9rem;
        }
    }
</style>

<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>

