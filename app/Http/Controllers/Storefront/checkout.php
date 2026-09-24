<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['level']) && $_SESSION['level'] === 'user') {
    header('Location: user_checkout.php');
    exit;
}
// Cek apakah pengguna sudah login
if (!isset($_SESSION['id_user']) || !isset($_SESSION['level'])) {
    echo "<script>
        alert('Session tidak ditemukan, silakan login terlebih dahulu!');
        window.location = 'login.php';
    </script>";
    exit();
}

// Ambil ID user dari session
$id_user = $_SESSION['id_user'];

// Query untuk mengambil produk yang ada dalam keranjang dengan stok produk
$query = "
    SELECT c.*, p.stok
    FROM cart c
    JOIN produk p ON c.id_produk = p.id_produk
    WHERE c.id_user = '$id_user'
";
$result = mysqli_query($koneksi, $query);

// Cek apakah ada data dalam keranjang
$cart_items = [];
$total_harga = 0; // Initialize total_harga to 0
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Simpan data produk ke dalam array cart_items
        $cart_items[$row['id_produk']] = [
            'nama_produk' => $row['nama_produk'],
            'harga' => $row['harga'],
            'stok' => $row['stok'],
            'qty' => $row['jumlah']
        ];
    }
}

// tombol minus
if (isset($_GET['hapus'])) {
    $id_produk_hapus = $_GET['hapus'];
    $query_hapus = "DELETE FROM cart WHERE id_user = '$id_user' AND id_produk = '$id_produk_hapus'";
    mysqli_query($koneksi, $query_hapus);
    echo "<script>
        alert('Produk berhasil dihapus dari keranjang.');
        window.location.href = '?page=checkout';
    </script>";
    exit();
}


// Jika tombol reset ditekan, hapus semua data di tabel cart untuk user saat ini
if (isset($_POST['reset_cart'])) {
    $query_hapus_cart = "DELETE FROM cart WHERE id_user = '$id_user'";
    mysqli_query($koneksi, $query_hapus_cart);
    echo "<script>
        alert('Keranjang telah dikosongkan.');
        window.location.href = window.location.href;
    </script>";
    exit();
}

// Proses saat tombol "Proses Pembayaran" ditekan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pelanggan = $_POST['id_pelanggan'];
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $alamat = $_POST['alamat'];
    $no_telepon = $_POST['no_telepon'];
    $total_harga = $_POST['total'];
    $bayar = $_POST['bayar'];
    $kembali = $_POST['kembali'];
    
    // Validasi pembayaran
    if ($bayar < $total_harga) {
        echo "<script>
            alert('Jumlah pembayaran tidak mencukupi!');
            window.location.href = '?page=checkout';
        </script>";
        exit();
    }

    // Proses data pelanggan
    if (!empty($id_pelanggan)) {
        // Gunakan pelanggan yang sudah ada
        $query_pelanggan = mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE id_pelanggan = '$id_pelanggan'");
        $data_pelanggan = mysqli_fetch_assoc($query_pelanggan);
        $nama_pelanggan = $data_pelanggan['nama_pelanggan'];
        $alamat = $data_pelanggan['alamat'];
        $no_telepon = $data_pelanggan['no_telepon'];
    } else if (!empty($nama_pelanggan) && !empty($alamat) && !empty($no_telepon)) {
        // Buat pelanggan baru
        $query_pelanggan = "INSERT INTO pelanggan (nama_pelanggan, alamat, no_telepon) 
                           VALUES ('$nama_pelanggan', '$alamat', '$no_telepon')";
        mysqli_query($koneksi, $query_pelanggan);
        $id_pelanggan = mysqli_insert_id($koneksi);
    } else {
        echo "<script>
            alert('Silakan pilih pelanggan atau isi semua data pelanggan baru!');
            window.location.href = '?page=checkout';
        </script>";
        exit();
    }

    // Insert ke tabel penjualan
    $query_penjualan = "INSERT INTO penjualan (tanggal_penjualan, id_kasir, total_harga, id_pelanggan, bayar, kembali)
                        VALUES (NOW(), '$id_user', '$total_harga', '$id_pelanggan', '$bayar', '$kembali')";
    $result_penjualan = mysqli_query($koneksi, $query_penjualan);

    if ($result_penjualan) {
        $id_penjualan = mysqli_insert_id($koneksi);

        // Ambil data quantity terbaru dari form
        foreach ($cart_items as $id_produk => $item) {
            $qty_input = $_POST['produk'][$id_produk] ?? $item['qty'];
            $harga = $item['harga'];
            $sub_total = $harga * $qty_input;

            // Insert detail penjualan
            $query_detail = "INSERT INTO detail_penjualan (id_penjualan, id_produk, jumlah_produk, sub_total)
                            VALUES ('$id_penjualan', '$id_produk', '$qty_input', '$sub_total')";
            mysqli_query($koneksi, $query_detail);

            // Update stok produk
            $new_stok = $item['stok'] - $qty_input;
            $query_update_stok = "UPDATE produk SET stok = '$new_stok' WHERE id_produk = '$id_produk'";
            mysqli_query($koneksi, $query_update_stok);
        }

        // Hapus keranjang setelah transaksi selesai
        $query_hapus_cart = "DELETE FROM cart WHERE id_user = '$id_user'";
        mysqli_query($koneksi, $query_hapus_cart);

        echo "<script>
            alert('Pembayaran berhasil! Transaksi telah dicatat.');
            window.location.href = '?page=pembelian';
        </script>";
        exit();
    } else {
        echo "<script>alert('Terjadi kesalahan saat memproses pembayaran.');</script>";
    }
}

?>

<div class="container-fluid px-4">
    <div class="checkout-header mb-4">
        <h1 class="fw-bold">
            <i class="fas fa-shopping-cart"></i> Checkout
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="?page=pembelian_tambah">Katalog</a></li>
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
                                        
                                        <a href="?page=checkout&hapus=<?= $id_produk ?>" 
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
                            <a href="?page=pembelian_tambah" class="btn btn-primary">
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
                                    <?php
                                    $p = mysqli_query($koneksi, "SELECT * FROM pelanggan");
                                    while ($pel = mysqli_fetch_array($p)) {
                                        echo "<option value='{$pel['id_pelanggan']}' 
                                            data-nama='{$pel['nama_pelanggan']}'
                                            data-alamat='{$pel['alamat']}'
                                            data-telepon='{$pel['no_telepon']}'>{$pel['nama_pelanggan']} - {$pel['no_telepon']}</option>";
                                    }
                                    ?>
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
                            <a href="?page=pembelian_tambah" class="btn btn-outline-secondary">
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
