<?php

// Periksa apakah user sudah login
if (!isset($_SESSION['id_user']) || !isset($_SESSION['level'])) {
    echo "<script>
        alert('Session tidak ditemukan, silakan login terlebih dahulu!');
        window.location = 'login.php';
    </script>";
    exit();
}

// Ambil data user dari session
$id_user = $_SESSION['id_user']; // ID user yang login
$username = $_SESSION['username']; // Nama user yang login
$level = $_SESSION['level']; // Level user (admin/kasir)

// Ambil kata kunci pencarian dari URL
$search = isset($_GET['search']) ? $_GET['search'] : '';

$pro_query = "SELECT * FROM produk";
if (!empty($search)) {
    $pro_query .= " WHERE nama_produk LIKE '%$search%'";
}
$pro = mysqli_query($koneksi, $pro_query);

// Query untuk menghitung jumlah item di keranjang
$cart_count_query = "SELECT SUM(jumlah) as total_items FROM cart WHERE id_user = '$id_user'";
$cart_count_result = mysqli_query($koneksi, $cart_count_query);
$cart_count = mysqli_fetch_assoc($cart_count_result);
$total_items = $cart_count['total_items'] ?? 0;

// Handle AJAX request for adding to cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajax_request'])) {
    // Ambil data produk dan jumlah dari form
    $id_produk = (int)$_POST['id_produk'];
    $nama_produk = mysqli_real_escape_string($koneksi, $_POST['nama_produk']);
    $harga = (int)$_POST['harga'];
    $jumlah = (int)$_POST['jumlah'];
    $stok = (int)$_POST['stok'];

    $response = array();

    // Periksa apakah jumlah yang diminta tidak melebihi stok
    if ($jumlah > $stok) {
        $response['success'] = false;
        $response['message'] = 'Jumlah yang diminta melebihi stok yang tersedia!';
        echo json_encode($response);
        exit();
    }

    // Periksa apakah produk sudah ada dalam keranjang (tabel cart)
    $check_cart_query = "SELECT * FROM cart WHERE id_user = '$id_user' AND id_produk = '$id_produk'";
    $check_cart_result = mysqli_query($koneksi, $check_cart_query);

    if (mysqli_num_rows($check_cart_result) > 0) {
        // Jika produk sudah ada, update jumlahnya
        $cart_item = mysqli_fetch_assoc($check_cart_result);
        $new_jumlah = $cart_item['jumlah'] + $jumlah;
        if ($new_jumlah > $stok) {
            $response['success'] = false;
            $response['message'] = 'Jumlah total melebihi stok yang tersedia!';
            echo json_encode($response);
            exit();
        }

        // Update jumlah barang di keranjang
        $update_cart_query = "UPDATE cart SET jumlah = '$new_jumlah' WHERE id_user = '$id_user' AND id_produk = '$id_produk'";
        mysqli_query($koneksi, $update_cart_query);
    } else {
        // Jika produk belum ada di keranjang, insert data baru
        $total_harga = $jumlah * $harga;
        $insert_cart_query = "INSERT INTO cart (id_user, id_produk, nama_produk, harga, jumlah, total_harga) 
                              VALUES ('$id_user', '$id_produk', '$nama_produk', '$harga', '$jumlah', '$total_harga')";
        mysqli_query($koneksi, $insert_cart_query);
    }

    // Update cart count
    $cart_count_query = "SELECT SUM(jumlah) as total_items FROM cart WHERE id_user = '$id_user'";
    $cart_count_result = mysqli_query($koneksi, $cart_count_query);
    $cart_count = mysqli_fetch_assoc($cart_count_result);
    $new_total_items = $cart_count['total_items'] ?? 0;

    $response['success'] = true;
    $response['message'] = 'Produk berhasil ditambahkan ke keranjang!';
    $response['cart_count'] = $new_total_items;
    
    echo json_encode($response);
    exit();
}

