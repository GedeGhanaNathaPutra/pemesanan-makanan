# Dokumentasi Lengkap Aplikasi Pemesanan Makanan (FoodOrder)

Dokumentasi arsitektur, basis data, alur bisnis, sistem desain, dan panduan pengoperasian aplikasi pemesanan dan pengantaran makanan multi-peran (**FoodOrder**) berbasis **Laravel 12**, **Bootstrap 5.3**, dan **MySQL**.

---

## Daftar Isi
1. [Ringkasan Eksekutif](#1-ringkasan-eksekutif)
2. [Tech Stack & Pustaka Utama](#2-tech-stack--pustaka-utama)
3. [Peran Pengguna & Matriks Hak Akses](#3-peran-pengguna--matriks-hak-akses)
4. [Siklus Hidup & State Machine Pesanan](#4-siklus-hidup--state-machine-pesanan)
5. [Skema Basis Data & Relasi Eloquent](#5-skema-basis-data--relasi-eloquent)
6. [Aturan Bisnis Kritis & Keamanan](#6-aturan-bisnis-kritis--keamanan)
7. [Peta Rute & Pengendali (Route Map)](#7-peta-rute--pengendali-route-map)
8. [Design System: Liquid Glass & Identitas Brand](#8-design-system-liquid-glass--identitas-brand)
9. [Panduan Instalasi & Pengujian](#9-panduan-instalasi--pengujian)
10. [Kredensial Akun Demo](#10-kredensial-akun-demo)

---

## 1. Ringkasan Eksekutif

**FoodOrder** adalah platform web terpadu untuk ekosistem kuliner yang menghubungkan tiga entitas utama:
- **Pelanggan (Customer)** yang mencari kuliner lokal, memesan hidangan favorit, melakukan pembayaran digital/manual, dan melacak pesanan.
- **Mitra Restoran (Owner)** yang mengelola profil gerai, katalog menu makanan, ketersediaan stok, dan memproses masakan.
- **Pengelola Platform (Admin)** yang memverifikasi mitra restoran, mengelola kategori kuliner global, serta memonitor seluruh transaksi sistem.

Aplikasi dirancang dengan pemisahan peran yang tegas, integritas transaksi stok tanpa *race condition*, dan antarmuka **Liquid Glass Light Mode** yang modern dan responsif.

---

## 2. Tech Stack & Pustaka Utama

| Komponen | Teknologi | Versi / Rincian |
| :--- | :--- | :--- |
| **Backend Framework** | Laravel | 12.x (PHP 8.2+) |
| **Database Engine** | MySQL / MariaDB | InnoDB engine dengan foreign key cascade |
| **Frontend Framework** | Bootstrap | 5.3.3 (Liquid Glass UI token styling) |
| **Ikonografi** | Bootstrap Icons | 1.11.3 (Pengganti seluruh emoji untuk tampilan profesional) |
| **Tipografi** | Plus Jakarta Sans | Google Fonts (Weight: 400, 500, 600, 700, 800) |
| **Asset Bundler** | Vite | 6.x |
| **CSS Utilities** | Tailwind CSS / Bootstrap Utility | v4 hybrid utility support |

---

## 3. Peran Pengguna & Matriks Hak Akses

Sistem menggunakan kolom `role` berformat `enum('admin', 'owner', 'customer')` pada tabel `users`.

```
                  ┌─────────────────────────────────────────┐
                  │               User Roles                │
                  └────┬─────────────────┬─────────────┬────┘
                       │                 │             │
                       ▼                 ▼             ▼
                 ┌──────────┐      ┌───────────┐  ┌─────────┐
                 │ Customer │      │   Owner   │  │  Admin  │
                 └──────────┘      └───────────┘  └─────────┘
```

### Matriks Akses Fitur:

| Fitur / Modul | Guest (Tamu) | Customer | Owner | Admin |
| :--- | :---: | :---: | :---: | :---: |
| Jelajah Restoran & Katalog Menu | Ya | Ya | Ya | Ya |
| Rincian Menu & Porsi Makanan | Ya | Ya | Ya | Ya |
| Tambah Item ke Keranjang Belanja | Tidak (Redirect Login) | Ya | Tidak | Tidak |
| Checkout & Pembayaran Pesanan | Tidak | Ya | Tidak | Tidak |
| Unggah Bukti Transfer & Batalkan Pesanan | Tidak | Ya (Pesanan Milik Sendiri) | Tidak | Tidak |
| Beri Rating & Ulasan (Pesanan Selesai) | Tidak | Ya | Tidak | Tidak |
| Kelola Profil & Jam Buka Restoran Sendiri | Tidak | Tidak | Ya | Tidak |
| Manajemen Menu Makanan (CRUD, Stok, Harga) | Tidak | Tidak | Ya (Restoran Sendiri) | Tidak |
| Penerimaan & Pembaruan Status Pesanan | Tidak | Tidak | Ya (Pesanan Restoran) | Ya (Semua) |
| Verifikasi Bukti Pembayaran | Tidak | Tidak | Ya | Ya |
| Manajemen Pengguna (CRUD User) | Tidak | Tidak | Tidak | Ya |
| Kelola Restoran Mitra (Approve / CRUD) | Tidak | Tidak | Tidak | Ya |
| Kelola Kategori Makanan Global | Tidak | Tidak | Tidak | Ya |
| Monitoring Seluruh Transaksi Pesanan | Tidak | Tidak | Tidak | Ya |

---

## 4. Siklus Hidup & State Machine Pesanan

Setiap pesanan berpindah melalui status terukur yang mengikat mutasi stok dan saldo:

```
[Customer Checkout]
        │
        ▼
   ┌─────────┐
   │ PENDING │ ◄── Menunggu pembayaran atau konfirmasi awal
   └────┬────┘
        │
   ┌────┴────────────────────────┐
   │                             │
   ▼ (Restoran Terima & Masak)   ▼ (Customer / Restoran / Admin Batalkan)
┌───────────┐             ┌────────────┐
│ DIPROSES  │             │ DIBATALKAN │ ◄── [Otomatis Rollback Stok Produk]
└─────┬─────┘             └────────────┘
      │
      ▼ (Makanan Siap & Diserahkan ke Kurir)
┌───────────┐
│  DIKIRIM  │ ◄── Kurir/Driver membawa makanan ke alamat pelanggan
└─────┬─────┘
      │
      ▼ (Diterima Pembeli)
┌───────────┐
│  SELESAI  │ ◄── Transaksi tuntas, customer berhak memberi Review & Rating (1-5)
└───────────┘
```

### Aturan Transisi:
1. **`pending`**: Tercipta saat checkout berhasil. Stok otomatis terpotong via `lockForUpdate`. Item keranjang dikosongkan.
2. **`diproses`**: Diubah oleh Owner saat menerima pesanan atau setelah pembayaran terverifikasi.
3. **`dikirim`**: Diubah saat makanan telah matang dan dalam perjalanan bersama kurir.
4. **`selesai`**: Ditandai saat pesanan diterima pemesan. Customer membuka akses form rating/ulasan.
5. **`dibatalkan`**: Dapat dipicu oleh Customer (jika belum diproses), Owner, atau Admin. **Wajib mengeksekusi pengembalian (rollback) stok ke seluruh produk terkait dalam satu transaksi database.**

---

## 5. Skema Basis Data & Relasi Eloquent

Arsitektur data terdiri dari 10 tabel terintegrasi dengan integritas relasional penuh:

```
                  ┌──────────────┐
                  │    users     │ (admin, owner, customer)
                  └──────┬───────┘
          ┌──────────────┼──────────────┐
          │ (owns)       │ (orders)     │ (reviews)
          ▼              ▼              ▼
   ┌─────────────┐ ┌───────────┐  ┌───────────┐
   │ restaurants │ │  orders   │  │  reviews  │
   └──────┬──────┘ └─────┬─────┘  └─────▲─────┘
          │              │              │
          │ (has many)   │ (has many)   │
          ▼              ▼              │
    ┌───────────┐  ┌───────────┐        │
    │ products  │◄─┤order_items│        │
    └─────▲─────┘  └───────────┘        │
          │                             │
          │ (categorized by)            │
    ┌─────┴─────┐                       │
    │categories │                       │
    └───────────┘                       │
                                        │
    ┌───────────┐  ┌───────────┐        │
    │   carts   ├──►cart_items ├────────┘
    └───────────┘  └───────────┘
```

### Rincian Tabel:

1. **`users`**
   - Kolom: `id`, `name`, `email`, `password`, `role` (`admin`/`owner`/`customer`), `phone`, `address`, `avatar`, `timestamps`.
2. **`categories`**
   - Kolom: `id`, `name`, `slug` (unique), `icon`, `image`, `description`, `timestamps`.
3. **`restaurants`**
   - Kolom: `id`, `user_id` (FK users), `name`, `slug` (unique), `description`, `address`, `phone`, `image`, `is_open` (boolean), `open_time`, `close_time`, `timestamps`.
4. **`products`**
   - Kolom: `id`, `restaurant_id` (FK restaurants), `category_id` (FK categories), `name`, `slug`, `description`, `price` (decimal 12,2), `stock` (integer), `image`, `is_available` (boolean), `timestamps`.
5. **`carts`**
   - Kolom: `id`, `user_id` (FK users), `restaurant_id` (FK restaurants), `timestamps`.
6. **`cart_items`**
   - Kolom: `id`, `cart_id` (FK carts), `product_id` (FK products), `quantity` (integer), `price` (decimal 12,2), `notes` (string), `timestamps`.
7. **`orders`**
   - Kolom: `id`, `user_id` (FK users), `restaurant_id` (FK restaurants), `total_price` (decimal 12,2), `delivery_fee`, `service_fee`, `status` (`pending`, `diproses`, `dikirim`, `selesai`, `dibatalkan`), `payment_method` (`transfer`, `cod`, `ewallet`), `address`, `notes`, `timestamps`.
8. **`order_items`**
   - Kolom: `id`, `order_id` (FK orders), `product_id` (FK products), `quantity`, `price`, `notes`, `timestamps`.
9. **`payments`**
   - Kolom: `id`, `order_id` (FK orders), `payment_proof` (string path), `payment_status` (`pending`, `paid`, `failed`, `cancelled`), `paid_at`, `timestamps`.
10. **`reviews`**
    - Kolom: `id`, `user_id` (FK users), `restaurant_id` (FK restaurants), `product_id` (FK products, nullable), `rating` (tinyint 1-5), `comment`, `timestamps`.

---

## 6. Aturan Bisnis Kritis & Keamanan

1. **Isolasi Keranjang Restoran Tunggal (Single Restaurant Cart)**:
   - Satu sesi keranjang hanya boleh memuat produk dari satu restoran.
   - Bila pengguna memilih hidangan dari restoran berbeda, sistem memicu modal peringatan konfirmasi penggantian keranjang.
2. **Proteksi Balapan Stok (Race Condition Protection)**:
   - Pengurangan stok saat checkout dibungkus dalam `DB::transaction()` dan dikunci menggunakan `Product::where('id', ...)->lockForUpdate()`.
3. **Rollback Stok Otomatis**:
   - Jika pesanan dibatalkan, seluruh kuantitas pada `order_items` otomatis dikembalikan ke stok produk terkait:
   ```php
   foreach ($order->items as $item) {
       $item->product->increment('stock', $item->quantity);
   }
   ```
4. **Otorisasi Kepemilikan Data**:
   - Pengecekan customer wajib menggunakan id pemilik:
   ```php
   if ($order->user_id !== Auth::id()) {
       abort(403, 'Akses ditolak.');
   }
   ```
5. **Validasi Restoran Tutup**:
   - Customer dilarang memasukkan item atau melakukan checkout jika restoran mitra berstatus `is_open = false`.
6. **Alur Pemesanan 2 Tahap (Two-Step Ordering)**:
   - Dari katalog restoran (`Restaurant-detail`), pelanggan mengklik **Pilih Menu** menuju `products.show` untuk memilih porsi, catatan hidangan, dan kalkulasi subtotal sebelum masuk ke keranjang belanja.

---

## 7. Peta Rute & Pengendali (Route Map)

### A. Rute Publik (Tanpa Autentikasi)
- `GET /` : Halaman Beranda (`HomeController@index`)
- `GET /restaurants` : Katalog Seluruh Restoran (`HomeController@restaurants`)
- `GET /restaurants/{restaurant}` : Detail Restoran & Menu (`HomeController@showRestaurant`)
- `GET /products` : Daftar Seluruh Menu (`ProductController@index`)
- `GET /products/{product}` : Detail Produk & Pemilihan Porsi (`HomeController@showProduct`)
- `POST /payment/webhook` : Webhook Integrasi Gateway Pembayaran (`PaymentController@handleWebhook`)

### B. Autentikasi
- `GET /login` : Form Masuk (`AuthController@showLogin`)
- `POST /login` : Proses Masuk (`AuthController@login`)
- `GET /register` : Form Registrasi (`AuthController@showRegister`)
- `POST /register` : Proses Registrasi (`AuthController@register`)
- `POST /logout` : Keluar Sesi (`AuthController@logout`)

### C. Rute Customer (`middleware: ['auth', 'role:customer']`, prefix: `/customer`)
- `GET /customer/dashboard` : Dashboard Customer & Metrik Transaksi
- `GET /customer/cart` : Halaman Keranjang Belanja
- `POST /customer/cart/add` : Tambah Menu ke Keranjang
- `PUT /customer/cart/update/{item}` : Ubah Kuantitas Keranjang
- `DELETE /customer/cart/remove/{item}` : Hapus Item Keranjang
- `DELETE /customer/cart/clear` : Kosongkan Seluruh Keranjang
- `GET /customer/orders` : Riwayat Seluruh Pesanan
- `GET /customer/orders/{order}` : Pelacakan & Rincian Pesanan
- `POST /customer/orders` : Checkout Buat Pesanan Baru
- `POST /customer/orders/{order}/cancel` : Batalkan Pesanan
- `POST /customer/orders/{order}/payment` : Unggah Bukti Transfer
- `POST /customer/reviews` : Kirim Rating & Ulasan

### D. Rute Owner (`middleware: ['auth', 'role:owner']`, prefix: `/owner`)
- `GET /owner/dashboard` : Dashboard Penjualan & Statistik Omzet Restoran
- `GET /owner/restaurant` : Profil Restoran
- `GET /owner/restaurant/{restaurant}/edit` : Form Ubah Info Restoran
- `PUT /owner/restaurant/{restaurant}` : Simpan Perubahan Restoran
- `GET /owner/products` : Katalog Manajemen Menu Restoran
- `GET /owner/products/create` : Form Tambah Menu Baru
- `POST /owner/products` : Simpan Menu Baru
- `GET /owner/products/{product}/edit` : Form Edit Menu
- `PUT /owner/products/{product}` : Simpan Perubahan Menu
- `DELETE /owner/products/{product}` : Hapus Menu
- `GET /owner/orders` : Daftar Pesanan Masuk
- `GET /owner/orders/{order}` : Rincian Pesanan Masuk
- `PUT /owner/orders/{order}/status` : Perbarui Status Pesanan (`diproses`/`dikirim`/`selesai`/`dibatalkan`)
- `POST /owner/orders/{order}/verify-payment` : Verifikasi Bukti Pembayaran

### E. Rute Admin (`middleware: ['auth', 'role:admin']`, prefix: `/admin`)
- `GET /admin/dashboard` : Dashboard Ringkasan Global Platform
- `RESOURCE /admin/users` : Manajemen Data Seluruh User (Admin, Owner, Customer)
- `RESOURCE /admin/restaurants` : Verifikasi & Manajemen Mitra Restoran
- `RESOURCE /admin/categories` : Manajemen Kategori Makanan Global
- `GET /admin/orders` : Monitoring Seluruh Transaksi Pesanan di Platform
- `GET /admin/orders/{order}` : Detail Pesanan Global
- `PUT /admin/orders/{order}/status` : Intervensi Status Pesanan
- `POST /admin/orders/{order}/verify-payment` : Verifikasi Pembayaran

---

## 8. Design System: Liquid Glass & Identitas Brand

Aplikasi mengusung bahasa visual **Liquid Glass Light Mode**:

### A. Identitas Tipografi Brand FoodOrder
- Kata **"Food"** menggunakan warna hitam tegas (`#0f172a` / `.brand-food`).
- Kata **"Order"** menggunakan warna oranye kuliner hangat (`var(--liquid-primary)` / `#ff5722` / `.brand-order`).
- Aturan isolasi CSS mencegah pewarnaan oranye turun ke seluruh kata.

### B. Token Desain
- **Ground Background**: `#f8fafc` dengan aksen *radial ambient gradient* hangat.
- **Card Background**: `#ffffff` murni dengan border halus `1px solid rgba(226, 232, 240, 0.95)` dan elevasi bayangan mikro `0 1px 3px 0 rgba(15, 23, 42, 0.04)`.
- **Card Hover**: Transisi mulus `translateY(-2px)` dengan peningkatan kedalaman bayangan.
- **KPI Stat Cards (`.stat-card`)**:
  - Ukuran ikon standar: `46px × 46px` dengan `border-radius: 12px`.
  - Label: `0.76rem`, huruf kapital, *tracked*, warna `#64748b`.
  - Nilai metriks: `1.55rem`, *bold*, warna `#0f172a`.
- **Badges Status Pesanan**:
  - `badge-pending`: Kuning amber lembut (`#92400e` di atas `#fef3c7`).
  - `badge-diproses`: Biru langit lembut (`#0369a1` di atas `#e0f2fe`).
  - `badge-dikirim`: Toska teal lembut (`#0f766e` di atas `#ccfbf1`).
  - `badge-selesai`: Hijau zamrud segar (`#166534` di atas `#dcfce7`).
  - `badge-dibatalkan`: Merah karang lembut (`#991b1b` di atas `#fee2e2`).
- **Ikonografi Konsisten**: 100% menggunakan Bootstrap Icons (`bi-*`), tanpa emoji dekoratif di kartu atau tabel.

---

## 9. Panduan Instalasi & Pengujian

### Prasyarat:
- PHP >= 8.2 (ekstensi `pdo_mysql`, `mbstring`, `fileinfo`, `openssl`, `curl`)
- Composer >= 2.x
- Node.js >= 18.x & NPM
- MySQL Server >= 8.0

### Langkah Instalasi:

```bash
# 1. Salin berkas lingkungan
cp .env.example .env

# 2. Pasang dependensi PHP & JavaScript
composer install
npm install

# 3. Buat application key
php artisan key:generate

# 4. Buat symlink public storage untuk gambar menu & bukti bayar
php artisan storage:link

# 5. Jalankan migrasi dan seeder data lengkap
php artisan migrate:fresh --seed --class=FoodOrderingSeeder

# 6. Kompilasi aset frontend
npm run build

# 7. Jalankan server lokal
php artisan serve
```

---

## 10. Kredensial Akun Demo

Database seeder (`FoodOrderingSeeder`) menyediakan akun siap pakai untuk seluruh skenario pengujian:

| Role | Nama Pengguna | Alamat Email | Kata Sandi | Deskripsi Akun |
| :--- | :--- | :--- | :--- | :--- |
| **Admin** | Administrator Kuliner | `admin@food.test` | `password` | Akses penuh manajemen user, restoran, kategori, & pesanan. |
| **Owner** | Budi Santoso | `owner@food.test` | `password` | Pemilik Restoran Padang Minang Saiyo. |
| **Owner** | Siti Rahmawati | `kopi@food.test` | `password` | Pemilik Kedai Kopi Kenangan Senja. |
| **Owner** | H. Achmad Madura | `bebek@food.test` | `password` | Pemilik Bebek Goreng Kremes Sinjay. |
| **Customer** | Andi Pratama | `customer@food.test` | `password` | Pelanggan dengan riwayat pesanan aktif & selesai. |
| **Customer** | Budi Wijaya | `budi@food.test` | `password` | Pelanggan demo reguler. |
| **Customer** | Dewi Lestari | `dewi@food.test` | `password` | Pelanggan demo reguler. |

*Catatan: Tombol jalan pintas login cepat (Quick Fill) tersedia langsung pada halaman `/login` untuk memudahkan pergantian role saat pengujian.*
