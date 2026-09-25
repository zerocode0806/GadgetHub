<div class="container-fluid px-4">
    <div class="checkout-header mb-4">
        <h1 class="fw-bold">
            <i class="fas fa-shopping-cart"></i> Checkout
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="/admin/sales/create">Katalog</a></li>
                <li class="breadcrumb-item active">Checkout</li>
            </ol>
        </nav>
    </div>

    <div class="row g-4">
        <!-- Cart Items Section -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Keranjang Belanja</h5>
                        <?php if (count($cart_items) > 0): ?>
                            <form method="POST" class="d-inline">
                                <button type="submit" name="reset_cart" class="btn btn-outline-danger btn-sm" 
                                    onclick="return confirm('Apakah Anda yakin ingin mengosongkan keranjang?')">
                                    <i class="fas fa-trash-alt"></i> Kosongkan
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="card-body">
                    <?php if (count($cart_items) > 0): ?>
                        <div class="cart-items">
                            <?php 
                            $total_harga = 0;
                            foreach ($cart_items as $id_produk => $item): 
                                $subtotal = $item['harga'] * $item['qty'];
                                $total_harga += $subtotal;
                            ?>
                                <div class="cart-item">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="cart-item-details flex-grow-1">
                                            <h6 class="item-name mb-1"><?= $item['nama_produk'] ?></h6>
                                            <div class="item-price text-muted">
                                                Rp <?= number_format($item['harga'], 0, ',', '.') ?>
                                            </div>
                                        </div>
                                        
                                        <div class="quantity-control">
                                            <div class="input-group">
                                                <input type="number" 
                                                    class="form-control form-control-sm text-center quantity-input" 
                                                    name="produk[<?= $id_produk ?>]" 
                                                    value="<?= $item['qty'] ?>" 
                                                    min="1" 
                                                    max="<?= $item['stok'] ?>"
                                                    data-harga="<?= $item['harga'] ?>"
                                                    data-id="<?= $id_produk ?>"
                                                    onchange="updateSubtotal(this)">
                                            </div>
                                            <small class="text-muted">Stok: <?= $item['stok'] ?></small>
                                        </div>
                                        
                                        <div class="subtotal text-end">
                                            <div class="amount" id="subtotal_<?= $id_produk ?>">
                                                Rp <?= number_format($subtotal, 0, ',', '.') ?>
                                            </div>
                                        </div>
                                        
                                        <a href="/admin/checkout?hapus=<?= $id_produk ?>" 
                                           class="btn btn-link text-danger p-0 ms-3"
                                           onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <h5>Keranjang Kosong</h5>
                            <p class="text-muted">Silakan tambahkan produk ke keranjang</p>
                            <a href="/admin/sales/create" class="btn btn-primary">
                                Lanjut Belanja
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Payment Information Section -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="card-title mb-0"><i class="fas fa-file-invoice me-2"></i>Informasi Pembayaran</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="" id="payment-form">
                        <?php foreach ($cart_items as $id_produk => $item): ?>
                            <input type="hidden" name="produk[<?= $id_produk ?>]" 
                                   value="<?= $item['qty'] ?>" 
                                   id="hidden_qty_<?= $id_produk ?>">
                        <?php endforeach; ?>
                        <!-- Summary Box -->
                        <div class="payment-summary mb-4">
                            <div class="summary-box p-3 rounded">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Subtotal</span>
                                    <span class="fw-bold" id="subtotal-display">Rp <?= number_format($total_harga, 0, ',', '.') ?></span>
                                </div>
                                <hr class="my-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Total Pembayaran </span>
                                    <span class="fw-bold fs-5 text-primary" id="total-display">Rp <?= number_format($total_harga, 0, ',', '.') ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Information -->
                        <div class="customer-info mb-4">
                            <h6 class="section-title mb-3"><i class="fas fa-user-circle me-2"></i>Data Pelanggan</h6>
                            
                            <!-- Select Pelanggan dengan Search -->
                            <div class="mb-3">
                                <label class="form-label">Pilih Pelanggan yang Sudah Ada</label>
                                <select class="form-select select2" name="id_pelanggan" id="id_pelanggan">
                                    <option value="">-- Pilih Pelanggan --</option>
                                    <?php foreach ($customers as $pel): ?>
                                        <option value="<?= (int) $pel['id_pelanggan']; ?>"
                                            data-nama="<?= htmlspecialchars($pel['nama_pelanggan'], ENT_QUOTES, 'UTF-8'); ?>"
                                            data-alamat="<?= htmlspecialchars($pel['alamat'], ENT_QUOTES, 'UTF-8'); ?>"
                                            data-telepon="<?= htmlspecialchars($pel['no_telepon'], ENT_QUOTES, 'UTF-8'); ?>"><?= htmlspecialchars($pel['nama_pelanggan'], ENT_QUOTES, 'UTF-8'); ?> - <?= htmlspecialchars($pel['no_telepon'], ENT_QUOTES, 'UTF-8'); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="text-muted">atau isi form di bawah untuk pelanggan baru</small>
                            </div>

                            <hr class="my-3">

                            <!-- Form input manual -->
                            <div class="mb-3">
                                <label class="form-label">Informasi Pelanggan</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" name="nama_pelanggan" id="nama_pelanggan" 
                                        placeholder="Nama Pelanggan">
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                    <textarea class="form-control" name="alamat" id="alamat" rows="2" 
                                        placeholder="Alamat Lengkap"></textarea>
                                </div>

                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="text" class="form-control" name="no_telepon" id="no_telepon" 
                                        placeholder="Nomor Telepon">
                                </div>
                            </div>
                        </div>

                        <!-- Payment Details -->
                        <div class="payment-details">
                            <h6 class="section-title mb-3"><i class="fas fa-money-bill-wave me-2"></i>Detail Pembayaran</h6>
                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-light">Rp</span>
                                    <input type="number" class="form-control bg-light fw-bold" name="total" 
                                        id="total" value="<?= $total_harga ?>" readonly>
                                </div>
                                <small class="text-muted">Total yang harus dibayar</small>
                            </div>

                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" name="bayar" id="bayar" 
                                        oninput="hitungKembali()" required placeholder="Jumlah yang dibayar">
                                </div>
                                <small class="text-muted">Masukkan jumlah uang yang diterima</small>
                            </div>

                            <div class="mb-4">
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control bg-light text-success fw-bold" 
                                        name="kembali" id="kembali" readonly>
                                </div>
                                <small class="text-muted">Jumlah kembalian</small>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg" 
                                <?= count($cart_items) === 0 ? 'disabled' : '' ?>>
                                <i class="fas fa-check-circle me-2"></i>Proses Pembayaran
                            </button>
                            <a href="/admin/sales/create" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Lanjut Belanja
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.checkout-header h1 {
    color: #2c3e50;
    font-size: 2rem;
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

.cart-items {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.cart-item {
    padding: 1rem;
    border-radius: 8px;
    background-color: #f8f9fa;
    transition: background-color 0.2s ease;
}

.cart-item:hover {
    background-color: #f1f3f5;
}

.item-name {
    color: #2c3e50;
    margin: 0;
}

.quantity-control {
    width: 120px;
}

.quantity-input {
    text-align: center;
    font-weight: 500;
}

.subtotal .amount {
    font-weight: 600;
    color: #2c3e50;
    white-space: nowrap;
}

.payment-summary {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
}

.form-control:read-only {
    background-color: #f8f9fa;
    cursor: not-allowed;
}

.btn-primary {
    background-color: #3498db;
    border-color: #3498db;
}

.btn-primary:hover {
    background-color: #2980b9;
    border-color: #2980b9;
}

.sticky-top {
    z-index: 1020;
}

@media (max-width: 768px) {
    .cart-item {
        padding: 0.75rem;
    }
    
    .quantity-control {
        width: 100px;
    }
    
    .subtotal {
        font-size: 0.9rem;
    }
}

/* New styles for payment form */
.summary-box {
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
}

.section-title {
    color: #2c3e50;
    font-weight: 600;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e9ecef;
}

.input-group-text {
    background-color: #f8f9fa;
    border-right: none;
}

.input-group .form-control {
    border-left: none;
}

.input-group .form-control:focus {
    border-color: #dee2e6;
    box-shadow: none;
}

.input-group:focus-within {
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    border-radius: 0.375rem;
}

.input-group:focus-within .input-group-text,
.input-group:focus-within .form-control {
    border-color: #dee2e6;
}

.customer-info, .payment-details {
    background-color: #ffffff;
    border-radius: 8px;
    padding: 1.5rem;
    box-shadow: 0 0 10px rgba(0,0,0,0.03);
}

.btn-primary {
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

small.text-muted {
    font-size: 0.75rem;
    margin-top: 0.25rem;
    display: block;
}

#kembali {
    font-size: 1.1rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .card-body {
        padding: 1rem;
    }
    
    .customer-info, .payment-details {
        padding: 1rem;
    }
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
// Pastikan document ready
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        placeholder: "Pilih Pelanggan",
        allowClear: true,
        width: '100%'
    });

    // Event handler saat pelanggan dipilih dari dropdown
    $('#id_pelanggan').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        if (selectedOption.val()) {
            $('#nama_pelanggan').val(selectedOption.data('nama'));
            $('#alamat').val(selectedOption.data('alamat'));
            $('#no_telepon').val(selectedOption.data('telepon'));
        } else {
            $('#nama_pelanggan, #alamat, #no_telepon').val('');
        }
    });
});

