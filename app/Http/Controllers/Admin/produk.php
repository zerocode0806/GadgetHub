<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
// Cek apakah pengguna sudah login dan memiliki level 'admin'
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    echo "<script>
            alert('Akses ditolak! Hanya admin yang bisa mengakses halaman ini.');
             history.back();
          </script>";
    exit(); // Pastikan script berhenti setelah redirect
}

// Koneksi ke databaase
require_once APP_ROOT . '/app/Support/koneksi.php';

// Variabel untuk search dan pagination
$search = isset($_GET['search']) ? strip_tags($_GET['search']) : '';
$jumlahDataPerhalaman = 8;
$halamanAktif = isset($_GET['halaman']) ? $_GET['halaman'] : 1;
$awalData = ($jumlahDataPerhalaman * $halamanAktif) - $jumlahDataPerhalaman;

// Query dasar untuk data produk
$queryStr = "SELECT * FROM produk";

// Tambahkan filter pencarian jika ada
if (!empty($search)) {
    $queryStr .= " WHERE nama_produk LIKE '%$search%'";
}

// Hitung total data untuk pagination
$totalData = mysqli_num_rows(mysqli_query($koneksi, $queryStr));
$jumlahHalaman = ceil($totalData / $jumlahDataPerhalaman);

// Tambahkan limit untuk pagination
$queryStr .= " LIMIT $awalData, $jumlahDataPerhalaman";

// Jalankan query
$query = mysqli_query($koneksi, $queryStr);
require APP_ROOT . '/resources/views/admin/produk.php';
