<?php 
    // Mendapatkan id penjualan dari parameter URL
    $id = $_GET['id'];

    // Query untuk mengambil data penjualan, kasir, dan pelanggan
    $query = mysqli_query($koneksi, "
        SELECT penjualan.*, user.nama AS nama_kasir, pelanggan.nama_pelanggan 
        FROM penjualan 
        LEFT JOIN user ON user.id_user = penjualan.id_kasir 
        LEFT JOIN pelanggan ON pelanggan.id_pelanggan = penjualan.id_pelanggan 
        WHERE id_penjualan=$id
    ");
    $data = mysqli_fetch_array($query);

    // Query untuk mengambil detail produk dalam penjualan
    $pro = mysqli_query($koneksi, "
        SELECT detail_penjualan.*, produk.nama_produk, 
               detail_penjualan.jumlah_produk,
               detail_penjualan.sub_total
        FROM detail_penjualan 
        LEFT JOIN produk ON produk.id_produk = detail_penjualan.id_produk 
        WHERE id_penjualan=$id
    ");
?>

<div class="container-fluid px-4">
    <div class="page-header mb-4">
        <h1 class="fw-bold">
            <i class="fas fa-receipt"></i> Detail Transaksi
            <span class="fs-5 text-muted">#<?php echo $id; ?></span>
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="?page=pembelian">Daftar Transaksi</a></li>
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
                        <h5 class="card-title mb-0">Informasi Transaksi</h5>
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
                                <label class="text-muted mb-1">Kasir</label>
                                <h6 class="mb-0">
                                    <i class="fas fa-user-circle text-primary"></i>
                                    <?php echo $data['nama_kasir']; ?>
                                </h6>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="text-muted mb-1">Pelanggan</label>
                                <h6 class="mb-0">
                                    <i class="fas fa-user text-primary"></i>
                                    <?php echo $data['nama_pelanggan'] ?: 'Umum'; ?>
                                </h6>
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
                            <span class="text-muted">Pembayaran</span>
                            <span class="fw-bold text-success">Rp <?php echo number_format($data['bayar'], 0, ',', '.'); ?></span>
                        </div>
                        <div class="payment-item d-flex justify-content-between">
                            <span class="text-muted">Kembalian</span>
                            <span class="fw-bold text-primary">Rp <?php echo number_format($data['kembali'], 0, ',', '.'); ?></span>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-grid gap-2">
                        <a href="cetak_struk.php?id=<?php echo $id; ?>" target="_blank" 
                           class="btn btn-primary btn-lg">
                            <i class="fas fa-print me-2"></i>
                            Cetak Struk
                        </a>
                        <a href="?page=pembelian" class="btn btn-outline-secondary">
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
