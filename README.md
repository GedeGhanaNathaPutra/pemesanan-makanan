# 🍲 FoodOrder — Food Ordering & Delivery Multi-Role Platform

<div align="center">

[![Laravel Version](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

**A modern, full-stack multi-role food ordering and restaurant management platform built with Laravel 12, Bootstrap 5.3, and MySQL.**

[Fitur Utama](#-fitur-utama--multi-role) • [Alur Pesanan](#-siklus-hidup-pesanan-state-machine) • [Panduan Instalasi](#-panduan-instalasi) • [Akun Demo](#-kredensial-akun-demo) • [Struktur Database](#-arsitektur-basis-data)

</div>

---

## 📌 Gambaran Proyek (Overview)

**FoodOrder** adalah aplikasi web pemesanan dan pengantaran makanan yang menghubungkan **Customer (Pelanggan)**, **Restaurant Owner (Mitra Restoran)**, dan **Admin (Pengelola Platform)** dalam satu ekosistem terintegrasi. 

Dibangun dengan arsitektur **Laravel 12** yang tangguh, sistem ini menerapkan aturan integritas bisnis tingkat tinggi seperti **Isolasi Keranjang Restoran Tunggal (Single-Restaurant Cart)**, **Pencegahan Balapan Stok (Race Condition Handling via `lockForUpdate`)**, dan **Rollback Stok Otomatis** saat pesanan dibatalkan.

---

## ✨ Fitur Utama & Multi-Role

### 🛒 1. Customer (Pembeli)
* **Katalog & Eksplorasi**: Menjelajahi restoran, jam operasional, rating, dan menu berdasarkan kategori.
* **Detail Menu & Kustomisasi**: Pemilihan jumlah porsi dan catatan khusus per menu.
* **Smart Cart**: Isolasi keranjang 1 restoran dengan dialog konfirmasi penggantian keranjang otomatis.
* **Checkout Fleksibel**: Pilihan metode pembayaran (Transfer Bank, COD, E-Wallet).
* **Unggah Bukti Bayar & Pelacakan**: Upload bukti transfer dan pelacakan status pesanan secara real-time.
* **Rating & Review**: Memberikan ulasan bintang 1–5 dan komentar setelah pesanan selesai.

### 👨‍🍳 2. Restaurant Owner (Mitra Restoran)
* **Dashboard Penjualan**: Ringkasan statistik omzet harian, pesanan aktif, dan menu terlaris.
* **Manajemen Restoran**: Kontrol profil toko, alamat, nomor kontak, serta tombol buka/tutup resto.
* **Manajemen Produk (CRUD)**: Kelola nama makanan, deskripsi, harga, foto, kategori, dan stok.
* **Pemrosesan Pesanan**: Menerima pesanan masuk, verifikasi bukti bayar, dan pembaruan status ke tahap memasak (`diproses`) hingga pengiriman (`dikirim`).

### 🛡️ 3. Platform Admin (Pengelola Sistem)
* **Pusat Kontrol Global**: Pantau total transaksi, mitra aktif, dan pertumbuhan pengguna.
* **Manajemen Mitra & Restoran**: Verifikasi, aktivasi, dan pengawasan gerai makanan.
* **Kategori Global**: Kelola master data kategori kuliner (Nusantara, Minuman, Fast Food, dll).
* **Manajemen Pengguna**: Kontrol hak akses seluruh user (Admin, Owner, Customer).
* **Audit Pesanan**: Monitoring dan intervensi status seluruh transaksi sistem.

---

## 🔄 Siklus Hidup Pesanan (State Machine)

Pesanan berpindah melalui status terukur yang mengikat integritas stok dan transaksi:

```text
[Customer Checkout]
        │
        ▼
   ┌─────────┐
   │ PENDING │ ◄── Menunggu pembayaran atau konfirmasi restoran
   └────┬────┘
        │
   ┌────┴────────────────────────┐
   │                             │
   ▼ (Restoran Terima & Masak)   ▼ (Dibatalkan oleh User/Owner/Admin)
┌───────────┐             ┌────────────┐
│ DIPROSES  │             │ DIBATALKAN │ ◄── [Otomatis Rollback Stok Produk]
└─────┬─────┘             └────────────┘
      │
      ▼ (Diserahkan ke Kurir)
┌───────────┐
│  DIKIRIM  │
└─────┬─────┘
      │
      ▼ (Diterima Customer)
┌───────────┐
│  SELESAI  │ ◄── Buka form Ulasan & Rating (Bintang 1-5)
└───────────┘
```

---

## 🔒 Keamanan & Aturan Bisnis Kritis

1. **Race Condition Protection**: Pengurangan stok saat checkout dibungkus `DB::transaction()` dan dikunci menggunakan `Product::lockForUpdate()`.
2. **Otomatis Rollback Stok**: Pembatalan pesanan otomatis mengembalikan kuantitas `order_items` ke stok master produk.
3. **Validasi Restoran Tutup**: Sistem memblokir penambahan menu ke keranjang jika status restoran `is_open = false`.
4. **Otorisasi Kepemilikan**: Validasi ketat kepemilikan data pesanan (`$order->user_id === Auth::id()`).
5. **Format Mata Uang Standar**: Seluruh harga terformat standar Rupiah Indonesia (`Rp number_format(...)`).

---

## 🗄️ Arsitektur Basis Data

Sistem didukung oleh **10 tabel relasional** dengan integritas foreign key cascade:

```text
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

---

## 💻 Tech Stack

| Layer | Teknologi | Deskripsi |
| :--- | :--- | :--- |
| **Backend** | [Laravel 12](https://laravel.com) | Arsitektur MVC, Eloquent ORM, Middleware Auth & Role |
| **Database** | [MySQL 8.0+](https://www.mysql.com/) | Relational DB Engine dengan InnoDB & Transaksi ACID |
| **Frontend** | [Bootstrap 5.3](https://getbootstrap.com) | Liquid Glass modern UI design, fully responsive |
| **Icons** | [Bootstrap Icons](https://icons.getbootstrap.com) | Icon set lengkap dan konsisten |
| **Typography** | [Plus Jakarta Sans](https://fonts.google.com/specimen/Plus+Jakarta+Sans) | Tipografi modern & bersih |
| **Bundler** | [Vite 6](https://vitejs.dev) | Asset pipeline & hot module replacement |

---

## 🚀 Panduan Instalasi

### Prasyarat Sistem
* PHP `>= 8.2` (Ekstensi: `pdo_mysql`, `mbstring`, `openssl`, `curl`, `fileinfo`)
* Composer `>= 2.x`
* Node.js `>= 18.x` & NPM
* MySQL Server `>= 8.0`

### Langkah-langkah:

1. **Clone repository**:
   ```bash
   git clone https://github.com/GedeGhanaNathaPutra/pemesanan-makanan.git
   cd pemesanan-makanan
   ```

2. **Pasang dependensi PHP & JS**:
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Sesuaikan konfigurasi database `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` pada file `.env`.*

4. **Buat Symlink Storage**:
   ```bash
   php artisan storage:link
   ```

5. **Migrasi Database & Seeder Lengkap**:
   ```bash
   php artisan migrate:fresh --seed --class=FoodOrderingSeeder
   ```

6. **Build Aset & Jalankan Server**:
   ```bash
   # Terminal 1: Kompilasi frontend
   npm run dev

   # Terminal 2: Server Laravel
   php artisan serve
   ```
   Buka browser di: `http://127.0.0.1:8000`

---

## 🔑 Kredensial Akun Demo

Seeder menyediakan akun siap pakai untuk seluruh skenario pengujian:

| Role | Nama Pengguna | Email | Password | Hak Akses |
| :--- | :--- | :--- | :--- | :--- |
| 🛡️ **Admin** | Administrator Kuliner | `admin@food.test` | `password` | Manajemen penuh sistem, restoran & kategori |
| 👨‍🍳 **Owner** | Budi Santoso | `owner@food.test` | `password` | Restoran Padang Minang Saiyo |
| 👨‍🍳 **Owner** | Siti Rahmawati | `kopi@food.test` | `password` | Kedai Kopi Kenangan Senja |
| 👨‍🍳 **Owner** | H. Achmad Madura | `bebek@food.test` | `password` | Bebek Goreng Kremes Sinjay |
| 🛒 **Customer** | Andi Pratama | `customer@food.test` | `password` | Pelanggan dengan pesanan aktif & selesai |
| 🛒 **Customer** | Budi Wijaya | `budi@food.test` | `password` | Pelanggan reguler |

---

## 🧪 Pengujian Otomatis (Automated Testing)

Jalankan test suite untuk memastikan seluruh logika bisnis, keranjang belanja, dan mutasi stok berjalan sempurna:

```bash
# Menjalankan seluruh test
php artisan test

# Menjalankan pengujian fitur pemesanan makanan
php artisan test --filter FoodOrderingTest
```

---

## 📄 Lisensi

Proyek ini dirilis di bawah lisensi [MIT License](LICENSE).

---

<div align="center">
  Dibuat dengan dedikasi untuk ekosistem kuliner digital Indonesia 🇮🇩
</div>
