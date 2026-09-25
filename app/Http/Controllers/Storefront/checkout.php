<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['level']) && $_SESSION['level'] === 'user') {
    header('Location: /checkout/customer');
    exit;
}
// Cek apakah pengguna sudah login
if (!isset($_SESSION['id_user']) || !isset($_SESSION['level'])) {
    echo "<script>
        alert('Session tidak ditemukan, silakan login terlebih dahulu!');
        window.location = '/login';
    </script>";
    exit();
}

// Ambil ID user dari session
$id_user = $_SESSION['id_user'];

// Query untuk mengambil produk yang ada dalam keranjang dengan stok produk
$query = "
    SELECT c.*, p.stok
    FROM cart c
    JOIN produk p ON c.id_produk = p.id_produk
    WHERE c.id_user = '$id_user'
";
$result = mysqli_query($koneksi, $query);

// Cek apakah ada data dalam keranjang
$cart_items = [];
$total_harga = 0; // Initialize total_harga to 0
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Simpan data produk ke dalam array cart_items
        $cart_items[$row['id_produk']] = [
            'nama_produk' => $row['nama_produk'],
            'harga' => $row['harga'],
            'stok' => $row['stok'],
            'qty' => $row['jumlah']
        ];
    }
}

// tombol minus
if (isset($_GET['hapus'])) {
    $id_produk_hapus = $_GET['hapus'];
    $query_hapus = "DELETE FROM cart WHERE id_user = '$id_user' AND id_produk = '$id_produk_hapus'";
    mysqli_query($koneksi, $query_hapus);
    echo "<script>
        alert('Produk berhasil dihapus dari keranjang.');
        window.location.href = '/admin/checkout';
    </script>";
    exit();
}


// Jika tombol reset ditekan, hapus semua data di tabel cart untuk user saat ini
if (isset($_POST['reset_cart'])) {
    $query_hapus_cart = "DELETE FROM cart WHERE id_user = '$id_user'";
    mysqli_query($koneksi, $query_hapus_cart);
    echo "<script>
        alert('Keranjang telah dikosongkan.');
        window.location.href = window.location.href;
    </script>";
    exit();
}

// Proses saat tombol "Proses Pembayaran" ditekan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pelanggan = $_POST['id_pelanggan'];
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $alamat = $_POST['alamat'];
    $no_telepon = $_POST['no_telepon'];
    $total_harga = $_POST['total'];
    $bayar = $_POST['bayar'];
    $kembali = $_POST['kembali'];
    
    // Validasi pembayaran
    if ($bayar < $total_harga) {
        echo "<script>
            alert('Jumlah pembayaran tidak mencukupi!');
            window.location.href = '/admin/checkout';
        </script>";
        exit();
    }

    // Proses data pelanggan
    if (!empty($id_pelanggan)) {
        // Gunakan pelanggan yang sudah ada
        $query_pelanggan = mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE id_pelanggan = '$id_pelanggan'");
        $data_pelanggan = mysqli_fetch_assoc($query_pelanggan);
        $nama_pelanggan = $data_pelanggan['nama_pelanggan'];
        $alamat = $data_pelanggan['alamat'];
        $no_telepon = $data_pelanggan['no_telepon'];
    } else if (!empty($nama_pelanggan) && !empty($alamat) && !empty($no_telepon)) {
        // Buat pelanggan baru
        $query_pelanggan = "INSERT INTO pelanggan (nama_pelanggan, alamat, no_telepon) 
                           VALUES ('$nama_pelanggan', '$alamat', '$no_telepon')";
        mysqli_query($koneksi, $query_pelanggan);
        $id_pelanggan = mysqli_insert_id($koneksi);
    } else {
        echo "<script>
            alert('Silakan pilih pelanggan atau isi semua data pelanggan baru!');
            window.location.href = '/admin/checkout';
        </script>";
        exit();
    }

    // Insert ke tabel penjualan
    $query_penjualan = "INSERT INTO penjualan (tanggal_penjualan, id_kasir, total_harga, id_pelanggan, bayar, kembali)
                        VALUES (NOW(), '$id_user', '$total_harga', '$id_pelanggan', '$bayar', '$kembali')";
    $result_penjualan = mysqli_query($koneksi, $query_penjualan);

    if ($result_penjualan) {
        $id_penjualan = mysqli_insert_id($koneksi);

        // Ambil data quantity terbaru dari form
        foreach ($cart_items as $id_produk => $item) {
            $qty_input = $_POST['produk'][$id_produk] ?? $item['qty'];
            $harga = $item['harga'];
            $sub_total = $harga * $qty_input;

            // Insert detail penjualan
            $query_detail = "INSERT INTO detail_penjualan (id_penjualan, id_produk, jumlah_produk, sub_total)
                            VALUES ('$id_penjualan', '$id_produk', '$qty_input', '$sub_total')";
            mysqli_query($koneksi, $query_detail);

            // Update stok produk
            $new_stok = $item['stok'] - $qty_input;
            $query_update_stok = "UPDATE produk SET stok = '$new_stok' WHERE id_produk = '$id_produk'";
            mysqli_query($koneksi, $query_update_stok);
        }

        // Hapus keranjang setelah transaksi selesai
        $query_hapus_cart = "DELETE FROM cart WHERE id_user = '$id_user'";
        mysqli_query($koneksi, $query_hapus_cart);

        echo "<script>
            alert('Pembayaran berhasil! Transaksi telah dicatat.');
            window.location.href = '/admin/sales';
        </script>";
        exit();
    } else {
        echo "<script>alert('Terjadi kesalahan saat memproses pembayaran.');</script>";
    }
}
$customersResult = mysqli_query($koneksi, 'SELECT * FROM pelanggan');
$customers = [];
if ($customersResult) {
    while ($customerRow = mysqli_fetch_assoc($customersResult)) {
        $customers[] = $customerRow;
    }
}
require APP_ROOT . '/resources/views/storefront/checkout.php';
