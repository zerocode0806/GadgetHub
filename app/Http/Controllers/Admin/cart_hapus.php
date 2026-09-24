<?php
session_start();
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id_produk = $_GET['id'];
    $id_user = $_SESSION['id_user'];

    $query = "DELETE FROM cart WHERE id_produk = '$id_produk' AND id_user = '$id_user'";
    mysqli_query($koneksi, $query);

    echo "<script>
        alert('Produk berhasil dihapus dari keranjang!');
        window.location.href = '?page=checkout';
    </script>";
} else {
    echo "<script>
        alert('ID produk tidak ditemukan!');
        window.location.href = '?page=checkout';
    </script>";
}
?>
