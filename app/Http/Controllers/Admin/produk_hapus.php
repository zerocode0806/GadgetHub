<?php
$id = $_GET['id'];

// Ambil nama file gambar dari database
$query = mysqli_query($koneksi, "SELECT gambar_produk FROM produk WHERE id_produk = $id");
$row = mysqli_fetch_assoc($query);

// Jika gambar ditemukan, hapus gambar dari folder uploads
if ($row) {
    $file_gambar = APP_ROOT . '/uploads/' . $row['gambar_produk'];

    // Periksa apakah file gambar ada dan hapus
    if (file_exists($file_gambar)) {
        unlink($file_gambar); // Menghapus file
    }
}

// Hapus data produk dari database
$query = mysqli_query($koneksi, "DELETE FROM produk WHERE id_produk = $id");

if ($query) {
    echo "<script>alert('Data berhasil dihapus'); location.href = '?page=produk';</script>";
} else {
    echo "<script>alert('Data gagal dihapus'); location.href = '?page=produk';</script>";
}
?>
