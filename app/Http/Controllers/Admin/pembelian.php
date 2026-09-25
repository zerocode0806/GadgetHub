<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
// Mulai session untuk mendapatkan informasi user yang sedang login
require_once APP_ROOT . '/app/Support/koneksi.php';

// Dapatkan data user dari session
$id_user = $_SESSION['id_user']; // ID user yang sedang login
$level = $_SESSION['level']; // level user (admin atau kasir)

if ($level === 'admin' && empty($_SESSION['admin_csrf'])) {
    $_SESSION['admin_csrf'] = bin2hex(random_bytes(32));
}
$admin_flash = $_SESSION['admin_flash'] ?? null;
unset($_SESSION['admin_flash']);

// Variabel untuk search dan pagination
$search = isset($_GET['search']) ? strip_tags($_GET['search']) : '';
$jumlahDataPerhalaman = 10;
$halamanAktif = isset($_GET['halaman']) ? $_GET['halaman'] : 1;
$awalData = ($jumlahDataPerhalaman * $halamanAktif) - $jumlahDataPerhalaman;

// Query default untuk admin (tanpa filter)
$queryStr = "
    SELECT penjualan.*, user.nama AS nama_kasir, pelanggan.nama_pelanggan 
    FROM penjualan 
    LEFT JOIN user ON user.id_user = penjualan.id_kasir
    LEFT JOIN pelanggan ON pelanggan.id_pelanggan = penjualan.id_pelanggan
";

// Jika user adalah kasir, tambahkan filter berdasarkan ID user
if ($level == 'petugas') {
    $queryStr .= " WHERE penjualan.id_kasir = '$id_user'";
}

// Tambahkan filter pencarian
if (!empty($search)) {
    $queryStr .= ($level == 'petugas' ? " AND" : " WHERE") . " 
        (penjualan.tanggal_penjualan LIKE '%$search%' OR 
        user.nama LIKE '%$search%' OR 
        pelanggan.nama_pelanggan LIKE '%$search%')";
}

// Hitung total data untuk pagination
$totalData = mysqli_num_rows(mysqli_query($koneksi, $queryStr));
$jumlahHalaman = ceil($totalData / $jumlahDataPerhalaman);

// Tambahkan limit untuk paginasi
$queryStr .= " ORDER BY penjualan.id_penjualan DESC, penjualan.tanggal_penjualan DESC LIMIT $awalData, $jumlahDataPerhalaman";

// Jalankan query
$query = mysqli_query($koneksi, $queryStr);
require APP_ROOT . '/resources/views/admin/pembelian.php';
