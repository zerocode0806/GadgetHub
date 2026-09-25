<?php
require_once APP_ROOT . '/app/Support/koneksi.php';

// Check if 'id' is passed in the URL
if (isset($_GET['id'])) {
    $id_pelanggan = $_GET['id'];

    // Fetch customer data based on 'id'
    $query = mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE id_pelanggan = '$id_pelanggan'");
    $data = mysqli_fetch_array($query);

    // Check if customer data is found
    if (!$data) {
        echo "<script>alert('Pelanggan tidak ditemukan!'); window.location = '/admin/customers';</script>";
        exit();
    }
} else {
    echo "<script>alert('ID tidak valid!'); window.location = '/admin/customers';</script>";
    exit();
}

// Update data if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $alamat = $_POST['alamat'];
    $no_telepon = $_POST['no_telepon'];

    // Update the customer data in the database
    $sql = "UPDATE pelanggan SET nama_pelanggan = '$nama_pelanggan', alamat = '$alamat', no_telepon = '$no_telepon' WHERE id_pelanggan = '$id_pelanggan'";
    $result = mysqli_query($koneksi, $sql);

    if ($result) {
        echo "<script>alert('Pelanggan berhasil diperbarui!'); window.location = '/admin/customers';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan, coba lagi!'); window.location = '/admin/customers/edit?id=$id_pelanggan';</script>";
    }
}
require APP_ROOT . '/resources/views/admin/pelanggan_ubah.php';
