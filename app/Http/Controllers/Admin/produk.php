<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    header('Location: /dashboard');
    exit;
}

$search = trim((string) ($_GET['search'] ?? ''));
$jumlahDataPerhalaman = 8;
$halamanAktif = max(1, (int) ($_GET['halaman'] ?? 1));
$awalData = ($jumlahDataPerhalaman * $halamanAktif) - $jumlahDataPerhalaman;
$like = '%' . $search . '%';
$searchSql = "WHERE (? = '' OR nama_produk LIKE ? OR kategori_produk LIKE ? OR deskripsi_produk LIKE ? OR spesifikasi_produk LIKE ?)";
$countStmt = mysqli_prepare($koneksi, "SELECT COUNT(*) AS total FROM produk $searchSql");
mysqli_stmt_bind_param($countStmt, 'sssss', $search, $like, $like, $like, $like);
mysqli_stmt_execute($countStmt);
$totalData = (int) (mysqli_fetch_assoc(mysqli_stmt_get_result($countStmt))['total'] ?? 0);
$jumlahHalaman = max(1, (int) ceil($totalData / $jumlahDataPerhalaman));
$halamanAktif = min($halamanAktif, $jumlahHalaman);
$awalData = ($jumlahDataPerhalaman * $halamanAktif) - $jumlahDataPerhalaman;
$queryStmt = mysqli_prepare($koneksi, "SELECT * FROM produk $searchSql ORDER BY id_produk DESC LIMIT ?, ?");
mysqli_stmt_bind_param($queryStmt, 'sssssii', $search, $like, $like, $like, $like, $awalData, $jumlahDataPerhalaman);
mysqli_stmt_execute($queryStmt);
$query = mysqli_stmt_get_result($queryStmt);
require APP_ROOT . '/resources/views/admin/produk.php';
