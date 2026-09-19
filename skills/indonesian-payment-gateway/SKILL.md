---
name: indonesian-payment-gateway
description: Implementation guide and code patterns for Indonesian payment methods in food ordering applications. Covers Midtrans Snap & Core API (QRIS, GoPay, ShopeePay, Virtual Accounts), Manual Bank Transfer slip upload with verification workflow, Cash on Delivery (COD), and webhook signature validation. Use this skill when implementing, debugging, or configuring payments for Indonesian food orders.
---

# Indonesian Payment Gateway & Payment Methods Guide

Panduan lengkap integrasi metode pembayaran populer di Indonesia untuk aplikasi pemesanan makanan:
1. **Manual Bank Transfer (Upload Bukti Bayar)**
2. **Midtrans Snap / Core API (QRIS, GoPay, OVO, ShopeePay, Virtual Account)**
3. **Cash on Delivery (COD / Bayar di Tempat)**

---

## 1. Manual Bank Transfer (Upload Bukti Pembayaran)

Metode paling sederhana tanpa biaya transaksi gateway.

### A. Alur Kerja (Workflow)
1. Customer checkout memilih transfer bank (BCA / Mandiri / BRI).
2. Sistem menampilkan nomor rekening restoran / platform dan batas waktu transfer (misal 60 menit).
3. Customer mengunggah foto / tangkapan layar bukti transfer (Struk ATM, m-Banking).
4. Restoran / Admin memeriksa bukti transfer pada dashboard.
5. Jika valid: status pembayaran diubah menjadi `paid`, status pesanan menjadi `diproses`.
6. Jika tidak valid: admin menolak bukti bayar dengan alasan.

### B. Controller Implementation
```php
public function uploadPayment(Request $request, Order $order)
{
    if ($order->user_id !== Auth::id()) {
        abort(403, 'Akses ditolak.');
    }

    $request->validate([
        'payment_proof' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072', // Max 3MB
    ]);

    // Simpan file ke storage/app/public/payment_proofs
    $path = $request->file('payment_proof')->store('payment_proofs', 'public');

    // Update atau buat data pembayaran
    $order->payment()->updateOrCreate(
        ['order_id' => $order->id],
        [
            'payment_proof'  => $path,
            'payment_status' => 'pending', // Menunggu verifikasi admin/owner
            'paid_at'        => now(),
        ]
    );

    return back()->with('success', 'Bukti pembayaran berhasil diunggah! Menunggu konfirmasi restoran.');
}
```

### C. Owner/Admin Verification
```php
public function verifyPayment(Request $request, Order $order)
{
    $request->validate(['status' => 'required|in:paid,failed']);

    DB::transaction(function () use ($request, $order) {
        if ($request->status === 'paid') {
            $order->payment->update(['payment_status' => 'paid']);
            $order->update(['status' => 'diproses']); // Mulai masak makanan
        } else {
            $order->payment->update(['payment_status' => 'failed']);
            // Opsional: batalkan dan rollback stok jika gagal bayar
        }
    });

    return back()->with('success', 'Status pembayaran berhasil diperbarui.');
}
```

---

## 2. Integrasi Midtrans (QRIS, E-Wallet, Virtual Account)

Midtrans adalah gateway pembayaran resmi terpopuler di Indonesia dengan dukungan penuh QRIS (GoPay, OVO, DANA, ShopeePay, LinkAja, BCA Mobile).

### A. Instalasi SDK Midtrans
```bash
composer require midtrans/midtrans-php
```

### B. Konfigurasi `.env`
```env
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxxxxxxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxxxxxxxx
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

### C. Pembuatan Snap Token untuk Checkout Makanan
```php
namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Order;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createSnapToken(Order $order): string
    {
        $itemDetails = [];
        foreach ($order->items as $item) {
            $itemDetails[] = [
                'id'       => $item->product_id,
                'price'    => (int) $item->price,
                'quantity' => $item->quantity,
                'name'     => substr($item->product->name, 0, 50),
            ];
        }

        // Tambahkan ongkir jika ada
        if ($order->delivery_fee > 0) {
            $itemDetails[] = [
                'id'       => 'DELIVERY-FEE',
                'price'    => (int) $order->delivery_fee,
                'quantity' => 1,
                'name'     => 'Ongkos Kirim',
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id'     => 'ORDER-' . $order->id . '-' . time(),
                'gross_amount' => (int) $order->total_price,
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $order->user->name,
                'email'      => $order->user->email,
                'phone'      => $order->user->phone ?? '08123456789',
                'billing_address' => [
                    'address' => $order->address,
                ],
            ],
            'enabled_payments' => [
                'gopay', 'shopeepay', 'qris',
                'bca_va', 'bni_va', 'bri_va', 'mandiri_va',
            ],
        ];

        return Snap::getSnapToken($params);
    }
}
```

### D. Webhook Handler & Signature Verification (Aman & Idempoten)
```php
public function handleWebhook(Request $request)
{
    $serverKey = config('services.midtrans.server_key');
    $orderId = $request->order_id;
    $statusCode = $request->status_code;
    $grossAmount = $request->gross_amount;
    $signature = $request->signature_key;

    // Verifikasi hash signature SHA512
    $mySignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
    if ($mySignature !== $signature) {
        return response()->json(['message' => 'Invalid signature'], 403);
    }

    // Ekstrak ID order (format: ORDER-{id}-{timestamp})
    preg_match('/ORDER-(\d+)-/', $orderId, $matches);
    $realOrderId = $matches[1] ?? null;

    $order = Order::find($realOrderId);
    if (!$order) {
        return response()->json(['message' => 'Order not found'], 404);
    }

    $transactionStatus = $request->transaction_status;
    $fraudStatus = $request->fraud_status;

    DB::transaction(function () use ($order, $transactionStatus, $fraudStatus) {
        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            $order->payment()->update(['payment_status' => 'paid', 'paid_at' => now()]);
            $order->update(['status' => 'diproses']); // Restoran langsung mulai masak
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $order->payment()->update(['payment_status' => 'failed']);
            // Rollback stok makanan
            foreach ($order->items as $item) {
                $item->product()->increment('stock', $item->quantity);
            }
            $order->update(['status' => 'dibatalkan']);
        }
    });

    return response()->json(['status' => 'success']);
}
```

---

## 3. Cash on Delivery (COD / Bayar di Tempat)

Pilihan populer bagi pelanggan yang tidak memiliki akses e-wallet / m-banking.

**Aturan Bisnis COD**:
1. Batas maksimal nominal pesanan COD (misal maksimal Rp 200.000) untuk membatasi resiko penolakan/fake order.
2. Validasi nomor telepon wajib aktif sebelum checkout COD.
3. Status pembayaran tetap `pending` saat pesanan `diproses` dan `dikirim`.
4. Saat pesanan berstatus `selesai` (driver menandai pesanan telah diserahkan dan uang tunai diterima), status pembayaran otomatis diperbarui menjadi `paid`.
