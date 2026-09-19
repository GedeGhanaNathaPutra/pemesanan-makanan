<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Upload bukti pembayaran manual transfer bank oleh Customer.
     */
    public function uploadProof(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak: Pesanan ini bukan milik Anda.');
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $path = $request->file('payment_proof')->store('payment_proofs', 'public');

        $order->payment()->updateOrCreate(
            ['order_id' => $order->id],
            [
                'payment_proof'  => $path,
                'payment_status' => 'pending',
                'paid_at'        => now(),
            ]
        );

        return back()->with('success', 'Bukti pembayaran berhasil diunggah! Menunggu konfirmasi pihak restoran.');
    }

    /**
     * Verifikasi bukti pembayaran oleh Mitra Restoran atau Administrator.
     */
    public function verifyPayment(Request $request, Order $order)
    {
        $user = Auth::user();
        $isOwner = $user->isOwner() && $user->restaurant && $order->restaurant_id === $user->restaurant->id;
        $isAdmin = $user->isAdmin();

        if (!$isOwner && !$isAdmin) {
            abort(403, 'Akses ditolak: Anda tidak memiliki wewenang untuk memverifikasi pembayaran ini.');
        }

        $request->validate([
            'status' => 'required|in:paid,failed',
        ]);

        DB::transaction(function () use ($request, $order) {
            if ($request->status === 'paid') {
                $order->payment()->updateOrCreate(
                    ['order_id' => $order->id],
                    ['payment_status' => 'paid', 'paid_at' => now()]
                );
                // Setelah pembayaran lunas diverifikasi, restoran mulai memasak (diproses)
                if ($order->status === 'pending') {
                    $order->update(['status' => 'diproses']);
                }
            } else {
                $order->payment()->updateOrCreate(
                    ['order_id' => $order->id],
                    ['payment_status' => 'failed']
                );
            }
        });

        return back()->with('success', 'Status verifikasi pembayaran berhasil diperbarui.');
    }

    /**
     * Webhook Handler untuk Gateway Pembayaran (e.g. Midtrans Snap / QRIS).
     */
    public function handleWebhook(Request $request)
    {
        $serverKey = config('services.midtrans.server_key', env('MIDTRANS_SERVER_KEY', ''));
        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $grossAmount = $request->gross_amount;
        $signature = $request->signature_key;

        // Verifikasi signature jika server key dikonfigurasi
        if (!empty($serverKey) && $signature) {
            $mySignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
            if ($mySignature !== $signature) {
                return response()->json(['message' => 'Invalid signature'], 403);
            }
        }

        // Format order ID: ORD-{id} atau ORDER-{id}-{timestamp}
        preg_match('/(?:ORDER|ORD)-(\d+)/', (string)$orderId, $matches);
        $realOrderId = $matches[1] ?? $orderId;

        $order = Order::find($realOrderId);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $transactionStatus = $request->transaction_status;

        DB::transaction(function () use ($order, $transactionStatus) {
            if (in_array($transactionStatus, ['capture', 'settlement'])) {
                $order->payment()->updateOrCreate(
                    ['order_id' => $order->id],
                    ['payment_status' => 'paid', 'paid_at' => now()]
                );
                if ($order->status === 'pending') {
                    $order->update(['status' => 'diproses']);
                }
            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                $order->payment()->updateOrCreate(
                    ['order_id' => $order->id],
                    ['payment_status' => 'failed']
                );

                // Kembalikan stok bila pesanan belum dibatalkan sebelumnya
                if ($order->status !== 'dibatalkan') {
                    $order->load('items.product');
                    foreach ($order->items as $item) {
                        if ($item->product) {
                            $item->product->increment('stock', $item->quantity);
                        }
                    }
                    $order->update(['status' => 'dibatalkan']);
                }
            }
        });

        return response()->json(['status' => 'success']);
    }
}

// Alias for backwards compatibility with lowercase references
if (!class_exists('App\Http\Controllers\payment', false)) {
    class_alias(PaymentController::class, 'App\Http\Controllers\payment');
}