// Proses data dari form (fallback for non-AJAX)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['ajax_request'])) {
    // Ambil data produk dan jumlah dari form
    $id_produk = (int)$_POST['id_produk'];
    $nama_produk = mysqli_real_escape_string($koneksi, $_POST['nama_produk']);
    $harga = (int)$_POST['harga'];
    $jumlah = (int)$_POST['jumlah'];
    $stok = (int)$_POST['stok'];

    // Periksa apakah jumlah yang diminta tidak melebihi stok
    if ($jumlah > $stok) {
        echo "<script>
            alert('Jumlah yang diminta melebihi stok yang tersedia!');
            window.location.href = '?page=pembelian';
        </script>";
        exit();
    }

    // Periksa apakah produk sudah ada dalam keranjang (tabel cart)
    $check_cart_query = "SELECT * FROM cart WHERE id_user = '$id_user' AND id_produk = '$id_produk'";
    $check_cart_result = mysqli_query($koneksi, $check_cart_query);

    if (mysqli_num_rows($check_cart_result) > 0) {
        // Jika produk sudah ada, update jumlahnya
        $cart_item = mysqli_fetch_assoc($check_cart_result);
        $new_jumlah = $cart_item['jumlah'] + $jumlah;
        if ($new_jumlah > $stok) {
            echo "<script>
                alert('Jumlah total melebihi stok yang tersedia!');
                window.location.href = '?page=pembelian';
            </script>";
            exit();
        }

        // Update jumlah barang di keranjang
        $update_cart_query = "UPDATE cart SET jumlah = '$new_jumlah' WHERE id_user = '$id_user' AND id_produk = '$id_produk'";
        mysqli_query($koneksi, $update_cart_query);
    } else {
        // Jika produk belum ada di keranjang, insert data baru
        $total_harga = $jumlah * $harga;
        $insert_cart_query = "INSERT INTO cart (id_user, id_produk, nama_produk, harga, jumlah, total_harga) 
                              VALUES ('$id_user', '$id_produk', '$nama_produk', '$harga', '$jumlah', '$total_harga')";
        mysqli_query($koneksi, $insert_cart_query);
    }

    // Setelah berhasil, alihkan ke halaman keranjang
    echo "<script>
        alert('Produk berhasil ditambahkan ke keranjang!');
        window.location.href = '?page=pembelian_tambah';
    </script>";
}
?>



