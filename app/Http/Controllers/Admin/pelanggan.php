<?php
require_once APP_ROOT . '/app/Support/koneksi.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    echo "<script>showError('Akses ditolak! Hanya admin yang bisa mengakses halaman ini.', '/');</script>";
    exit();
}

// Get search input from the URL (if available)
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Start building the query
$query = "
    SELECT 
        p.id_pelanggan,
        p.nama_pelanggan,
        p.alamat,
        p.no_telepon,
        IFNULL(COUNT(DISTINCT dp.id_penjualan), 0) AS total_pembelian,  -- Menghitung jumlah transaksi yang unik
        IFNULL(SUM(DISTINCT pb.total_harga), 0) AS total_harga            -- Mengambil total_harga yang unik dari tabel penjualan
    FROM 
        pelanggan p
    LEFT JOIN 
        penjualan pb ON p.id_pelanggan = pb.id_pelanggan       -- JOIN dengan tabel penjualan untuk total_harga
    LEFT JOIN 
        detail_penjualan dp ON pb.id_penjualan = dp.id_penjualan -- JOIN dengan detail_penjualan
";


// Apply filter if search input is given
if (!empty($search)) {
    $search = mysqli_real_escape_string($koneksi, $search);  // Prevent SQL injection
    
    // Jika pencarian adalah angka, mencari berdasarkan total pembelian
    if (is_numeric($search)) {
        $query .= " WHERE (p.nama_pelanggan LIKE '%$search%' OR p.no_telepon LIKE '%$search%')";
        $query .= " GROUP BY p.id_pelanggan, p.nama_pelanggan, p.alamat, p.no_telepon";  // Tambahkan semua kolom ke GROUP BY
        $query .= " HAVING total_pembelian = '$search' ";  // Filter by total purchase count
    } else {
        $query .= " WHERE p.nama_pelanggan LIKE '%$search%' OR p.no_telepon LIKE '%$search%' ";
        $query .= " GROUP BY p.id_pelanggan, p.nama_pelanggan, p.alamat, p.no_telepon";  // Tambahkan semua kolom ke GROUP BY
    }
} else {
    // If no search, apply GROUP BY at this point
    $query .= " GROUP BY p.id_pelanggan, p.nama_pelanggan, p.alamat, p.no_telepon";  // Tambahkan semua kolom ke GROUP BY
}

// Final ordering
$query .= "
    ORDER BY p.nama_pelanggan ASC
";

// Execute the query
$result = mysqli_query($koneksi, $query);
require APP_ROOT . '/resources/views/admin/pelanggan.php';
