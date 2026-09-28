<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    header('Location: /dashboard');
    exit;
}

$categoryNotice = '';
$categoryNoticeType = 'success';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryName = trim((string) ($_POST['nama_kategori'] ?? ''));
    $categoryLength = function_exists('mb_strlen') ? mb_strlen($categoryName) : strlen($categoryName);
    if ($categoryName === '' || $categoryLength > 100) {
        $categoryNotice = 'Nama kategori wajib diisi dan maksimal 100 karakter.';
        $categoryNoticeType = 'danger';
    } else {
        $stmt = mysqli_prepare($koneksi, 'INSERT IGNORE INTO kategori (nama_kategori) VALUES (?)');
        mysqli_stmt_bind_param($stmt, 's', $categoryName);
        if (mysqli_stmt_execute($stmt)) {
            $categoryNotice = mysqli_stmt_affected_rows($stmt) > 0
                ? 'Kategori berhasil ditambahkan.'
                : 'Nama kategori tersebut sudah tersedia.';
            $categoryNoticeType = mysqli_stmt_affected_rows($stmt) > 0 ? 'success' : 'info';
        } else {
            $categoryNotice = 'Kategori gagal disimpan. Silakan coba lagi.';
            $categoryNoticeType = 'danger';
        }
    }
}

$categoriesResult = mysqli_query($koneksi, 'SELECT k.id_kategori, k.nama_kategori, COUNT(p.id_produk) AS jumlah_produk FROM kategori k LEFT JOIN produk p ON p.kategori_produk = k.nama_kategori GROUP BY k.id_kategori, k.nama_kategori ORDER BY k.nama_kategori ASC');
require APP_ROOT . '/resources/views/admin/kategori.php';