<div class="container-fluid px-4">
    <!-- Header Section with improved styling -->
    <div class="page-header mb-4">
        <h1 class="fw-bold"><i class="fas fa-shopping-cart"></i> Katalog Produk</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Katalog</li>
            </ol>
        </nav>
    </div>

    <!-- Action Buttons with improved styling -->
    <div class="action-buttons mb-4">
        <a href="?page=pembelian" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <button type="button" class="btn btn-primary position-relative" onclick="showCartItems()">
            <i class="fas fa-shopping-cart"></i> Keranjang (<span id="cart-count"><?php echo $total_items; ?></span>)
            <?php if ($total_items > 0): ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cart-badge">
                    <?php echo $total_items; ?>
                    <span class="visually-hidden">items in cart</span>
                </span>
            <?php else: ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cart-badge" style="display: none;">
                    0
                    <span class="visually-hidden">items in cart</span>
                </span>
            <?php endif; ?>
        </button>
    </div>

    <!-- Search Bar with improved styling -->
    <div class="search-section mb-4">
        <form method="GET" action="" id="searchForm">
            <div class="input-group">
                <input type="text" 
                       class="form-control search-input" 
                       id="searchInput" 
                       placeholder="Cari produk..." 
                       value="<?php echo htmlspecialchars($search); ?>" 
                       oninput="searchProduk()">
                <button class="btn btn-primary" type="button" onclick="searchProduk()">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Products Grid with improved styling -->
    <div class="products-grid">
        <div class="row g-4">
            <?php
            if ($pro) {
                while ($produk = mysqli_fetch_array($pro)) {
            ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="product-card">
                        <div class="product-badge">
                            <?php if ($produk['stok'] > 0): ?>
                                <span class="badge-stock in-stock">Stok: <?php echo $produk['stok']; ?></span>
                            <?php else: ?>
                                <span class="badge-stock out-stock">Habis</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="product-image-wrapper">
                            <img src="uploads/<?php echo $produk['gambar_produk']; ?>" 
                                 alt="<?php echo $produk['nama_produk']; ?>"
                                 class="product-image">
                        </div>

                        <div class="product-content">
                            <h5 class="product-title"><?php echo $produk['nama_produk']; ?></h5>
                            <div class="product-price">
                                <span class="currency">Rp</span>
                                <span class="amount"><?php echo number_format($produk['harga'], 0, ',', '.'); ?></span>
                            </div>
                            <div class="product-description">
                                <?php echo nl2br($produk['deskripsi_produk']); ?>
                            </div>

                            <form method="POST" action="" class="product-form" onsubmit="addToCart(event, this)">
                                <input type="hidden" name="id_produk" value="<?php echo $produk['id_produk']; ?>">
                                <input type="hidden" name="nama_produk" value="<?php echo $produk['nama_produk']; ?>">
                                <input type="hidden" name="harga" value="<?php echo $produk['harga']; ?>">
                                <input type="hidden" name="stok" value="<?php echo $produk['stok']; ?>">
                                <input type="hidden" name="ajax_request" value="1">
                                
                                <div class="product-actions">
                                    <div class="quantity-wrapper">
                                        <button type="button" class="qty-btn" onclick="decrementQty(this)">-</button>
                                        <input type="number" 
                                               class="quantity-input" 
                                               name="jumlah" 
                                               min="1" 
                                               max="<?php echo $produk['stok']; ?>" 
                                               value="1">
                                        <button type="button" class="qty-btn" onclick="incrementQty(this)">+</button>
                                    </div>
                                    <button type="submit" class="add-to-cart-btn" <?php echo $produk['stok'] <= 0 ? 'disabled' : ''; ?>>
                                        <i class="fas fa-cart-plus"></i>
                                        <span>Tambah ke Keranjang</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php
                }
            }
            ?>
        </div>
    </div>
</div>

<style>
/* Modern styling for the page */
.page-header h1 {
    color: #2c3e50;
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.breadcrumb {
    background: transparent;
    padding: 0;
}

.action-buttons {
    display: flex;
    gap: 1rem;
}

.search-input {
    border-radius: 50px 0 0 50px;
    padding-left: 1.5rem;
}

.search-input + .btn {
    border-radius: 0 50px 50px 0;
}

/* Product Card Styling */
.product-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
    overflow: hidden;
    position: relative;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
}

.product-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    z-index: 2;
}

.badge-stock {
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 600;
}

.in-stock {
    background: rgba(46, 213, 115, 0.15);
    color: #2ed573;
}

.out-stock {
    background: rgba(255, 71, 87, 0.15);
    color: #ff4757;
}

.product-image-wrapper {
    position: relative;
    padding-top: 75%; /* 4:3 Aspect Ratio */
    overflow: hidden;
}

.product-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image {
    transform: scale(1.05);
}

.product-content {
    padding: 1.5rem;
}

.product-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #2d3436;
    margin-bottom: 0.75rem;
}

.product-price {
    margin-bottom: 1rem;
}

.currency {
    font-size: 1rem;
    color: #636e72;
    margin-right: 0.25rem;
}

.amount {
    font-size: 1.5rem;
    font-weight: 700;
    color: #0984e3;
}

.product-description {
    font-size: 0.9rem;
    color: #636e72;
    margin-bottom: 1.5rem;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.5;
}

.product-actions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.quantity-wrapper {
    display: flex;
    align-items: center;
    background: #f1f2f6;
    border-radius: 50px;
    padding: 0.25rem;
    width: fit-content;
}

