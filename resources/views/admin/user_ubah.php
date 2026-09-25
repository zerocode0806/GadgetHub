<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4" style="color: #1d3557;">
            <i class="fas fa-user-edit me-2"></i> Edit Akun
        </h1>
        <a href="/admin/users" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row">
        <!-- Form Section -->
        <div class="col-lg-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informasi Akun
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
                                        <option value="user" <?php echo $data['level'] == 'user' ? 'selected' : ''; ?>>User</option>
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
            document.getElementById('previewImage').src = '/assets/img/no-image.png';
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
