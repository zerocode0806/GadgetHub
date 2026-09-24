<?php
include 'koneksi.php';

// Check if 'id' is passed in the URL
if (isset($_GET['id'])) {
    $id_pelanggan = $_GET['id'];

    // Fetch customer data based on 'id'
    $query = mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE id_pelanggan = '$id_pelanggan'");
    $data = mysqli_fetch_array($query);

    // Check if customer data is found
    if (!$data) {
        echo "<script>alert('Pelanggan tidak ditemukan!'); window.location = '?page=pelanggan';</script>";
        exit();
    }
} else {
    echo "<script>alert('ID tidak valid!'); window.location = '?page=pelanggan';</script>";
    exit();
}

// Update data if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $alamat = $_POST['alamat'];
    $no_telepon = $_POST['no_telepon'];

    // Update the customer data in the database
    $sql = "UPDATE pelanggan SET nama_pelanggan = '$nama_pelanggan', alamat = '$alamat', no_telepon = '$no_telepon' WHERE id_pelanggan = '$id_pelanggan'";
    $result = mysqli_query($koneksi, $sql);

    if ($result) {
        echo "<script>alert('Pelanggan berhasil diperbarui!'); window.location = '?page=pelanggan';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan, coba lagi!'); window.location = '?page=pelanggan_ubah&id=$id_pelanggan';</script>";
    }
}
?>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4" style="color: #1d3557;">
            <i class="fas fa-user-edit me-2"></i> Edit Pelanggan
        </h1>
        <a href="?page=pelanggan" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row">
        <!-- Form Section -->
        <div class="col-lg-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informasi Pelanggan
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Nama</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" name="nama_pelanggan" 
                                           value="<?php echo $data['nama_pelanggan']; ?>" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Alamat</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                    <input type="text" class="form-control" name="alamat" 
                                           value="<?php echo $data['alamat']; ?>" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">No. Telepon</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="text" class="form-control" name="no_telepon" 
                                           value="<?php echo $data['no_telepon']; ?>" required>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                                </button>
                                <button type="reset" class="btn btn-danger" id="resetButton">
                                    <i class="fas fa-eraser me-2"></i>Reset Form
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Reset preview when form is reset
    document.getElementById('resetButton').addEventListener('click', function() {
        setTimeout(() => {
            document.getElementById('previewName').textContent = 'Nama Pelanggan';
            document.getElementById('previewPhone').textContent = 'Nomor Telepon';
            document.getElementById('previewAddress').textContent = 'Alamat Pelanggan';
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
</style>
