<div class="container-fluid px-4">
    <h1 class="mt-4 mb-4">Dashboard Overview</h1>
    
    <!-- Stats Cards Row -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-5 border-primary mb-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Total Pelanggan</div>
                            <div class="fs-4 fw-bold"><?php echo $customer_count; ?></div>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="fas fa-users fa-2x text-primary"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="/admin/customers" class="text-decoration-none">View Details <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-5 border-success mb-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Total Produk</div>
                            <div class="fs-4 fw-bold"><?php echo $product_count; ?></div>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="fas fa-box fa-2x text-success"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="/admin/products" class="text-decoration-none">View Details <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-5 border-warning mb-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Total Pembelian</div>
                            <div class="fs-4 fw-bold"><?php echo $sales_count; ?></div>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="fas fa-shopping-cart fa-2x text-warning"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="/admin/sales" class="text-decoration-none">View Details <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-5 border-info mb-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Total Pendapatan</div>
                            <div class="fs-4 fw-bold">Rp <?php echo number_format($total_sales, 0, ',', '.'); ?></div>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="fas fa-money-bill-wave fa-2x text-info"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="/admin/sales" class="text-decoration-none">View Details <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Business Info Row -->
    <div class="row">
        <div class="col-xl-8">
            <div class="card mb-4 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-chart-area me-1"></i>
                        Business Information
                    </div>
                    <button class="btn btn-sm btn-primary" onclick="window.location.href='/admin/settings'">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small">Business Name</label>
                                <div class="fs-5"><?php echo $settings['business_name']; ?></div>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Address</label>
                                <div class="fs-5"><?php echo $settings['address']; ?></div>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Phone</label>
                                <div class="fs-5"><?php echo $settings['phone']; ?></div>
                            </div>
                        </div>
                        <div class="col-md-6 text-center">
                            <label class="text-muted small">Business Logo</label>
                            <div class="mt-2">
                                <img src="/uploads/<?php echo $settings['logo']; ?>" alt="Brand Logo" class="img-fluid rounded" style="max-height: 150px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <i class="fas fa-bell me-1"></i>
                    Quick Actions
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="/admin/products?action=add" class="list-group-item list-group-item-action">
                            <i class="fas fa-plus-circle me-2"></i> Add New Product
                        </a>
                        <a href="/admin/sales?action=add" class="list-group-item list-group-item-action">
                            <i class="fas fa-cart-plus me-2"></i> Create New Order
                        </a>
                        <a href="/admin/users?action=add" class="list-group-item list-group-item-action">
                            <i class="fas fa-user-plus me-2"></i> Add New User
                        </a>
                        <a href="/admin/settings" class="list-group-item list-group-item-action">
                            <i class="fas fa-cog me-2"></i> Update Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
