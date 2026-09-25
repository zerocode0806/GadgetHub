<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
if (isset($_POST['nama_produk'])) {
    $nama = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $deskripsi = $_POST['deskripsi_produk'];

    // Proses upload gambar
    $gambar = $_FILES['gambar_produk']['name'];
    $gambar_tmp = $_FILES['gambar_produk']['tmp_name'];
    $gambar_path = APP_ROOT . '/public/uploads/' . $gambar;

    // Memindahkan file gambar ke folder uploads
    move_uploaded_file($gambar_tmp, $gambar_path);

    // Menyimpan data produk ke database
    $query = mysqli_query($koneksi, "INSERT INTO produk(nama_produk,harga,stok,gambar_produk,deskripsi_produk) 
                                   VALUES('$nama','$harga','$stok','$gambar','$deskripsi')");
    if ($query) {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Produk berhasil ditambahkan',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = '/admin/products';
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Gagal menambahkan produk'
                });
              </script>";
    }
}
require APP_ROOT . '/resources/views/admin/produk_tambah.php';
