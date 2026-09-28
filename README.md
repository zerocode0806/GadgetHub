# Aplikasi Kasir Ubeddahlan

Proyek PHP/MySQL procedural ini mengadopsi pola struktur bergaya Laravel—front controller, route map, controller, dan view—tanpa mengganti aplikasi menjadi framework Laravel penuh. Tidak ada berkas PHP shim di root proyek; semua permintaan web melewati `public/index.php`.

## Struktur

- `public/index.php`: dispatcher/front controller; `public/.htaccess` dan root `.htaccess` mengarahkan route ke dispatcher.
- `routes/web.php`: tabel route modern. Route lama berakhiran `.php` tetap dikenali oleh router sebagai alias agar bookmark lama tidak putus.
- `app/Http/Controllers/{admin,auth,storefront,api}`: pemrosesan request dan data.
- `resources/views/{admin,auth,storefront,api,errors}`: markup tampilan per domain.
- `app/Support`: bootstrap, koneksi MySQL, dan helper bersama.
- `public/{css,js,assets,uploads}`: stylesheet, script, gambar, dan unggahan.
- `database/schema.sql` dan `database/migrations`: skema serta migrasi.

## Menjalankan

1. Atur document root ke folder `public/` (direkomendasikan), atau gunakan root proyek dengan `.htaccess` dan `mod_rewrite` aktif.
2. Untuk instalasi baru, buat database MySQL `ukk_kasir` lalu impor `database/schema.sql`. Untuk database lama yang sudah berisi data, jangan impor ulang dump. Jika kolom kategori/spesifikasi belum ada, jalankan migrasi `2026_09_28_000000_product_category_specifications.sql`, lalu selalu jalankan `2026_09_28_000001_categories_and_reviews.sql` satu kali untuk tabel kategori dan ulasan. Migrasi storefront `2025_02_07_000000_user_storefront.sql` hanya perlu dijalankan bila kolomnya belum tersedia. Jalankan juga migrasi `2026_09_28_000002_demo_payment_methods.sql` satu kali untuk mengaktifkan opsi COD, QRIS, dan Bank di checkout pelanggan. Spesifikasi lama dibiarkan kosong untuk dilengkapi melalui menu Edit Produk.
3. Sesuaikan kredensial lokal di `app/Support/koneksi.php`.
4. Jalankan dengan PHP 8+ dan ekstensi `mysqli`: `php -S 127.0.0.1:8000 -t public public/index.php`.

Font Awesome dimuat melalui stylesheet Font Awesome 6.5.2 CDN di layout dan dokumen HTML. Tema hitam-putih mengikuti `REFERENSI.css`; katalog mendukung filter/pencarian kategori, pengelolaan kategori admin, dan rating/ulasan customer. QRIS dan nomor virtual account pada detail pesanan hanya data simulasi demo dan tidak memproses pembayaran.
