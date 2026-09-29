# GadgetHub — Dokumentasi Aplikasi E-commerce

GadgetHub adalah aplikasi web e-commerce katalog gadget yang dilengkapi storefront pelanggan dan panel administrasi. Aplikasi menggunakan PHP prosedural dengan pola **front controller, route map, controller, dan view** serta database MySQL/MariaDB. Proyek ini bukan aplikasi Laravel.

> **Peringatan kredensial demo:** database awal menyediakan akun admin `ubeddahlan` dengan kata sandi `krian123`. Kredensial ini diminta untuk kebutuhan demonstrasi dan sudah diverifikasi terhadap hash password pada seed database. Jangan gunakan akun/kata sandi tersebut di server publik atau produksi. Ganti sebelum deployment dan jangan membagikan kredensial kepada pihak yang tidak berwenang.

## Fitur utama

### Storefront pelanggan

- Beranda dan katalog produk gadget.
- Pencarian produk, kategori, halaman detail produk, stok, dan spesifikasi.
- Registrasi dan login akun pelanggan. Form registrasi mengumpulkan nama, username, nomor telepon, alamat, kata sandi, dan konfirmasi kata sandi. Kata sandi baru disimpan sebagai hash.
- Keranjang belanja tersimpan per akun di tabel `keranjang`, termasuk setelah logout dan login kembali. Jumlah barang dibatasi berdasarkan stok.
- Checkout menyimpan snapshot nama/alamat/telepon pembeli dan membuat pesanan beserta detail barang.
- Pelanggan dapat melihat daftar pesanan, status, metode pembayaran, dan detail pesanan miliknya.
- Pelanggan dapat memberikan ulasan/rating produk jika fitur tersedia pada produk tersebut.

### Pembayaran demo

Metode yang ditampilkan saat checkout:

- **Cash On Delivery (COD)**
- **QRIS** — kode QR/payload simulasi
- **Bank** — nomor virtual account simulasi

QRIS dan virtual account **hanya untuk demonstrasi**. Aplikasi ini tidak terhubung dengan payment gateway dan tidak memproses pembayaran sungguhan. Detail kode QR/VA ditampilkan pada halaman detail pesanan ketika status masih `Proses`; setelah status berubah, kode QR/VA disembunyikan, sedangkan status dan metode pembayaran tetap ditampilkan.

### Panel admin dan petugas

- **Admin:** mengelola akun staf (admin/petugas), akun pelanggan, produk, kategori, pengaturan, dan pesanan.
- **Petugas:** melihat pesanan untuk diproses dan memperbarui status sesuai kewenangannya; tidak memperoleh hak pengelolaan admin penuh.
- **Pelanggan storefront:** akun pembeli dengan `level` database `user`, terpisah dari akun admin/petugas.
- Halaman pelanggan merangkum jumlah pesanan dan total belanja per akun, serta menyediakan riwayat transaksi. Kontak transaksi POS lama tanpa akun storefront ditangani terpisah agar tidak tercampur dengan daftar akun.

## Akun demo

Buka halaman `/login`.

| Peran | Username | Kata sandi |
|---|---|---|
| Admin | `ubeddahlan` | `krian123` |

Akun pelanggan dapat dibuat melalui `/register`. Jangan menambahkan kata sandi asli atau rahasia server ke repositori publik. Untuk deployment nyata, ganti kata sandi demo dan tinjau semua akun seed terlebih dahulu.

## Teknologi dan prasyarat

- PHP 8 atau lebih baru.
- Ekstensi PHP `mysqli` (dengan dukungan `mysqlnd` untuk hasil prepared statement).
- MySQL atau MariaDB dengan dukungan InnoDB dan foreign key.
- Apache dengan `mod_rewrite` (opsional untuk hosting Apache), atau server PHP bawaan untuk pengujian lokal.
- Browser modern dengan JavaScript aktif. Beberapa ikon/font dimuat melalui Font Awesome CDN; aplikasi tetap tidak menggunakan payment gateway.

## Instalasi baru

1. Salin folder proyek ke server.
2. Buat database dengan nama yang sesuai dengan konfigurasi bawaan:

   ```sql
   CREATE DATABASE `gadgethub-db`
     CHARACTER SET utf8mb4
     COLLATE utf8mb4_unicode_ci;
   ```

3. Impor schema untuk instalasi baru saja:

   ```bash
   mysql -u root -p gadgethub-db < database/schema.sql
   ```

   `database/schema.sql` adalah schema utama yang direkomendasikan untuk instalasi baru. Jangan mengimpor `database/schema.sql` dan `gadgethub-db.sql` sekaligus ke database yang sama.

4. Periksa koneksi database pada `app/Support/koneksi.php`. Konfigurasi bawaan saat ini memakai host `localhost`, user database `root`, password kosong, dan nama database `gadgethub-db`. Ubah sesuai lingkungan. Untuk server publik, gunakan akun database khusus dengan hak minimum—jangan gunakan `root`.
5. Arahkan document root web server ke folder `public/`. Jika memakai Apache, aktifkan `mod_rewrite` agar URL aplikasi diarahkan melalui front controller.
6. Untuk pengujian lokal dengan PHP bawaan, jalankan dari root proyek:

   ```bash
   php -S 127.0.0.1:8000 -t public public/index.php
   ```

