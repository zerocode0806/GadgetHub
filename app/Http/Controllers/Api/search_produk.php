<?php
require_once APP_ROOT . '/app/Support/koneksi.php';

$search = trim((string) ($_GET['search'] ?? ''));
$like = '%' . $search . '%';
$searchStmt = mysqli_prepare($koneksi, "SELECT * FROM produk WHERE (? = '' OR nama_produk LIKE ? OR kategori_produk LIKE ? OR deskripsi_produk LIKE ? OR spesifikasi_produk LIKE ?) ORDER BY nama_produk ASC");
mysqli_stmt_bind_param($searchStmt, 'sssss', $search, $like, $like, $like, $like);
mysqli_stmt_execute($searchStmt);
$pro = mysqli_stmt_get_result($searchStmt);

// Endpoint tetap menerima form lama yang mengirimkan produk/jumlah ke URL pencarian ini.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['id_user'])) {
        echo "<script>alert('Session tidak ditemukan, silakan login terlebih dahulu!'); window.location = '/login';</script>";
        exit;
    }

    $id_user = (int) $_SESSION['id_user'];
    $id_produk = (int) ($_POST['id_produk'] ?? 0);
    $nama_produk = mysqli_real_escape_string($koneksi, $_POST['nama_produk'] ?? '');
    $harga = (int) ($_POST['harga'] ?? 0);
    $jumlah = (int) ($_POST['jumlah'] ?? 0);
    $stok = (int) ($_POST['stok'] ?? 0);

    if ($jumlah < 1 || $jumlah > $stok) {
        echo "<script>alert('Jumlah yang diminta melebihi stok yang tersedia!'); window.location.href = '/admin/sales';</script>";
        exit;
    }

    $check_cart_query = "SELECT * FROM cart WHERE id_user = '$id_user' AND id_produk = '$id_produk'";
    $check_cart_result = mysqli_query($koneksi, $check_cart_query);
    if ($check_cart_result && mysqli_num_rows($check_cart_result) > 0) {
        $cart_item = mysqli_fetch_assoc($check_cart_result);
        $new_jumlah = (int) $cart_item['jumlah'] + $jumlah;
        if ($new_jumlah > $stok) {
            echo "<script>alert('Jumlah total melebihi stok yang tersedia!'); window.location.href = '/admin/sales';</script>";
            exit;
        }
        $update_cart_query = "UPDATE cart SET jumlah = '$new_jumlah' WHERE id_user = '$id_user' AND id_produk = '$id_produk'";
        mysqli_query($koneksi, $update_cart_query);
    } else {
        $total_harga = $jumlah * $harga;
        $insert_cart_query = "INSERT INTO cart (id_user, id_produk, nama_produk, harga, jumlah, total_harga) VALUES ('$id_user', '$id_produk', '$nama_produk', '$harga', '$jumlah', '$total_harga')";
        mysqli_query($koneksi, $insert_cart_query);
    }

    echo "<script>alert('Produk berhasil ditambahkan ke keranjang!'); window.location.href = '/admin/sales';</script>";
    exit;
}

require APP_ROOT . '/resources/views/api/search_produk.php';
