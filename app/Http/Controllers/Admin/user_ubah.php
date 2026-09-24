<?php
include 'koneksi.php';

// Check if 'id' is passed in the URL
if (isset($_GET['id'])) {
    $id_user = $_GET['id'];

    // Fetch user data based on 'id'
    $query = mysqli_query($koneksi, "SELECT * FROM user WHERE id_user = '$id_user'");
    $data = mysqli_fetch_array($query);

    // Check if user data is found
    if (!$data) {
        echo "<script>alert('User tidak ditemukan!'); window.location = '?page=user';</script>";
        exit();
    }
} else {
    echo "<script>alert('ID tidak valid!'); window.location = '?page=user';</script>";
    exit();
}

// Update data if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $level = $_POST['level'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>alert('Password dan Konfirmasi Password tidak cocok!'); window.location = '?page=user_ubah&id=$id_user';</script>";
        exit();
    }

    // Hash password if it's set
    $hashed_password = empty($password) ? $data['password'] : password_hash($password, PASSWORD_DEFAULT);

    // Combine first and last name
    $nama = $first_name . ' ' . $last_name;

    // Update the user data in the database
    $sql = "UPDATE user SET nama = '$nama', username = '$username', password = '$hashed_password', level = '$level' WHERE id_user = '$id_user'";
    $result = mysqli_query($koneksi, $sql);

    if ($result) {
        echo "<script>alert('User berhasil diperbarui!'); window.location = '?page=user';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan, coba lagi!'); window.location = '?page=user_ubah&id=$id_user';</script>";
    }
}
?>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4" style="color: #1d3557;">
            <i class="fas fa-user-edit me-2"></i> Edit User
        </h1>
        <a href="?page=user" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row">
        <!-- Form Section -->
        <div class="col-lg-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informasi User
                    </h5>
                </div>
                <div class="card-body">
                    <form id="userForm" method="post">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" name="first_name" 
                                        value="<?php echo explode(' ', $data['nama'])[0]; ?>" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" name="last_name" 
                                        value="<?php echo explode(' ', $data['nama'])[1]; ?>" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                                    <input type="text" class="form-control" name="username" 
                                        value="<?php echo $data['username']; ?>" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" name="password">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" name="confirm_password">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Level</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-cogs"></i></span>
                                    <select class="form-select" name="level" required>
                                        <option value="admin" <?php echo $data['level'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                        <option value="petugas" <?php echo $data['level'] == 'petugas' ? 'selected' : ''; ?>>Petugas</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-save me-2"></i>Save Changes
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
            document.getElementById('previewName').textContent = 'Nama User';
            document.getElementById('previewUsername').textContent = 'User';
            document.getElementById('previewLevel').textContent = 'Admin';
            document.getElementById('previewImage').src = 'assets/img/no-image.png';
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
