<?php
include 'koneksi.php';

$id = $_GET['id']; // Mendapatkan id penjualan dari URL
$query = mysqli_query($koneksi, "SELECT penjualan.*, pelanggan.nama_pelanggan, user.nama AS nama_kasir 
    FROM penjualan 
    LEFT JOIN pelanggan ON pelanggan.id_pelanggan = penjualan.id_pelanggan 
    LEFT JOIN user ON user.id_user = penjualan.id_kasir
    WHERE id_penjualan=$id");
$data = mysqli_fetch_array($query);

$pro = mysqli_query($koneksi, "SELECT * FROM detail_penjualan 
    LEFT JOIN produk ON produk.id_produk = detail_penjualan.id_produk 
    WHERE id_penjualan=$id");

$query = "SELECT business_name, address, phone, logo FROM settings WHERE id = 1";
$result = mysqli_query($koneksi, $query);
$settings = mysqli_fetch_assoc($result);

// Calculate total quantity
$total_qty = 0;
$detail_result = mysqli_query($koneksi, "SELECT jumlah_produk FROM detail_penjualan WHERE id_penjualan=$id");
while ($row = mysqli_fetch_assoc($detail_result)) {
    $total_qty += $row['jumlah_produk'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembelian</title>
    <style>
        @page {
            size: 80mm auto; /* Thermal printer size */
            margin: 5mm 0mm 5mm 5mm; /* Adjust left margin */
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            font-weight: bold;
            overflow-x: hidden;
            text-align: left;
        }

        .receipt {
            width: 100%;
            max-width: 300px;
            margin: 0;
            padding: 10px;
            border: 1px dashed #000;
            box-sizing: border-box;
        }

        .header, .footer {
            text-align: left;
        }

        .header img {
            width: 80px;
            height: auto;
            display: block;
            margin-left: 0;
        }

        .details, .items, .total-section {
            width: 100%;
            margin-bottom: 10px;
            text-align: left;
        }

        .items th, .items td, .total-section td {
            text-align: left;
        }

        .total {
            text-align: left;
        }

        .separator {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
    </style>
    <script>
        // Print the page automatically when it loads
        window.onload = function() {
            window.print();
        };
    </script>
    <link rel="stylesheet" href="css/theme.css">
</head>
<body>
    <div class="receipt">
        <div class="header">
            <img src="uploads/<?php echo $settings['logo']; ?>" alt="Logo">
            <h2><?php echo $settings['business_name']; ?></h2>
            <p><?php echo $settings['address']; ?><br>Tel: <?php echo $settings['phone']; ?></p>
        </div>
        
        <div class="separator"></div>

        <div class="details">
            <table>
                <tr>
                    <td>No. Invoice</td>
                    <td>: <?php echo $data['id_penjualan']; ?></td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>: <?php echo $data['tanggal_penjualan']; ?></td>
                </tr>
                <tr>
                    <td>Nama Kasir</td>
                    <td>: <?php echo $data['nama_kasir']; ?></td>
                </tr>
            </table>
        </div>
        
        <div class="separator"></div>

        <div class="items">
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    while ($produk = mysqli_fetch_array($pro)): ?>
                    <tr>
                        <td><?php echo $produk['nama_produk']; ?></td>
                        <td><?php echo $produk['jumlah_produk']; ?></td>
                        <td>Rp <?php echo number_format($produk['sub_total'], 0, ',', '.'); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        
        <div class="separator"></div>

        <div class="highlight">
            <p>Total QTY: <strong><?php echo $total_qty; ?></strong></p>
        </div>

        <div class="separator"></div>

        <div class="total-section">
            <table>
                <tr>
                    <td>Total</td>
                    <td>: <strong>Rp <?php echo number_format($data['total_harga'], 0, ',', '.'); ?></strong></td>
                </tr>
                <tr>
                    <td>Bayar</td>
                    <td>: <strong>Rp <?php echo number_format($data['bayar'], 0, ',', '.'); ?></strong></td>
                </tr>
                <tr>
                    <td>Kembali</td>
                    <td>: <strong>Rp <?php echo number_format($data['kembali'], 0, ',', '.'); ?></strong></td>
                </tr>
            </table>
        </div>

        <div class="separator"></div>

        <div class="footer">
            <p>Terima kasih atas kunjungan Anda!</p>
            <p>*** Struk ini adalah bukti pembayaran yang sah ***</p>
        </div>
    </div>
</body>
</html>