.qty-btn {
    width: 35px;
    height: 35px;
    border: none;
    background: white;
    border-radius: 50%;
    font-size: 1.25rem;
    color: #2d3436;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.qty-btn:hover {
    background: #e1e4e8;
}

.quantity-input {
    width: 50px;
    border: none;
    background: transparent;
    text-align: center;
    font-weight: 600;
    color: #2d3436;
}

.quantity-input::-webkit-inner-spin-button,
.quantity-input::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.add-to-cart-btn {
    width: 100%;
    padding: 0.75rem 1.5rem;
    border: none;
    background: #0984e3;
    color: white;
    border-radius: 50px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: background-color 0.2s ease;
}

.add-to-cart-btn:hover {
    background: #0773c5;
}

.add-to-cart-btn:disabled {
    background: #b2bec3;
    cursor: not-allowed;
}

@media (max-width: 768px) {
    .product-title {
        font-size: 1.1rem;
    }
    
    .amount {
        font-size: 1.25rem;
    }
    
    .add-to-cart-btn {
        padding: 0.6rem 1.2rem;
    }
}

/* Add these styles for the cart badge */
.action-buttons .btn {
    position: relative;
}

.badge {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
    transform: translate(-50%, -50%);
}

.badge.bg-danger {
    background-color: #dc3545 !important;
    border: 2px solid #fff;
}
</style>

<script>
function resetForm() {
    document.querySelectorAll('.form-control').forEach(function (input) {
        input.value = '0';
    });
    document.getElementById('total').value = 0;
    document.getElementById('bayar').value = '';
    document.getElementById('kembali').value = 0;
}

function searchProduk() {
    var searchTerm = document.getElementById('searchInput').value;
    
    // Buat objek AJAX
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "search_produk.php?search=" + searchTerm, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Update konten produk dengan hasil pencarian
            document.getElementById('produkList').innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}

function incrementQty(btn) {
    const input = btn.parentElement.querySelector('.quantity-input');
    const max = parseInt(input.getAttribute('max'));
    const currentValue = parseInt(input.value);
    if (currentValue < max) {
        input.value = currentValue + 1;
    }
}

function decrementQty(btn) {
    const input = btn.parentElement.querySelector('.quantity-input');
    const currentValue = parseInt(input.value);
    if (currentValue > 1) {
        input.value = currentValue - 1;
    }
}

// AJAX function to add product to cart without page reload
function addToCart(event, form) {
    event.preventDefault(); // Prevent form from submitting normally
    
    // Get form data
    const formData = new FormData(form);
    
    // Create XMLHttpRequest
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '', true);
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                
                if (response.success) {
                    // Show success alert
                    alert(response.message);
                    
                    // Update cart badge and count
                    const cartBadge = document.getElementById('cart-badge');
                    const cartCount = document.getElementById('cart-count');
                    
                    if (cartBadge) {
                        cartBadge.textContent = response.cart_count;
                        if (response.cart_count > 0) {
                            cartBadge.style.display = 'inline-block';
                        }
                    }
                    
                    if (cartCount) {
                        cartCount.textContent = response.cart_count;
                    }
                    
                    // Reset quantity to 1
                    const quantityInput = form.querySelector('.quantity-input');
                    if (quantityInput) {
                        quantityInput.value = 1;
                    }
                } else {
                    // Show error alert
                    alert(response.message);
                }
            } catch (e) {
                alert('Terjadi kesalahan saat menambahkan produk ke keranjang.');
            }
        }
    };
    
    xhr.send(formData);
}

// Function to show cart items without page reload
function showCartItems() {
    // Create XMLHttpRequest to get cart items
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '?page=checkout', true);
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // For now, just navigate to checkout page
            // You can modify this to show a modal or update content dynamically
            window.location.href = '?page=checkout';
        }
    };
    
    xhr.send();
}
</script>