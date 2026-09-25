<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
$id = $_GET['id'];

    if (isset($_POST['nama_pelanggan'])) {
        $nama = $_POST['nama_pelanggan'];
        $alamat = $_POST['alamat'];
        $no_telepon = $_POST['no_telepon'];

        $query = mysqli_query($koneksi, "UPDATE pelanggan SET nama_pelanggan = '$nama', alamat = '$alamat', no_telepon = '$no_telepon' WHERE id_pelanggan=$id");
        if ($query) {
            echo "<script>alert('Data berhasil di ubah'); location.href = '/admin/cashiers';</script>";
        } else {
            echo "<script>alert('Data gagal di ubah'); location.href = '/admin/cashiers/edit';</script>";
        }
    }

    $query = mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE id_pelanggan=$id");
    $data = mysqli_fetch_array($query);
require APP_ROOT . '/resources/views/admin/kasir_ubah.php';
