# Aplikasi Kasir Ubeddahlan

Aplikasi PHP/MySQL ini telah ditata ulang mengikuti pemisahan tanggung jawab bergaya Laravel, tanpa mengganti runtime procedural atau alur bisnis yang telah ada. Nama URL lama (`dashboard.php`, `produk.php`, `user_home.php`, dan lainnya) tetap tersedia melalui front controller kompatibilitas di root.

## Struktur utama

- `app/Http/Controllers/Admin`, `app/Http/Controllers/Auth`, `app/Http/Controllers/Storefront`, dan `app/Http/Controllers/Api`: halaman dan handler aplikasi per domain.
- `app/Support`: bootstrap, koneksi database, serta helper bersama.
- `resources/views/storefront`: partial header/footer storefront.
- `public`: aset CSS, JavaScript, serta gambar statis yang ditata seperti document root Laravel. Salinan aset di `css/`, `js/`, dan `assets/`, serta direktori `uploads/` tetap berada di root agar semua URL lama dan operasi file tetap kompatibel pada XAMPP maupun Linux.
- `database/schema.sql` dan `database/migrations`: skema dan migrasi SQL.
- `vendor`: dependensi Composer yang sudah ada.

## Menjalankan

1. Buat database MySQL `ukk_kasir`, lalu impor `database/schema.sql` (terapkan migrasi storefront bila belum ada).
2. Sesuaikan kredensial MySQL dalam `app/Support/koneksi.php` untuk lingkungan lokal.
3. Jadikan folder proyek sebagai document root, atau jalankan PHP built-in server dari folder proyek: `php -S 127.0.0.1:8000`.
4. Paket ekspor Excel menggunakan dependensi Composer di `vendor/`.

## Tema

`REFERENSI.css` dipertahankan sebagai berkas acuan. Tema bersama pada `public/css/theme.css` menerapkan font Inter, latar putih, teks hitam, serta aksen dan komponen abu-abu netral pada halaman admin, autentikasi, dan storefront. Perubahan hanya pada presentasi; tidak ada fitur aplikasi yang sengaja dihapus atau ditambah.
