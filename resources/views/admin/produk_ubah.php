<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4" style="color: #1d3557;">
            <i class="fas fa-edit me-2"></i> Edit Produk
        </h1>
        <a href="/admin/products" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row">
        <!-- Form Section -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informasi Produk
                    </h5>
                </div>
                <div class="card-body">
                    <form id="editProductForm" method="post" enctype="multipart/form-data">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Produk</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-box"></i></span>
                                    <input type="text" class="form-control" name="nama_produk" id="nama_produk" 
                                           value="<?php echo $data['nama_produk']; ?>" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Harga</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" name="harga" id="harga" 
                                           value="<?php echo $data['harga']; ?>" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Stok</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-cubes"></i></span>
                                    <input type="number" class="form-control" name="stok" id="stok" 
                                           value="<?php echo $data['stok']; ?>" required min="0">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Gambar Produk</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-image"></i></span>
                                    <input type="file" class="form-control" name="gambar_produk" id="fileInput" 
                                           accept="image/*">
                                </div>
                                <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar</small>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Deskripsi Produk</label>
                                <textarea class="form-control" name="deskripsi_produk" id="deskripsi_produk" 
                                          rows="4" required><?php echo $data['deskripsi_produk']; ?></textarea>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                                </button>
                                <button type="reset" class="btn btn-danger" id="resetButton">
                                    <i class="fas fa-undo me-2"></i>Reset Form
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Preview Section -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-eye me-2"></i>Preview Produk
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img id="previewImage" 
                             src="<?php echo $data['gambar_produk'] ? '/uploads/'.$data['gambar_produk'] : '/assets/img/no-image.png'; ?>" 
                             class="img-fluid rounded" 
                             style="max-height: 200px; object-fit: cover;">
                    </div>
                    <div class="product-details">
                        <h5 id="previewNama" class="text-center mb-3"><?php echo $data['nama_produk']; ?></h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Harga:</span>
                            <span id="previewHarga" class="fw-bold">
                                Rp <?php echo number_format($data['harga'], 0, ',', '.'); ?>
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Stok:</span>
                            <span id="previewStok" class="fw-bold"><?php echo $data['stok']; ?></span>
                        </div>
                        <div class="mt-3">
                            <span class="text-muted">Deskripsi:</span>
                            <p id="previewDeskripsi" class="mt-2"><?php echo $data['deskripsi_produk']; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Preview functions
    document.getElementById('nama_produk').addEventListener('input', function() {
        document.getElementById('previewNama').textContent = this.value || 'Nama Produk';
    });

    document.getElementById('harga').addEventListener('input', function() {
        const formattedPrice = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR'
        }).format(this.value);
        document.getElementById('previewHarga').textContent = formattedPrice;
    });

    document.getElementById('stok').addEventListener('input', function() {
        document.getElementById('previewStok').textContent = this.value || '0';
    });

    document.getElementById('deskripsi_produk').addEventListener('input', function() {
        document.getElementById('previewDeskripsi').textContent = this.value || 'Tidak ada deskripsi';
    });

    document.getElementById('fileInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImage').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });

    // Reset preview to current data
    document.getElementById('resetButton').addEventListener('click', function() {
        setTimeout(() => {
            document.getElementById('previewNama').textContent = '<?php echo $data['nama_produk']; ?>';
            document.getElementById('previewHarga').textContent = 'Rp <?php echo number_format($data['harga'], 0, ',', '.'); ?>';
            document.getElementById('previewStok').textContent = '<?php echo $data['stok']; ?>';
            document.getElementById('previewDeskripsi').textContent = '<?php echo $data['deskripsi_produk']; ?>';
            document.getElementById('previewImage').src = '<?php echo $data['gambar_produk'] ? "/uploads/".$data['gambar_produk'] : "/assets/img/no-image.png"; ?>';
        }, 0);
    });
</script>

<style>
    .card {
        border-radius: 10px;
    }
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,.125);
    }
    .form-control:focus, .input-group-text {
        border-color: #1d3557;
        box-shadow: 0 0 0 0.2rem rgba(29, 53, 87, 0.25);
    }
    .btn-primary {
        background-color: #1d3557;
        border-color: #1d3557;
    }
    .btn-primary:hover {
        background-color: #152640;
        border-color: #152640;
    }
    .product-details {
        border-top: 1px solid rgba(0,0,0,.125);
        padding-top: 1rem;
        margin-top: 1rem;
    }
    .input-group-text {
        background-color: #f8f9fa;
    }
    textarea.form-control {
        min-height: 120px;
    }
</style>