function updateSubtotal(input) {
    const qty = parseInt(input.value);
    const harga = parseInt(input.dataset.harga);
    const id_produk = input.dataset.id;
    
    // Update hidden input
    document.getElementById(`hidden_qty_${id_produk}`).value = qty;
    
    // Hitung subtotal untuk item ini
    const subtotal = qty * harga;
    
    // Update tampilan subtotal
    const subtotalElement = document.getElementById(`subtotal_${id_produk}`);
    subtotalElement.innerHTML = `Rp ${numberFormat(subtotal)}`;
    
    // Hitung ulang total keseluruhan
    hitungTotal();
}

function numberFormat(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

function hitungTotal() {
    let total = 0;
    document.querySelectorAll('.quantity-input').forEach(function(input) {
        let qty = parseInt(input.value);
        let harga = parseInt(input.dataset.harga);
        if (!isNaN(qty) && qty > 0 && !isNaN(harga)) {
            total += qty * harga;
        }
    });
    
    // Update input total
    document.getElementById('total').value = total;
    
    // Update tampilan subtotal dan total
    document.getElementById('subtotal-display').innerHTML = `Rp ${numberFormat(total)}`;
    document.getElementById('total-display').innerHTML = `Rp ${numberFormat(total)}`;
    
    hitungKembali();
}

function hitungKembali() {
    let total = parseInt(document.getElementById('total').value) || 0;
    let bayar = parseInt(document.getElementById('bayar').value) || 0;
    let kembali = bayar - total;
    
    document.getElementById('kembali').value = kembali > 0 ? kembali : 0;
}
</script>
