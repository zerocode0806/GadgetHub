<?php
include 'koneksi.php';

// Check if 'id' is passed in the URL
if (isset($_GET['id'])) {
    $id_pelanggan = $_GET['id'];

    // Delete the customer from the pelanggan table
    $delete_pelanggan = mysqli_query($koneksi, "DELETE FROM pelanggan WHERE id_pelanggan = '$id_pelanggan'");

    if ($delete_pelanggan) {
        echo "<script>alert('Pelanggan berhasil dihapus!'); window.location = '?page=pelanggan';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan, coba lagi!'); window.location = '?page=pelanggan';</script>";
    }
} else {
    echo "<script>alert('ID pelanggan tidak valid!'); window.location = '?page=pelanggan';</script>";
}
?>
