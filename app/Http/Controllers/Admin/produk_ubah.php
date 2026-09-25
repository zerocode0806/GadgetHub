<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
$id = $_GET['id'];

    if (isset($_POST['nama_produk'])) {
        $nama = $_POST['nama_produk'];
        $harga = $_POST['harga'];
        $stok = $_POST['stok'];
        $deskripsi = $_POST['deskripsi_produk'];

        // If new image is uploaded
        if ($_FILES['gambar_produk']['name']) {
            $gambar = $_FILES['gambar_produk']['name'];
            $gambar_tmp = $_FILES['gambar_produk']['tmp_name'];
            $gambar_path = APP_ROOT . '/public/uploads/' . $gambar;

            // Move the uploaded image
            move_uploaded_file($gambar_tmp, $gambar_path);
            
            $query = mysqli_query($koneksi, "UPDATE produk SET 
                nama_produk = '$nama', 
                harga = '$harga', 
                stok = '$stok', 
                gambar_produk = '$gambar', 
                deskripsi_produk = '$deskripsi' 
                WHERE id_produk=$id");
        } else {
            $query = mysqli_query($koneksi, "UPDATE produk SET 
                nama_produk = '$nama', 
                harga = '$harga', 
                stok = '$stok', 
                deskripsi_produk = '$deskripsi' 
                WHERE id_produk=$id");
        }

        if ($query) {
            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Produk berhasil diperbarui',
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
                        text: 'Gagal memperbarui produk'
                    });
                  </script>";
        }
    }

    $query = mysqli_query($koneksi, "SELECT * FROM produk WHERE id_produk=$id");
    $data = mysqli_fetch_array($query);
require APP_ROOT . '/resources/views/admin/produk_ubah.php';
