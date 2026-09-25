<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
$id = $_GET['id'];
    $query = mysqli_query($koneksi, "DELETE FROM pelanggan WHERE id_pelanggan=$id");
    if ($query) {
        echo "<script>alert('Data berhasil di hapus'); location.href = '/admin/cashiers';</script>";
    } else {
        echo "<script>alert('Data gagal di hapus'); location.href = '/admin/cashiers';</script>";
    }
