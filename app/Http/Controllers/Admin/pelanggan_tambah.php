<?php 
if (isset($_POST['nama_pelanggan'])) {
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $no_telepon = $_POST['no_telepon'];
    $alamat = $_POST['alamat'];

    // Insert data ke database
    $query = mysqli_query($koneksi, "INSERT INTO pelanggan (nama_pelanggan, no_telepon, alamat) 
                                      VALUES ('$nama_pelanggan', '$no_telepon', '$alamat')");
    if ($query) {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Pelanggan berhasil ditambahkan',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = '?page=pelanggan';
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Gagal menambahkan pelanggan'
                });
              </script>";
    }
}
?>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4" style="color: #1d3557;">
            <i class="fas fa-user-plus me-2"></i> Tambah Pelanggan
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
                    <form id="pelangganForm" method="post">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Nama</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" name="nama_pelanggan" id="nama_pelanggan" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Alamat</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                    <input type="text" class="form-control" name="alamat" id="alamat" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">No Telepon</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="text" class="form-control" name="no_telepon" id="no_telepon" required>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-save me-2"></i>Simpan Pelanggan
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
            document.getElementById('previewName').textContent = 'nama_pelanggan Pelanggan';
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
