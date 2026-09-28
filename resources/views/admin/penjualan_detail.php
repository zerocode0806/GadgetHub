<div class="container-fluid px-4">
    <div class="page-header mb-4">
        <h1 class="fw-bold">
            <i class="fas fa-receipt"></i> Detail Pesanan
            <span class="fs-5 text-muted">#<?php echo $id; ?></span>
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="/admin/sales">Daftar Transaksi</a></li>
                <li class="breadcrumb-item active">Detail Transaksi</li>
            </ol>
        </nav>
    </div>

    <div class="row g-4">
        <!-- Transaction Info -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Informasi Pesanan</h5>
                        <div class="transaction-date text-muted">
                            <i class="far fa-calendar-alt"></i>
                            <?php echo date('d F Y', strtotime($data['tanggal_penjualan'])); ?>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="text-muted mb-1">Pelanggan</label>
                                <h6 class="mb-0">
                                    <i class="fas fa-user text-primary"></i>
                                    <?php echo htmlspecialchars($data['nama_pelanggan'] ?: 'Pelanggan umum', ENT_QUOTES, 'UTF-8'); ?>
                                </h6>
                                <div class="small text-muted mt-2"><?= htmlspecialchars($data['telepon_pelanggan'] ?: 'Nomor telepon tidak tersedia', ENT_QUOTES, 'UTF-8'); ?></div>
                                <div class="small text-muted"><?= nl2br(htmlspecialchars($data['alamat_pelanggan'] ?: 'Alamat tidak tersedia', ENT_QUOTES, 'UTF-8')); ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="text-muted mb-1">Status Pesanan</label>
                                <h6 class="mb-0"><?= htmlspecialchars($data['status'] ?: 'Proses', ENT_QUOTES, 'UTF-8'); ?></h6>
                                <div class="small text-muted mt-2"><?= htmlspecialchars($data['level_pembuat'] === 'user' ? 'Pesanan dari akun storefront' : 'Dicatat oleh petugas', ENT_QUOTES, 'UTF-8'); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Table -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">Detail Produk</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end">Harga Satuan</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($produk = mysqli_fetch_array($pro)) {
                                    $harga_satuan = $produk['sub_total'] / $produk['jumlah_produk'];
                                ?>
                                <tr>
                                    <td>
                                        <h6 class="mb-0"><?php echo $produk['nama_produk']; ?></h6>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark">
                                            <?php echo number_format($produk['jumlah_produk'], 0, ',', '.'); ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        Rp <?php echo number_format($harga_satuan, 0, ',', '.'); ?>
                                    </td>
                                    <td class="text-end fw-bold">
                                        Rp <?php echo number_format($produk['sub_total'], 0, ',', '.'); ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">Ringkasan Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="payment-details">
                        <div class="payment-item d-flex justify-content-between mb-3">
                            <span class="text-muted">Total Pembelian</span>
                            <span class="fw-bold">Rp <?php echo number_format($data['total_harga'], 0, ',', '.'); ?></span>
                        </div>
                        <div class="payment-item d-flex justify-content-between mb-3">
                            <span class="text-muted">Metode pembayaran</span>
                            <span class="fw-bold"><?php echo htmlspecialchars($data['metode'] ?: '—', ENT_QUOTES, 'UTF-8'); ?></span>
                        </div>
                        <div class="payment-item d-flex justify-content-between">
                            <span class="text-muted">Status</span>
                            <span class="fw-bold"><?php echo htmlspecialchars($data['status'] ?: 'Proses', ENT_QUOTES, 'UTF-8'); ?></span>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-grid gap-2">
                        <a href="/receipt?id=<?php echo $id; ?>" target="_blank" 
                           class="btn btn-primary btn-lg">
                            <i class="fas fa-print me-2"></i>
                            Cetak Struk
                        </a>
                        <a href="/admin/sales" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.page-header h1 {
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.breadcrumb {
    background: transparent;
    padding: 0;
    margin: 0;
}

.breadcrumb-item a {
    color: #3498db;
    text-decoration: none;
}

.card {
    border-radius: 10px;
    overflow: hidden;
}

.card-header {
    border-bottom: 1px solid rgba(0,0,0,0.08);
}

.transaction-date {
    font-size: 0.9rem;
}

.info-group {
    padding: 1rem;
    background-color: #f8f9fa;
    border-radius: 8px;
}

.info-group label {
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table {
    margin-bottom: 0;
}

.table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}

.table td {
    vertical-align: middle;
}

.badge {
    padding: 0.5em 1em;
    font-weight: 500;
}

.payment-details {
    font-size: 1.1rem;
}

.payment-item {
    padding: 0.5rem 0;
}

.btn-primary {
    background: linear-gradient(to right, #3498db, #2980b9);
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(to right, #2980b9, #2573a7);
    transform: translateY(-1px);
}

.btn-outline-secondary {
    border-color: #dee2e6;
}

.btn-outline-secondary:hover {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    color: #2c3e50;
}

@media (max-width: 768px) {
    .sticky-top {
        position: relative;
        top: 0 !important;
    }
    
    .payment-details {
        font-size: 1rem;
    }
}
</style>
