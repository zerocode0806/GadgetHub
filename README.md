# GadgetHub

Aplikasi web toko gadget dengan dua sisi: **storefront** (untuk pelanggan berbelanja online) dan **panel admin/kasir** (untuk mengelola produk, transaksi, dan akun). Dibangun dengan PHP prosedural (mysqli) yang mengadopsi pola struktur bergaya Laravel — front controller, route map, controller, dan view — tanpa memakai framework Laravel penuh.

## Fitur

### Storefront (Pelanggan)
- Registrasi & login akun pelanggan
- Melihat daftar produk & detail produk
- Keranjang belanja (tambah, ubah jumlah, hapus)
- Checkout & riwayat pesanan
- Profil akun pelanggan

### Panel Admin / Kasir
- Dashboard admin
- Manajemen akun (admin, petugas/kasir, user)
- Manajemen data pelanggan (khusus transaksi kasir/offline)
- Manajemen produk (tambah, ubah, hapus)
- Manajemen penjualan & detail penjualan, update status pesanan
- Cetak struk transaksi
- Export data penjualan ke Excel (PHPSpreadsheet)
- Pengaturan (settings) aplikasi

## Teknologi

- PHP 8+ dengan ekstensi `mysqli`
- MySQL
- `phpoffice/phpspreadsheet` (export Excel)
- Bootstrap 5 + Font Awesome 6.5.2 (CDN)
- Tanpa framework — struktur folder mengikuti pola ala-Laravel secara manual

## Struktur Folder

```
public/index.php          Dispatcher / front controller — semua request lewat sini
public/.htaccess          Rewrite rule ke dispatcher
routes/web.php             Tabel routing (route lama berakhiran .php tetap dikenali sebagai alias)
app/Http/Controllers/      Logika request per domain: admin, auth, storefront, api
resources/views/           Tampilan (view) per domain: admin, auth, storefront, api, errors
app/Support/                bootstrap.php, koneksi.php (koneksi database), helper bersama
public/css, public/js       Aset statis
public/assets, public/uploads   Gambar & file upload
database/schema.sql          Skema database
database/migrations          Migrasi tambahan
gadgethub-db.sql             Dump database lengkap (skema + data awal)
```

## Instalasi & Menjalankan

1. **Siapkan database.** Buat database MySQL baru, lalu impor `gadgethub-db.sql`.
2. **Samakan nama database.** Saat ini `app/Support/koneksi.php` mengarah ke database bernama `ukk_kasir`:
   ```php
   $koneksi = mysqli_connect("localhost", "root", "", "ukk_kasir");
   ```
   Beri nama database hasil import sesuai itu (`ukk_kasir`), **atau** ubah baris di atas agar sesuai nama database yang kamu pakai (mis. `gadgethub-db`).
3. **Sesuaikan kredensial** host/user/password MySQL di `app/Support/koneksi.php` bila berbeda dari default (`localhost` / `root` / tanpa password).
4. **Jalankan server lokal:**
   ```bash
   php -S 127.0.0.1:8000 -t public public/index.php
   ```
   atau arahkan document root web server ke folder `public/`.
5. Buka `http://127.0.0.1:8000/` di browser.

## Peran & Hak Akses

| Level (kolom `level` di tabel `user`) | Deskripsi |
|---|---|
| `admin` | Akses penuh ke semua halaman panel admin |
| `petugas` | Kasir — akses terbatas ke fitur transaksi/penjualan |
| `user` | Pelanggan storefront — belanja, checkout, lihat pesanan sendiri |

> Catatan: tabel `pelanggan` (nama_pelanggan, alamat, no_telepon) terpisah dari tabel `user`. Tabel ini menyimpan data pembeli walk-in untuk transaksi kasir dan **tidak** memiliki login — berbeda dari akun `user` di atas yang bisa login ke storefront.

## Akun Demo (Seed Accounts)

| Role | Nama | Username | Password |
|---|---|---|---|
| Admin | Ubed Dahlan | `ubeddahlan` | `krian123` |
| User (Pelanggan) | Ferdian Renaldy | `ferdian` | `ferdian123` |
| User (Pelanggan) | Angga | `angga` | `angga123` |

Akun `ubeddahlan` dan `ferdian` sudah ada di `gadgethub-db.sql`, tetapi hash password di dalam dump itu **belum tentu cocok** dengan password di atas. Akun `angga` **belum ada** di dump dan perlu ditambahkan. Jalankan SQL berikut setelah database di-import agar ketiga akun benar-benar bisa login dengan password pada tabel di atas:

```sql
-- Selaraskan password admin (ubeddahlan) dan user (ferdian) yang sudah ada
UPDATE `user` SET `password` = '$2b$10$z1.7iETlvBTZGkeN2mnwKOy.170xiyIwmGktt2WTAoHl9GS1zm93q' WHERE `username` = 'ubeddahlan'; -- krian123
UPDATE `user` SET `password` = '$2b$10$OLEmHJPgWE7sx0nxeAYSEevDqmbfwWa.q8Ky2pnEXXMmAhLaR7l.e' WHERE `username` = 'ferdian';    -- ferdian123

-- Tambahkan akun baru: angga (level user)
INSERT INTO `user` (`nama`, `username`, `no_telepon`, `alamat`, `password`, `level`)
VALUES ('Angga', 'angga', NULL, NULL, '$2b$10$DN6IldsvrDZrnbv.F1WK4.ELtyfn1g.q0j9rWWW86HVMcsBhF2omW', 'user');
```

Hash di atas dibuat dengan bcrypt (cost 10) dan kompatibel dengan `password_verify()` di PHP.

> ⚠️ **Keamanan:** ini adalah akun demo/uji coba untuk pengembangan lokal. Jangan gunakan password di atas pada environment production — ganti semua password sebelum deploy ke server publik.

## Login

- Login admin & kasir: `/login`
- Registrasi akun pelanggan baru: `/register`
- Setelah login, admin/kasir diarahkan ke panel admin (`/admin/home`), sedangkan user diarahkan ke storefront (`/shop`).
