<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4" style="color: #1d3557;">
            <i class="fas fa-user me-2"></i> Detail Pelanggan
        </h1>
        <a href="/admin/customers" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <!-- Customer Details Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="card-title mb-4">Informasi Pelanggan</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td width="150"><strong>Nama</strong></td>
                            <td>: <?= $pelanggan['nama_pelanggan'] ?></td>
                        </tr>
                        <tr>
                            <td><strong>Alamat</strong></td>
                            <td>: <?= $pelanggan['alamat'] ?></td>
                        </tr>
                        <tr>
                            <td><strong>No. Telepon</strong></td>
                            <td>: <?= $pelanggan['no_telepon'] ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5 class="card-title mb-4">Statistik Pembelian</h5>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2">Total Transaksi</h6>
                                    <h2 class="card-title mb-0"><?= $total_pembelian ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2">Total Nilai</h6>
                                    <h2 class="card-title mb-0">Rp <?= number_format($total_nilai, 0, ',', '.') ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Purchase History Card -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title">Riwayat Pembelian</h5>
                <form class="d-flex gap-2" method="GET" action="">
                    <input type="hidden" name="page" value="pelanggan_detail">
                    <input type="hidden" name="id" value="<?= $id_pelanggan ?>">
                    <input type="date" 
                           name="tanggal_awal" 
                           class="form-control form-control-sm" 
                           value="<?= $tanggal_awal ?>"
                           required>
                    <input type="date" 
                           name="tanggal_akhir" 
                           class="form-control form-control-sm" 
                           value="<?= $tanggal_akhir ?>"
                           required>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <?php if (!empty($tanggal_awal) && !empty($tanggal_akhir)): ?>
                    <a href="/admin/customers/detail?id=<?= $id_pelanggan ?>" 
                       class="btn btn-secondary btn-sm">
                        <i class="fas fa-times me-1"></i> Reset
                    </a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Add date range info if filter is active -->
            <?php if (!empty($tanggal_awal) && !empty($tanggal_akhir)): ?>
            <div class="alert alert-info alert-sm mb-4">
                <i class="fas fa-info-circle me-2"></i>
                Menampilkan data dari tanggal 
                <strong><?= date('d/m/Y', strtotime($tanggal_awal)) ?></strong> 
                sampai 
                <strong><?= date('d/m/Y', strtotime($tanggal_akhir)) ?></strong>
            </div>
            <?php endif; ?>

            <div class="row">
                <?php 
                if (mysqli_num_rows($query_pembelian) > 0):
                    while ($data = mysqli_fetch_array($query_pembelian)):
                ?>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="card-subtitle text-muted">
                                        <i class="far fa-calendar me-2"></i>
                                        <?= date('d/m/Y', strtotime($data['tanggal_penjualan'])); ?>
                                    </h6>
                                    <span class="badge bg-primary">#<?= $data['id_penjualan']; ?></span>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted">Kasir:</small>
                                    <div class="fw-bold"><?= $data['nama_kasir']; ?></div>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted">Jumlah Item:</small>
                                    <div class="fw-bold"><?= $data['total_items']; ?> item</div>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted">Total Pembelian:</small>
                                    <div class="h5 mb-0 text-success">
                                        Rp <?= number_format($data['total_harga'], 0, ',', '.'); ?>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <a href="/admin/sales/detail?&id=<?= $data['id_penjualan']; ?>" 
                                       class="btn btn-sm btn-info" 
                                       data-bs-toggle="tooltip" 
                                       title="Detail">
                                        <i class="fas fa-eye me-1"></i> Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php 
                    endwhile;
                else:
                ?>
                    <div class="col-12 text-center py-4">
                        <img src="/assets/img/no-data.png" alt="No Data" style="max-width: 200px;" class="mb-3">
                        <p class="text-muted">Belum ada riwayat pembelian</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>



<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
