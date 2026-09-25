<div class="container-fluid px-4">
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="fw-bold">
                    <i class="fas fa-users"></i> Manajemen Pelanggan
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active">Manajemen Pelanggan</li>
                    </ol>
                </nav>
            </div>
            <a href="/admin/customers/create" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i>Tambah Pelanggan
            </a>
        </div>
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
            <form action="" method="GET" class="row g-3 align-items-center">
                <input type="hidden" name="page" value="pelanggan">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control" 
                            placeholder="Cari berdasarkan nama pelanggan atau jumlah pembelian..." 
                            value="<?= htmlspecialchars($search); ?>">
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
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="customerTable">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Pelanggan</th>
                            <th>Alamat</th>
                            <th>No Telepon</th>
                            <th>Jumlah Pembelian</th>
                            <th>Total Pembelian</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        while($data = mysqli_fetch_array($result)){
                        ?>
                            <tr>
                                <td><?php echo $data['nama_pelanggan']; ?></td>
                                <td><?php echo $data['alamat']; ?></td>
                                <td><?php echo $data['no_telepon']; ?></td>
                                <td class="text-center"><?php echo $data['total_pembelian']; ?></td>
                                <td>Rp <?php echo number_format($data['total_harga'], 0, ',', '.'); ?></td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="/admin/customers/edit?&id=<?php echo $data['id_pelanggan']; ?>" 
                                           class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-edit"></i>
                                            <span class="d-none d-md-inline ms-1">Edit</span>
                                        </a>
                                        <a href="/admin/customers/delete?&id=<?php echo $data['id_pelanggan']; ?>"
                                        onclick="return confirm('Yakin ingin menghapus data ini?')" 
                                           class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                            <span class="d-none d-md-inline ms-1">Hapus</span>
                                        </a>
                                        <a href="/admin/customers/detail?&id=<?php echo $data['id_pelanggan']; ?>" 
                                           class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-info-circle"></i>
                                            <span class="d-none d-md-inline ms-1">Detail</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<style>
.page-header h1 {
    color: #2c3e50;
    font-size: 1.75rem;
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
    border-radius: 15px;
    overflow: hidden;
}

.table {
    margin-bottom: 0;
}

.table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    color: #2c3e50;
}

.table td {
    vertical-align: middle;
    padding: 1rem 0.75rem;
}

.user-info h6 {
    font-size: 0.95rem;
    color: #2c3e50;
}

.username {
    font-family: monospace;
    font-size: 0.9rem;
    color: #666;
}

.badge {
    padding: 0.5em 0.8em;
    font-weight: 500;
    text-transform: capitalize;
}

.btn-outline-primary, .btn-outline-danger {
    border-width: 1.5px;
}

.btn-outline-primary:hover {
    background-color: #3498db;
}

.btn-outline-danger:hover {
    background-color: #e74c3c;
}


</style>

<script>
function confirmDelete(customerId, customerName) {
    showConfirm(
        `Apakah Anda yakin ingin menghapus pelanggan "${customerName}"?`,
        function() {
            window.location.href = `/admin/customers/delete?&id=${customerId}`;
        }
    );
}

// Initialize DataTable
$(document).ready(function() {
    $('#customerTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        },
        pageLength: 10,
        responsive: true,
        dom: '<"row"<"col-md-6"l><"col-md-6"f>>rtip',
        order: [[1, 'asc']]
    });
});
</script>


