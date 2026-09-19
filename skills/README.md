# Food Ordering Application Skills (Paket Skill Pemesanan Makanan)

Direktori ini berisi kumpulan skill spesialis untuk pengembangan, pengujian, dan pemeliharaan aplikasi pemesanan makanan (Food Delivery & Takeaway) berbasis Laravel 12.

## Daftar Skill yang Tersedia:

1. **`food-order-workflow`** (`skills/food-order-workflow/SKILL.md`):
   - Alur siklus hidup pesanan (State Machine): `pending` ➔ `diproses` ➔ `dikirim` ➔ `selesai` / `dibatalkan`.
   - Aturan isolasi satu keranjang per restoran (Single Restaurant Cart).
   - Penanganan balapan stok (`lockForUpdate`) dan pengembalian stok otomatis saat pesanan dibatalkan.
   - Komponen kalkulasi biaya (Subtotal, Ongkir, Biaya Layanan).

2. **`laravel-food-app`** (`skills/laravel-food-app/SKILL.md`):
   - Skema database 10 tabel (`users`, `restaurants`, `categories`, `products`, `carts`, `cart_items`, `orders`, `order_items`, `payments`, `reviews`).
   - Standar relasi Eloquent lengkap antar model.
   - Eager loading anti N+1 Query.
   - Checklist audit bug otorisasi dan penamaan controller.

3. **`indonesian-payment-gateway`** (`skills/indonesian-payment-gateway/SKILL.md`):
   - Manual Bank Transfer dengan validasi upload bukti bayar dan verifikasi admin.
   - Integrasi Midtrans Snap & Core API (QRIS, GoPay, ShopeePay, Virtual Account).
   - Cash on Delivery (COD / Bayar di Tempat) dengan aturan pembatasan risiko.
   - Webhook handler dengan verifikasi signature hash SHA-512.

4. **`food-ui-components`** (`skills/food-ui-components/SKILL.md`):
   - Komponen Blade & Tailwind CSS v4 ramah perangkat mobile.
   - Restaurant Card (Rating, badge buka/tutup, waktu antar).
   - Food Card (Foto, harga Rupiah, stok habis overlay, tombol tambah cepat).
   - Floating Bottom Cart Bar (Keranjang melayang khas aplikasi food delivery).
   - Order Status Tracking Stepper (Visualisasi status pesanan interaktif).

5. **`food-seeder-generator`** (`skills/food-seeder-generator/SKILL.md`):
   - Data seeder kuliner nusantara siap pakai (Restoran Padang, Kopi Kekinian, Bebek Sinjay, dll).
   - Akun demo untuk semua role: Admin (`admin@food.test`), Owner (`owner@food.test`), Customer (`customer@food.test`).

6. **`food-qa-testing`** (`skills/food-qa-testing/SKILL.md`):
   - Automated Feature Tests lengkap menggunakan PHPUnit / Pest.
   - Pengujian keranjang kosong, pengurangan stok, pengembalian stok saat batal, dan proteksi otorisasi antar user.
