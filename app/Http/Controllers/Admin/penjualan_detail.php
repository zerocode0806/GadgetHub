<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
// Mendapatkan id penjualan dari parameter URL
    $id = $_GET['id'];

    // Query untuk mengambil data penjualan, kasir, dan pelanggan
    $query = mysqli_query($koneksi, "
        SELECT penjualan.*, user.nama AS nama_kasir, pelanggan.nama_pelanggan 
        FROM penjualan 
        LEFT JOIN user ON user.id_user = penjualan.id_kasir 
        LEFT JOIN pelanggan ON pelanggan.id_pelanggan = penjualan.id_pelanggan 
        WHERE id_penjualan=$id
    ");
    $data = mysqli_fetch_array($query);

    // Query untuk mengambil detail produk dalam penjualan
    $pro = mysqli_query($koneksi, "
        SELECT detail_penjualan.*, produk.nama_produk, 
               detail_penjualan.jumlah_produk,
               detail_penjualan.sub_total
        FROM detail_penjualan 
        LEFT JOIN produk ON produk.id_produk = detail_penjualan.id_produk 
        WHERE id_penjualan=$id
    ");
require APP_ROOT . '/resources/views/admin/penjualan_detail.php';
