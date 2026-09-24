<?php
include('koneksi.php'); // Pastikan koneksi database sudah ada

$search = isset($_GET['search']) ? $_GET['search'] : '';

// Query untuk mencari produk
$query = "SELECT * FROM produk WHERE nama_produk LIKE '%$search%'";
$pro = mysqli_query($koneksi, $query);

if ($pro) {
    while ($produk = mysqli_fetch_array($pro)) {
        ?>
        <div class="col-md-4 mb-4">
            <div class="card product-card">
                <img src="uploads/<?php echo $produk['gambar_produk']; ?>" class="card-img-top" alt="<?php echo $produk['nama_produk']; ?>">
                <div class="product-card-body">
                    <h5 class="product-title"><?php echo $produk['nama_produk'] . ' (Stok: ' . $produk['stok'] . ')'; ?></h5>
                    <p class="product-price">IDR <?php echo number_format($produk['harga'], 0, ',', '.'); ?></p>
                    <p class="product-description"><?php echo nl2br($produk['deskripsi_produk']); ?></p>

                    <div class="quantity-control">
                        <form method="POST" action="">
                            <input type="hidden" name="id_produk" value="<?php echo $produk['id_produk']; ?>" />
                            <input type="hidden" name="nama_produk" value="<?php echo $produk['nama_produk']; ?>" />
                            <input type="hidden" name="harga" value="<?php echo $produk['harga']; ?>" />
                            <input type="hidden" name="stok" value="<?php echo $produk['stok']; ?>" />
                            
                            <!-- Hanya input jumlah -->
                            <input type="number" class="form-control" name="jumlah" min="1" max="<?php echo $produk['stok']; ?>" value="1" />
                            
                            <button type="submit" class="btn btn-secondary"><i class="fas fa-cart-plus"></i> Tambah ke Keranjang</button>
                        </form>
                    </div>


                </div>
            </div>
        </div>
        <?php
    }
}

// Proses data dari form

// Periksa apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    echo "<script>
        alert('Session tidak ditemukan, silakan login terlebih dahulu!');
        window.location = 'login.php';
    </script>";
    exit();
}

// Ambil data user dari session
$id_user = $_SESSION['id_user']; // ID user yang login

// Proses data dari form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data produk dan jumlah dari form
    $id_produk = (int)$_POST['id_produk'];
    $nama_produk = mysqli_real_escape_string($koneksi, $_POST['nama_produk']);
    $harga = (int)$_POST['harga'];
    $jumlah = (int)$_POST['jumlah'];
    $stok = (int)$_POST['stok'];

    // Periksa apakah jumlah yang diminta tidak melebihi stok
    if ($jumlah > $stok) {
        echo "<script>
            alert('Jumlah yang diminta melebihi stok yang tersedia!');
            window.location.href = '?page=pembelian';
        </script>";
        exit();
    }

    // Periksa apakah produk sudah ada dalam keranjang (tabel cart)
    $check_cart_query = "SELECT * FROM cart WHERE id_user = '$id_user' AND id_produk = '$id_produk'";
    $check_cart_result = mysqli_query($koneksi, $check_cart_query);

    if (mysqli_num_rows($check_cart_result) > 0) {
        // Jika produk sudah ada, update jumlahnya
        $cart_item = mysqli_fetch_assoc($check_cart_result);
        $new_jumlah = $cart_item['jumlah'] + $jumlah;
        if ($new_jumlah > $stok) {
            echo "<script>
                alert('Jumlah total melebihi stok yang tersedia!');
                window.location.href = '?page=pembelian';
            </script>";
            exit();
        }

        // Update jumlah barang di keranjang
        $update_cart_query = "UPDATE cart SET jumlah = '$new_jumlah' WHERE id_user = '$id_user' AND id_produk = '$id_produk'";
        mysqli_query($koneksi, $update_cart_query);
    } else {
        // Jika produk belum ada di keranjang, insert data baru
        $total_harga = $jumlah * $harga;
        $insert_cart_query = "INSERT INTO cart (id_user, id_produk, nama_produk, harga, jumlah, total_harga) 
                              VALUES ('$id_user', '$id_produk', '$nama_produk', '$harga', '$jumlah', '$total_harga')";
        mysqli_query($koneksi, $insert_cart_query);
    }

    // Setelah berhasil, alihkan ke halaman keranjang
    echo "<script>
        alert('Produk berhasil ditambahkan ke keranjang!');
        window.location.href = '?page=chekout';
    </script>";
}
?>



