<?php
require_once APP_ROOT . '/app/Support/user_helpers.php';
user_require_customer();
$page_title = 'Pesanan saya'; $active_page = 'pesanan'; $id_user = (int) $_SESSION['id_user'];
$stmt = mysqli_prepare($koneksi, 'SELECT p.id_penjualan, p.tanggal_penjualan, p.total_harga, p.metode, p.status, pl.nama_pelanggan FROM penjualan p LEFT JOIN pelanggan pl ON pl.id_pelanggan = p.id_pelanggan WHERE p.id_kasir = ? ORDER BY p.id_penjualan DESC'); mysqli_stmt_bind_param($stmt, 'i', $id_user); mysqli_stmt_execute($stmt); $orders = mysqli_stmt_get_result($stmt);
include APP_ROOT . '/resources/views/storefront/header.php';
?>
<div class="page-title"><h1>Pesanan saya</h1><div class="muted">Pantau transaksi dan status pesananmu.</div></div><div class="order-list"><?php if (mysqli_num_rows($orders) === 0): ?><div class="panel"><p class="muted">Belum ada pesanan.</p><a class="btn btn-primary" href="user_produk.php">Mulai belanja</a></div><?php else: while ($order = mysqli_fetch_assoc($orders)): ?><article class="order-item"><div><strong>Pesanan #<?= (int) $order['id_penjualan']; ?></strong><span class="muted order-date"><?= e(date('d M Y', strtotime($order['tanggal_penjualan']))); ?> &middot; <?= e($order['metode'] ?: 'Cash'); ?></span></div><span class="status <?= in_array($order['status'], ['Proses', 'Dibatalkan'], true) ? 'process' : ''; ?>"><?= e(user_order_status_label($order['status'])); ?></span><strong class="order-total"><?= user_money($order['total_harga']); ?></strong><a class="btn btn-light order-link" href="pesanan_detail.php?id=<?= (int) $order['id_penjualan']; ?>">Detail</a></article><?php endwhile; endif; ?></div>
<?php include APP_ROOT . '/resources/views/storefront/footer.php'; ?>
