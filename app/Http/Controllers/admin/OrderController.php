<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'restaurant', 'payment']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('payment_status')) {
            $ps = $request->payment_status;
            $query->whereHas('payment', fn ($p) => $p->where('payment_status', $ps));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%")->orWhere('phone', 'like', "%{$search}%"))
                  ->orWhereHas('restaurant', fn ($r) => $r->where('name', 'like', "%{$search}%"));
            });
        }

        $orders = $query->latest()->paginate(15);

        $statusCounts = [
            'all'        => Order::count(),
            'pending'    => Order::where('status', 'pending')->count(),
            'diproses'   => Order::where('status', 'diproses')->count(),
            'dikirim'    => Order::where('status', 'dikirim')->count(),
            'selesai'    => Order::where('status', 'selesai')->count(),
            'dibatalkan' => Order::where('status', 'dibatalkan')->count(),
        ];

        return view('view.admin.orders.index', compact('orders', 'statusCounts'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'restaurant', 'items.product', 'payment']);
        return view('view.admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,diproses,dikirim,selesai,dibatalkan',
        ]);

        DB::transaction(function () use ($request, $order) {
            $oldStatus = $order->status;
            $newStatus = $request->status;

            // If admin cancels the order, rollback stock
            if ($newStatus === 'dibatalkan' && $oldStatus !== 'dibatalkan') {
                $order->load('items.product');
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', $item->quantity);
                    }
                }
                if ($order->payment) {
                    $order->payment->update(['payment_status' => 'cancelled']);
                }
            }

            $order->update(['status' => $newStatus]);
        });

        return back()->with('success', 'Status pesanan berhasil diperbarui oleh Administrator.');
    }
}

// Alias for backwards compatibility with lowercase references
if (!class_exists('App\Http\Controllers\admin\orderController', false)) {
    class_alias(OrderController::class, 'App\Http\Controllers\admin\orderController');
}
