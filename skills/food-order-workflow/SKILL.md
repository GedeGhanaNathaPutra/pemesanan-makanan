---
name: food-order-workflow
description: Domain-specific food ordering lifecycle management, business rules, cart calculations, stock reservation, and state machine transitions for food delivery applications. Use this skill whenever working on order placement, cart logic, status updates (pending, diproses, dikirim, selesai, dibatalkan), stock rollbacks, restaurant isolation, or delivery fee calculations.
---

# Food Order Workflow & State Machine

Skill ini mengatur seluruh alur bisnis pemesanan makanan (Food Delivery / Takeaway) mulai dari keranjang belanja hingga pesanan selesai atau dibatalkan.

---

## 1. Siklus Hidup Pesanan (Order State Machine)

Setiap pesanan mengikuti siklus status berikut dengan aturan transisi ketat:

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
   ▼ (Restoran Terima)           ▼ (Customer/Restoran Batal)
┌───────────┐             ┌────────────┐
│ DIPROSES  │             │ DIBATALKAN │ ◄── [Otomatis Rollback Stok]
└─────┬─────┘             └────────────┘
      │
      ▼ (Makanan Siap & Diantar)
┌───────────┐
│  DIKIRIM  │ ◄── Kurir/Driver membawa makanan ke alamat
└─────┬─────┘
      │
      ▼ (Diterima Pembeli)
┌───────────┐
│  SELESAI  │ ◄── Transaksi tuntas, customer bisa memberi Review & Rating
└───────────┘
```

### Tabel Aturan Transisi Status

| Status Asal | Status Target | Aktor yang Berhak | Kondisi & Efek Samping |
| :--- | :--- | :--- | :--- |
| *(Checkout)* | `pending` | Customer | Stok produk berkurang (`decrement('stock', qty)`), item keranjang dihapus. |
| `pending` | `diproses` | Owner / Admin | Restoran menerima pesanan dan mulai memasak. Pembayaran sudah divalidasi. |
| `pending` | `dibatalkan` | Customer / Owner / Admin | **WAJIB rollback stok produk** (`increment('stock', qty)`). |
| `diproses` | `dikirim` | Owner / Driver | Makanan selesai dimasak dan diserahkan ke kurir/pengantar. |
| `diproses` | `dibatalkan` | Owner / Admin | Hanya jika bahan habis darurat. Wajib rollback stok dan refund. |
| `dikirim` | `selesai` | Customer / Driver / Owner | Makanan sudah diterima pemesan. Saldo masuk ke pendapatan restoran. |
| `dikirim` | `dibatalkan` | Admin | Kasus khusus (alamat tidak ditemukan / kurir kecelakaan). Rollback stok opsional. |
| `selesai` | *(Semua)* | *None* | Status final tidak boleh diubah lagi. Review diizinkan. |
| `dibatalkan` | *(Semua)* | *None* | Status final tidak boleh diubah lagi. |

---

## 2. Aturan Bisnis Kritis (Core Business Rules)

### A. Single Restaurant Cart Constraint (Isolasi Keranjang Restoran)
Dalam aplikasi pemesanan makanan, satu pesanan umumnya hanya berasal dari **satu restoran** agar perhitungan ongkir dan penyiapan makanan tidak bentrok.

**Aturan Implementasi**:
1. Saat customer menambahkan produk ke keranjang:
   - Cek apakah keranjang sudah memiliki item dari restoran lain.
   - Jika `cart->restaurant_id !== product->restaurant_id`:
     - Tampilkan peringatan / konfirmasi: *"Keranjang Anda berisi menu dari restoran lain. Bersihkan keranjang dan ganti ke restoran ini?"*
     - Jika dikonfirmasi, kosongkan keranjang lama lalu masukkan produk baru.
2. Keranjang harus otomatis menyimpan `restaurant_id`.

### B. Proteksi Balapan Stok (Race Condition Protection)
Menu makanan favorit sering dipesan bersamaan oleh banyak pembeli saat jam makan siang/malam.
**Gunakan `DB::transaction` dan `lockForUpdate`**:

```php
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;

