<?php
require_once APP_ROOT . '/app/Support/koneksi.php';

$id = $_GET['id']; // Mendapatkan id penjualan dari URL
$query = mysqli_query($koneksi, "SELECT penjualan.*, pelanggan.nama_pelanggan, user.nama AS nama_kasir 
    FROM penjualan 
    LEFT JOIN pelanggan ON pelanggan.id_pelanggan = penjualan.id_pelanggan 
    LEFT JOIN user ON user.id_user = penjualan.id_kasir
    WHERE id_penjualan=$id");
$data = mysqli_fetch_array($query);

$pro = mysqli_query($koneksi, "SELECT * FROM detail_penjualan 
    LEFT JOIN produk ON produk.id_produk = detail_penjualan.id_produk 
    WHERE id_penjualan=$id");

$query = "SELECT business_name, address, phone, logo FROM settings WHERE id = 1";
$result = mysqli_query($koneksi, $query);
$settings = mysqli_fetch_assoc($result);

// Calculate total quantity
$total_qty = 0;
$detail_result = mysqli_query($koneksi, "SELECT jumlah_produk FROM detail_penjualan WHERE id_penjualan=$id");
while ($row = mysqli_fetch_assoc($detail_result)) {
    $total_qty += $row['jumlah_produk'];
}
require APP_ROOT . '/resources/views/admin/cetak_struk.php';
