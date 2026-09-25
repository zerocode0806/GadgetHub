<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
if (isset($_POST['nama_pelanggan'])) {
        $nama = $_POST['nama_pelanggan'];
        $alamat = $_POST['alamat'];
        $no_telepon = $_POST['no_telepon'];

        $query = mysqli_query($koneksi, "INSERT INTO pelanggan(nama_pelanggan,alamat,no_telepon) VALUES('$nama', '$alamat', '$no_telepon')");
        if ($query) {
            echo "<script>alert('Data berhasil ditambahkan');</script>";
        } else {
            echo "<script>alert('Data gagal ditambahkan');</script>";
        }
    }
require APP_ROOT . '/resources/views/admin/kasir_tambah.php';