DB::transaction(function () use ($user, $cart, $request) {
    // 1. Kunci dan validasi ketersediaan stok
    foreach ($cart->items as $item) {
        $product = Product::where('id', $item->product_id)->lockForUpdate()->first();
        
        if (!$product->is_available || $product->stock < $item->quantity) {
            throw new \Exception("Maaf, stok menu {$product->name} tidak mencukupi atau sedang tidak tersedia.");
        }
    }

    // 2. Buat Order
    $order = Order::create([
        'user_id'        => $user->id,
        'restaurant_id'  => $cart->restaurant_id,
        'total_price'    => $cart->total,
        'status'         => 'pending',
        'payment_method' => $request->payment_method,
        'address'        => $request->address,
        'notes'          => $request->notes ?? null,
    ]);

    // 3. Kurangi stok dan catat OrderItem
    foreach ($cart->items as $item) {
        OrderItem::create([
            'order_id'   => $order->id,
            'product_id' => $item->product_id,
            'quantity'   => $item->quantity,
            'price'      => $item->price,
        ]);
        
        Product::where('id', $item->product_id)->decrement('stock', $item->quantity);
    }

    // 4. Inisialisasi Payment
    Payment::create([
        'order_id'       => $order->id,
        'payment_status' => 'pending',
    ]);

    // 5. Bersihkan Cart
    $cart->items()->delete();
    $cart->delete();
});
```

### C. Alur Pembatalan Pesanan & Rollback Stok
Saat pesanan dibatalkan (oleh customer atau restoran), stok makanan **harus dikembalikan**:

```php
public function cancel(Order $order)
{
    // Hanya status pending yang boleh dibatalkan oleh customer
    if ($order->user_id !== Auth::id() && !Auth::user()->isAdmin() && !Auth::user()->isOwner()) {
        abort(403, 'Akses ditolak.');
    }

    if (!in_array($order->status, ['pending'])) {
        return back()->with('error', 'Pesanan yang sedang diproses atau dikirim tidak dapat dibatalkan.');
    }

    DB::transaction(function () use ($order) {
        // Kembalikan stok untuk semua item
        foreach ($order->items as $item) {
            $item->product()->increment('stock', $item->quantity);
        }

        $order->update(['status' => 'dibatalkan']);
        
        if ($order->payment) {
            $order->payment->update(['payment_status' => 'cancelled']);
        }
    });

    return back()->with('success', 'Pesanan berhasil dibatalkan dan stok dikembalikan.');
}
```

---

## 3. Komponen Perhitungan Biaya (Fee Calculations)

Struktur perhitungan pesanan makanan:

| Komponen | Rumus / Penjelasan | Contoh |
| :--- | :--- | :--- |
| **Subtotal Makanan** | $\sum (\text{Harga Produk} \times \text{Jumlah})$ | Rp 50.000 |
| **Biaya Ongkir (Delivery Fee)** | Flat (misal Rp 10.000) atau berdasar jarak (Rp 3.000 / km) | Rp 10.000 |
| **Biaya Layanan (Platform Fee)** | Biaya tetap aplikasi (misal Rp 2.000) | Rp 2.000 |
| **Biaya Kemasan / Plastik** | Opsional per restoran | Rp 1.000 |
| **Diskon Promo** | Potongan voucher / kupon jika ada | - Rp 5.000 |
| **Total Pembayaran** | **Subtotal + Ongkir + Layanan - Diskon** | **Rp 58.000** |

Format mata uang standar Indonesia:
```php
function formatRupiah($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}
```

---

## 4. Checklist Kualitas Pesanan Makanan
- [ ] Verifikasi restoran sedang buka (`restaurant->is_open === true`) sebelum checkout.
- [ ] Validasi alamat pengiriman tidak boleh kosong.
- [ ] Catatan khusus pesanan (misal: "Jangan pedas", "Banyakin kuah") tersimpan di kolom `notes`.
- [ ] Proteksi otorisasi: customer hanya bisa melihat pesanan miliknya (`$order->user_id === Auth::id()`).
- [ ] Owner restoran hanya bisa melihat dan memproses pesanan yang ditujukan ke restorannya (`$order->restaurant_id === $owner->restaurant->id`).
