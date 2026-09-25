<?php
require_once APP_ROOT . '/app/Support/koneksi.php';

// Get customer ID from URL
$id_pelanggan = isset($_GET['id']) ? $_GET['id'] : '';

// Get customer details
$query_pelanggan = mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE id_pelanggan = '$id_pelanggan'");
$pelanggan = mysqli_fetch_array($query_pelanggan);

// Get date filter parameters
$tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : '';
$tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : '';

// Modify the query to include date filtering
$date_filter = "";
if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
    $date_filter = "AND DATE(tanggal_penjualan) BETWEEN '$tanggal_awal' AND '$tanggal_akhir'";
}

$count_result = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM penjualan WHERE id_pelanggan = '$id_pelanggan' $date_filter");
$total_pembelian = (int) (mysqli_fetch_assoc($count_result)['total'] ?? 0);
$sum_result = mysqli_query($koneksi, "SELECT COALESCE(SUM(total_harga), 0) AS total FROM penjualan WHERE id_pelanggan = '$id_pelanggan' $date_filter");
$total_nilai = (float) (mysqli_fetch_assoc($sum_result)['total'] ?? 0);

// Get customer's purchase history
$query_pembelian = mysqli_query($koneksi, "
    SELECT p.*, u.nama AS nama_kasir,
           (SELECT COUNT(*) FROM detail_penjualan dp WHERE dp.id_penjualan = p.id_penjualan) as total_items
    FROM penjualan p
    LEFT JOIN user u ON u.id_user = p.id_kasir
    WHERE p.id_pelanggan = '$id_pelanggan' 
    $date_filter
    ORDER BY p.tanggal_penjualan DESC
");
require APP_ROOT . '/resources/views/admin/pelanggan_detail.php';
