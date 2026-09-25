<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard - <?php echo $settings['business_name']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="/css/styles.css" rel="stylesheet" />
    <!-- Add Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Add Custom CSS -->
    <style>
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 600;
        }
        .sb-nav-fixed #layoutSidenav #layoutSidenav_nav {
            background: #f8f9fa;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        .sb-sidenav-menu .nav-link {
            padding: 1rem;
            margin: 0.2rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s;
        }
        .sb-sidenav-menu .nav-link:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }
        .sb-sidenav-menu .nav-link.active {
            background: #1d3557;
            color: white;
        }
        .sb-sidenav-menu-heading {
            padding: 1.5rem 1rem 0.5rem;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            color: #6c757d;
        }
        .sb-nav-link-icon {
            background: rgba(0,0,0,0.05);
            padding: 0.5rem;
            border-radius: 0.5rem;
            margin-right: 0.75rem;
        }
        .sb-sidenav-footer {
            background: white;
            border-top: 1px solid #dee2e6;
            padding: 1rem;
        }
        #layoutSidenav_content {
            background: #f8f9fa;
        }
        main {
            padding: 2rem;
        }
        .footer {
            background: white;
            border-top: 1px solid #dee2e6;
        }
        .avatar-circle-sm {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.8rem;
        }
        
        #userDropdown {
            text-decoration: none;
        }
    </style>
    <link rel="stylesheet" href="/css/theme.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body class="sb-nav-fixed">
    <!-- Navbar with gradient -->
    <nav class="sb-topnav navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #1d3557 0%, #457b9d 100%);">
        <a class="navbar-brand ps-3" href="/dashboard">
            <i class="fas fa-store me-2"></i>
            <?php echo $settings['business_name']; ?>
        </a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
            <i class="fas fa-bars"></i>
        </button>
        <!-- Add user profile dropdown -->
        <div class="ms-auto me-3">
            <div class="dropdown">
                <button class="btn btn-link text-light dropdown-toggle d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php 
                    $initials = strtoupper(substr($_SESSION['username'], 0, 2));
                    $bgColor = sprintf('#%06X', crc32($_SESSION['username']) & 0xFFFFFF);
                    ?>
                    <div class="avatar-circle-sm me-2" style="background-color: <?php echo $bgColor; ?>">
                        <?php echo $initials; ?>
                    </div>
                    <?php echo $_SESSION['username']; ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="/logout" onclick="return confirm('Yakin ingin keluar?')">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Umum</div>
                        
                        <a class="nav-link <?php echo (!isset($_GET['page']) || $_GET['page'] == 'home') ? 'active' : ''; ?>" href="/dashboard">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>

                        <?php if ($level == 'admin'): ?>
                            <div class="sb-sidenav-menu-heading">Manajemen</div>
                            <a class="nav-link <?php echo (isset($_GET['page']) && $_GET['page'] == 'user') ? 'active' : ''; ?>" href="/admin/users">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-cog"></i></div>
                                Akun
                            </a>
                            <a class="nav-link <?php echo (isset($_GET['page']) && $_GET['page'] == 'pelanggan') ? 'active' : ''; ?>" href="/admin/customers">
                                <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                                Pelanggan
                            </a>

                            <a class="nav-link <?php echo (isset($_GET['page']) && $_GET['page'] == 'produk') ? 'active' : ''; ?>" href="/admin/products">
                                <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
                                Produk
                            </a>
                            <a class="nav-link <?php echo (isset($_GET['page']) && $_GET['page'] == 'settings') ? 'active' : ''; ?>" href="/admin/settings">
                                <div class="sb-nav-link-icon"><i class="fas fa-cogs"></i></div>
                                Settings
                            </a>
                        <?php endif; ?>

                        <div class="sb-sidenav-menu-heading">Transaksi</div>
                        <a class="nav-link <?php echo (isset($_GET['page']) && $_GET['page'] == 'pelanggan_detail') ? 'active' : ''; ?>" href="/admin/sales">
                            <div class="sb-nav-link-icon"><i class="fas fa-shopping-cart"></i></div>
                            Pembelian
                        </a>
                    </div>
                </div>
            </nav>
        </div>

        <div id="layoutSidenav_content">
            <main>
                <?= $page_content; ?>
            </main>
            
            <footer class="py-4 bg-white mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; <?php echo $settings['business_name']; ?> <?php echo date("Y"); ?></div>
                        <div>
                            <a href="#" class="text-decoration-none">Privacy Policy</a>
                            &middot;
                            <a href="#" class="text-decoration-none">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/scripts.js"></script>
</body>
</html>
