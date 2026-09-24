<?php


// Cek apakah pengguna sudah login dan memiliki level 'admin'
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    echo "<script>
            alert('Akses ditolak! Hanya admin yang bisa mengakses halaman ini.');
             history.back();
          </script>";
    exit(); // Pastikan script berhenti setelah redirect
}

// Koneksi ke databaase
include 'koneksi.php';

// Variabel untuk search dan pagination
$search = isset($_GET['search']) ? strip_tags($_GET['search']) : '';
$jumlahDataPerhalaman = 8;
$halamanAktif = isset($_GET['halaman']) ? $_GET['halaman'] : 1;
$awalData = ($jumlahDataPerhalaman * $halamanAktif) - $jumlahDataPerhalaman;

// Query dasar untuk data produk
$queryStr = "SELECT * FROM produk";

// Tambahkan filter pencarian jika ada
if (!empty($search)) {
    $queryStr .= " WHERE nama_produk LIKE '%$search%'";
}

// Hitung total data untuk pagination
$totalData = mysqli_num_rows(mysqli_query($koneksi, $queryStr));
$jumlahHalaman = ceil($totalData / $jumlahDataPerhalaman);

// Tambahkan limit untuk pagination
$queryStr .= " LIMIT $awalData, $jumlahDataPerhalaman";

// Jalankan query
$query = mysqli_query($koneksi, $queryStr);
?>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4" style="color: #1d3557;">
            <i class="fas fa-box me-2"></i> Manajemen Produk
        </h1>
        <a href="?page=produk_tambah" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i>Tambah Produk
        </a>
    </div>

    <!-- Search and Filter Card -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form action="" method="GET" class="row g-3 align-items-center">
                <input type="hidden" name="page" value="produk">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="<?= $search; ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-primary w-100" type="submit">
                        <i class="fas fa-search me-2"></i>Cari Produk
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row">
        <?php if (mysqli_num_rows($query) > 0): ?>
            <?php while ($data = mysqli_fetch_array($query)): ?>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="position-relative">
                            <img src="uploads/<?= $data['gambar_produk'] ? $data['gambar_produk'] : 'no-image.png'; ?>" 
                                 class="card-img-top" 
                                 alt="<?= $data['nama_produk']; ?>"
                                 style="height: 200px; object-fit: cover;">
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-<?= $data['stok'] > 0 ? 'success' : 'danger'; ?>">
                                    Stok: <?= $data['stok']; ?>
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title text-truncate"><?= $data['nama_produk']; ?></h5>
                            <p class="card-text text-primary fw-bold">
                                IDR <?= number_format($data['harga'], 0, ',', '.'); ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <a href="?page=produk_ubah&&id=<?= $data['id_produk']; ?>" 
                                   class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>
                                <a href="?page=produk_hapus&&id=<?= $data['id_produk']; ?>" 
                                   onclick="return confirm('Yakin ingin menghapus produk ini?')" 
                                   class="btn btn-outline-danger btn-sm">
                                    <i class="fas fa-trash-alt me-1"></i> Hapus
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle me-2"></i>Tidak ada data produk
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <nav class="mt-4">
        <ul class="pagination justify-content-center">
            <?php if ($halamanAktif > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=produk&halaman=<?= $halamanAktif - 1 ?>&search=<?= $search ?>">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $jumlahHalaman; $i++): ?>
                <li class="page-item <?= ($i == $halamanAktif) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=produk&halaman=<?= $i; ?>&search=<?= $search; ?>"><?= $i; ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($halamanAktif < $jumlahHalaman): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=produk&halaman=<?= $halamanAktif + 1 ?>&search=<?= $search ?>">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>

<style>
    .card {
        transition: transform 0.2s;
    }
    .card:hover {
        transform: translateY(-5px);
    }
    .card-img-top {
        border-top-left-radius: calc(0.375rem - 1px);
        border-top-right-radius: calc(0.375rem - 1px);
    }
    .page-link {
        color: #1d3557;
    }
    .page-item.active .page-link {
        background-color: #1d3557;
        border-color: #1d3557;
    }
    .btn-outline-success:hover, .btn-outline-danger:hover {
        color: white;
    }
</style>

