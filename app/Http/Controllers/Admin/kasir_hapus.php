<?php
    $id = $_GET['id'];
    $query = mysqli_query($koneksi, "DELETE FROM pelanggan WHERE id_pelanggan=$id");
    if ($query) {
        echo "<script>alert('Data berhasil di hapus'); location.href = '?page=kasir';</script>";
    } else {
        echo "<script>alert('Data gagal di hapus'); location.href = '?page=kasir';</script>";
    }
?>