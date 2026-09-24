<?php 
include 'koneksi.php';
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    echo "<script>showError('Akses ditolak! Hanya admin yang bisa mengakses halaman ini.', 'index.php');</script>";
    exit();
}
?>

<div class="container-fluid px-4">
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="fw-bold">
                    <i class="fas fa-users"></i> Manajemen Pengguna
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Manajemen Pengguna</li>
                    </ol>
                </nav>
            </div>
            <a href="?page=user_tambah" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i>Tambah Pengguna
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="userTable">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Level</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $query = mysqli_query($koneksi, "SELECT * FROM user ORDER BY level DESC, nama ASC");
                        while($data = mysqli_fetch_array($query)){
                        ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar me-3">
                                            <?php 
                                            $initials = strtoupper(substr($data['nama'], 0, 2));
                                            $bgColor = sprintf('#%06X', crc32($data['nama']) & 0xFFFFFF);
                                            ?>
                                            <div class="avatar-circle" style="background-color: <?php echo $bgColor; ?>">
                                                <?php echo $initials; ?>
                                            </div>
                                        </div>
                                        <div class="user-info">
                                            <h6 class="mb-0"><?php echo $data['nama']; ?></h6>
                                            <small class="text-muted">ID: <?php echo $data['id_user']; ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="username"><?php echo $data['username']; ?></span>
                                </td>
                                <td>
                                    <?php 
                                    $levelClass = $data['level'] == 'admin' ? 'bg-primary' : 'bg-info';
                                    ?>
                                    <span class="badge <?php echo $levelClass; ?>"><?php echo ucfirst($data['level']); ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-success">Aktif</span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="?page=user_ubah&&id=<?php echo $data['id_user']; ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                            <span class="d-none d-md-inline ms-1">Edit</span>
                                        </a>
                                        <a href="?page=user_hapus&&id=<?php echo $data['id_user']; ?>" 
                                           class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                            <span class="d-none d-md-inline ms-1">Hapus</span>
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

.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
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
function confirmDelete(userId, userName) {
    showConfirm(
        `Apakah Anda yakin ingin menghapus pengguna "${userName}"?`,
        function() {
            window.location.href = `?page=user_hapus&&id=${userId}`;
        }
    );
}


// Initialize DataTable
$(document).ready(function() {
    $('#userTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        },
        pageLength: 10,
        responsive: true,
        dom: '<"row"<"col-md-6"l><"col-md-6"f>>rtip',
        order: [[2, 'desc'], [0, 'asc']]
    });
});
</script>
