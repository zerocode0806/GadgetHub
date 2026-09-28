<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
$categories = [];
$categoryResult = mysqli_query($koneksi, 'SELECT nama_kategori FROM kategori ORDER BY nama_kategori ASC');
if ($categoryResult) {
    while ($categoryRow = mysqli_fetch_assoc($categoryResult)) $categories[] = $categoryRow['nama_kategori'];
}
if (isset($_POST['nama_produk'])) {
    $nama = $_POST['nama_produk'];
    $kategori = trim($_POST['kategori_produk'] ?? 'Aksesori');
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $deskripsi = $_POST['deskripsi_produk'] ?? '';
    $spesifikasi = $_POST['spesifikasi_produk'] ?? '';

    // Proses upload gambar
    $gambar = $_FILES['gambar_produk']['name'];
    $gambar_tmp = $_FILES['gambar_produk']['tmp_name'];
    $gambar_path = APP_ROOT . '/public/uploads/' . $gambar;

    // Memindahkan file gambar ke folder uploads
    move_uploaded_file($gambar_tmp, $gambar_path);

    // Menyimpan data produk ke database
    $stmt = mysqli_prepare($koneksi, 'INSERT INTO produk (nama_produk, kategori_produk, harga, stok, gambar_produk, deskripsi_produk, spesifikasi_produk) VALUES (?, ?, ?, ?, ?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'ssiisss', $nama, $kategori, $harga, $stok, $gambar, $deskripsi, $spesifikasi);
    $query = mysqli_stmt_execute($stmt);
    if ($query) {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Produk berhasil ditambahkan',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = '/admin/products';
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Gagal menambahkan produk'
                });
              </script>";
    }
}
require APP_ROOT . '/resources/views/admin/produk_tambah.php';
