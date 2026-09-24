<?php 
if (isset($_POST['first_name'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $level = $_POST['level'];

    // Validasi password
    if ($password !== $confirm_password) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Password dan Konfirmasi Password tidak cocok!'
                });
              </script>";
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Gabungkan nama depan dan belakang
    $nama = $first_name . ' ' . $last_name;

    // Insert data ke database
    $query = mysqli_query($koneksi, "INSERT INTO user (nama, username, password, level) 
                                      VALUES ('$nama', '$username', '$hashed_password', '$level')");
    if ($query) {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'User berhasil ditambahkan',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = '?page=user';
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Gagal menambahkan user'
                });
              </script>";
    }
}
?>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4" style="color: #1d3557;">
            <i class="fas fa-user-plus me-2"></i> Tambah User
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
                                    <input type="text" class="form-control" name="first_name" id="first_name" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" name="last_name" id="last_name" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                                    <input type="text" class="form-control" name="username" id="username" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" name="password" id="password" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Level</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-cogs"></i></span>
                                    <select class="form-select" name="level" id="level" required>
                                        <option value="">Select Level</option>
                                        <option value="admin">Admin</option>
                                        <option value="petugas">Petugas</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-save me-2"></i>Simpan User
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
    // Preview functions
    document.getElementById('first_name').addEventListener('input', function() {
        document.getElementById('previewName').textContent = this.value + ' ' + document.getElementById('last_name').value || 'Nama User';
    });

    document.getElementById('last_name').addEventListener('input', function() {
        document.getElementById('previewName').textContent = document.getElementById('first_name').value + ' ' + this.value || 'Nama User';
    });

    document.getElementById('username').addEventListener('input', function() {
        document.getElementById('previewUsername').textContent = this.value || 'User';
    });

    document.getElementById('level').addEventListener('input', function() {
        document.getElementById('previewLevel').textContent = this.value || 'Admin';
    });

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
    .user-details {
        border-top: 1px solid rgba(0,0,0,.125);
        padding-top: 1rem;
        margin-top: 1rem;
    }
</style>
