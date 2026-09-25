<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4" style="color: #1d3557;">
            <i class="fas fa-cogs me-2"></i> Business Settings
        </h1>
    </div>

    <div class="row">
        <!-- Settings Form -->
        <div class="col-xl-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-building me-2"></i>Business Information
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" id="settingsForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Business Name</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-store"></i></span>
                                    <input type="text" class="form-control" name="business_name" 
                                           value="<?php echo $settings['business_name']; ?>" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="tel" class="form-control" name="phone" 
                                           value="<?php echo $settings['phone']; ?>" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" name="email" 
                                           value="<?php echo $settings['email']; ?>">
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Business Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                    <textarea class="form-control" name="address" rows="3" 
                                              required><?php echo $settings['address']; ?></textarea>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Business Logo</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-image"></i></span>
                                    <input type="file" class="form-control" name="logo" id="logoInput" 
                                           accept="image/*">
                                </div>
                                <small class="text-muted">Leave empty to keep current logo</small>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-save me-2"></i>Save Changes
                                </button>
                                <button type="reset" class="btn btn-danger" id="resetButton">
                                    <i class="fas fa-undo me-2"></i>Reset Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Preview Card -->
        <div class="col-xl-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-eye me-2"></i>Preview
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img id="logoPreview" 
                             src="<?php echo !empty($settings['logo']) ? '/uploads/'.$settings['logo'] : '/assets/img/no-logo.png'; ?>" 
                             class="img-fluid rounded" 
                             style="max-height: 150px; object-fit: contain;">
                    </div>
                    <div class="business-details">
                        <div class="mb-3">
                            <label class="text-muted small">Business Name</label>
                            <div class="fs-5 fw-bold"><?php echo $settings['business_name']; ?></div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small">Contact Information</label>
                            <div><i class="fas fa-phone me-2"></i><?php echo $settings['phone']; ?></div>
                            <div><i class="fas fa-envelope me-2"></i><?php echo $settings['email']; ?></div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small">Address</label>
                            <div><i class="fas fa-map-marker-alt me-2"></i><?php echo $settings['address']; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Logo preview
    document.getElementById('logoInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('logoPreview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });

    // Form validation
    document.getElementById('settingsForm').addEventListener('submit', function(event) {
        const phone = document.querySelector('input[name="phone"]').value;
        const email = document.querySelector('input[name="email"]').value;
        
        // Basic phone validation
        if (!/^\d{10,15}$/.test(phone.replace(/[^0-9]/g, ''))) {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Invalid Phone Number',
                text: 'Please enter a valid phone number'
            });
            return;
        }
        
        // Basic email validation
        if (email & !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Invalid Email',
                text: 'Please enter a valid email address'
            });
            return;
        }
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
    .input-group-text {
        background-color: #f8f9fa;
        width: 40px;
        justify-content: center;
    }
    .business-details {
        border-top: 1px solid rgba(0,0,0,.125);
        padding-top: 1rem;
    }
    textarea.form-control {
        min-height: 100px;
    }
</style>
