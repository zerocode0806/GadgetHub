<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
if (isset($_POST['nama_pelanggan'])) {
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $no_telepon = $_POST['no_telepon'];
    $alamat = $_POST['alamat'];

    // Insert data ke database
    $query = mysqli_query($koneksi, "INSERT INTO pelanggan (nama_pelanggan, no_telepon, alamat) 
                                      VALUES ('$nama_pelanggan', '$no_telepon', '$alamat')");
    if ($query) {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Pelanggan berhasil ditambahkan',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = '/admin/customers';
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Gagal menambahkan pelanggan'
                });
              </script>";
    }
}
require APP_ROOT . '/resources/views/admin/pelanggan_tambah.php';