7. Buka `http://127.0.0.1:8000/`, lalu login sebagai admin melalui `/login`.

## Memperbarui database lama

**Jangan impor ulang schema atau dump ke database lama yang sudah berisi data.** Buat backup terlebih dahulu, lalu jalankan hanya migrasi yang belum pernah diterapkan. File migrasi berupa SQL biasa dan dijalankan satu kali, berurutan, melalui MySQL/MariaDB atau phpMyAdmin.

Urutan migrasi yang disediakan:

1. `database/migrations/2025_02_07_000000_user_storefront.sql` — jalankan hanya jika kolom profil storefront yang dibutuhkan belum ada.
2. `database/migrations/2026_09_28_000000_product_category_specifications.sql` — kolom kategori/spesifikasi produk, jika belum ada.
3. `database/migrations/2026_09_28_000001_categories_and_reviews.sql` — tabel kategori dan ulasan produk.
4. `database/migrations/2026_09_28_000002_demo_payment_methods.sql` — nilai metode pembayaran demo COD, QRIS, dan Bank.
5. `database/migrations/2026_09_28_000003_link_customers_to_accounts.sql` — menghubungkan snapshot pelanggan pesanan dengan akun storefront.
6. `database/migrations/2026_09_29_000004_persistent_cart_and_foreign_keys.sql` — keranjang persisten dan foreign key.

Jalankan migrasi yang relevan sesuai kondisi database; jangan menjalankan ulang migrasi yang menambah kolom atau constraint yang sudah ada. Migrasi terakhir menormalkan kategori, menggabungkan baris `keranjang` duplikat, serta membersihkan relasi keranjang/ulasan yang sudah tidak memiliki induk valid. Referensi transaksi lama yang induknya hilang dijadikan `NULL` agar histori penjualan tetap tersimpan. Karena itu, **backup database sebelum menjalankan migrasi**.

## Keranjang dan relasi database

- Storefront membaca dan menulis keranjang akun pada tabel `keranjang`, dengan satu baris unik per kombinasi akun-produk.
- Tabel `cart` lama dipertahankan untuk kompatibilitas fitur kasir; tabel itu bukan keranjang storefront.
- Checkout menghapus baris keranjang pelanggan dalam transaksi yang sama setelah pesanan berhasil dibuat.
- Foreign key menjaga relasi keranjang, pelanggan, penjualan, detail penjualan, produk, kategori, dan ulasan. Aturan `CASCADE`, `SET NULL`, atau `RESTRICT` dipilih sesuai kebutuhan untuk membersihkan data turunan atau menjaga histori.
- Tabel yang berdiri sendiri dan tidak memiliki relasi induk, misalnya pengaturan aplikasi, tidak membutuhkan foreign key.

## Route penting

| URL | Kegunaan |
|---|---|
| `/` atau `/shop` | Beranda storefront |
| `/products` | Katalog produk |
| `/product?id=<id>` | Detail produk |
| `/register` | Registrasi pelanggan |
| `/login` dan `/logout` | Masuk dan keluar |
| `/cart` | Keranjang pelanggan |
| `/checkout/customer` | Checkout storefront |
| `/orders` | Daftar pesanan pelanggan |
| `/orders/detail?id=<id>` | Detail pesanan milik pelanggan yang login |
| `/dashboard` | Panel admin/petugas sesuai peran |
| `/admin/users` | Akun staf (admin) |
| `/admin/customers` | Data akun pelanggan dan ringkasan transaksi (admin) |
| `/admin/products` dan `/admin/categories` | Katalog serta kategori (admin) |
| `/admin/sales` | Daftar pesanan untuk pengelolaan (admin/petugas sesuai kewenangan) |
| `/admin/settings` | Pengaturan aplikasi (admin) |

Route lama berakhiran `.php` dapat dikenali sebagai alias oleh router untuk kompatibilitas bookmark lama.

## Struktur proyek

- `public/index.php` — front controller/dispatcher.
- `routes/web.php` — pemetaan URL ke controller dan halaman.
- `app/Http/Controllers/admin` — alur panel admin/petugas.
- `app/Http/Controllers/auth` — login, logout, dan registrasi.
- `app/Http/Controllers/storefront` — katalog, keranjang, checkout, profil, dan pesanan pelanggan.
- `app/Http/Controllers/api` — endpoint API aplikasi.
- `app/Support` — koneksi database, bootstrap, dan helper bersama.
- `resources/views` — tampilan admin, autentikasi, storefront, dan API.
- `public/css`, `public/js`, `public/assets`, `public/uploads` — aset web.
- `database/schema.sql` — schema utama untuk instalasi baru.
- `database/migrations` — perubahan schema untuk database yang sudah ada.

## Catatan keamanan dan batasan

- Kredensial admin di atas adalah kredensial seed/demo; ganti sebelum aplikasi dapat diakses publik.
- Jangan commit password database, token, atau kredensial produksi ke repositori.
- QRIS/virtual account hanya simulasi dan tidak membuktikan pembayaran.
- Gunakan HTTPS dan akun database khusus pada deployment publik; tinjau juga permission upload dan konfigurasi session server.
- Pastikan backup tersedia sebelum menjalankan migrasi yang membersihkan data orphan.
