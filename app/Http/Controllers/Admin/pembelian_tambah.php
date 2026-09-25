<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
// Periksa apakah user sudah login
if (!isset($_SESSION['id_user']) || !isset($_SESSION['level'])) {
    echo "<script>
        alert('Session tidak ditemukan, silakan login terlebih dahulu!');
        window.location = '/login';
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
            window.location.href = '/admin/sales';
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
                window.location.href = '/admin/sales';
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
        window.location.href = '/admin/sales/create';
    </script>";
}
require APP_ROOT . '/resources/views/admin/pembelian_tambah.php';
