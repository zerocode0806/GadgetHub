<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
$categories = [];
$categoryResult = mysqli_query($koneksi, 'SELECT nama_kategori FROM kategori ORDER BY nama_kategori ASC');
if ($categoryResult) {
    while ($categoryRow = mysqli_fetch_assoc($categoryResult)) $categories[] = $categoryRow['nama_kategori'];
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: /admin/products');
    exit;
}

if (isset($_POST['nama_produk'])) {
    $nama = trim($_POST['nama_produk'] ?? '');
    $kategori = trim($_POST['kategori_produk'] ?? 'Aksesori');
    $harga = (int) ($_POST['harga'] ?? 0);
    $stok = (int) ($_POST['stok'] ?? 0);
    $deskripsi = trim($_POST['deskripsi_produk'] ?? '');
    $spesifikasi = trim($_POST['spesifikasi_produk'] ?? '');
    $gambar = null;
    $hasNewImage = isset($_FILES['gambar_produk']) && $_FILES['gambar_produk']['error'] === UPLOAD_ERR_OK;

    if ($hasNewImage) {
        $gambar = basename($_FILES['gambar_produk']['name']);
        $gambarTmp = $_FILES['gambar_produk']['tmp_name'];
        $gambarPath = APP_ROOT . '/public/uploads/' . $gambar;
        $uploadOk = move_uploaded_file($gambarTmp, $gambarPath);
    } else {
        $uploadOk = true;
    }

    if ($uploadOk && $hasNewImage) {
        $stmt = mysqli_prepare($koneksi, 'UPDATE produk SET nama_produk = ?, kategori_produk = ?, harga = ?, stok = ?, gambar_produk = ?, deskripsi_produk = ?, spesifikasi_produk = ? WHERE id_produk = ?');
        mysqli_stmt_bind_param($stmt, 'ssiisssi', $nama, $kategori, $harga, $stok, $gambar, $deskripsi, $spesifikasi, $id);
    } elseif ($uploadOk) {
        $stmt = mysqli_prepare($koneksi, 'UPDATE produk SET nama_produk = ?, kategori_produk = ?, harga = ?, stok = ?, deskripsi_produk = ?, spesifikasi_produk = ? WHERE id_produk = ?');
        mysqli_stmt_bind_param($stmt, 'ssiissi', $nama, $kategori, $harga, $stok, $deskripsi, $spesifikasi, $id);
    } else {
        $stmt = false;
    }

    $query = $stmt ? mysqli_stmt_execute($stmt) : false;
    if ($query) {
        echo "<script>Swal.fire({icon:'success',title:'Berhasil!',text:'Produk berhasil diperbarui',showConfirmButton:false,timer:1500}).then(() => {window.location.href='/admin/products';});</script>";
    } else {
        echo "<script>Swal.fire({icon:'error',title:'Oops...',text:'Gagal memperbarui produk'});</script>";
    }
}

$stmt = mysqli_prepare($koneksi, 'SELECT * FROM produk WHERE id_produk = ? LIMIT 1');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$data) {
    http_response_code(404);
    exit('Produk tidak ditemukan.');
}
require APP_ROOT . '/resources/views/admin/produk_ubah.php';
