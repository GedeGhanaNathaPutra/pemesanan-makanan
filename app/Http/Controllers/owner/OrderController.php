<?php

namespace App\Http\Controllers\owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    private function getRestaurant()
    {
        $restaurant = Auth::user()->restaurant;
        if (!$restaurant) abort(403, 'Restoran tidak ditemukan atau Anda belum memiliki restoran.');
        return $restaurant;
    }

    public function index(Request $request)
    {
        $restaurant = $this->getRestaurant();
        $query = Order::where('restaurant_id', $restaurant->id)
            ->with(['user', 'items.product', 'payment']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $ps = $request->payment_status;
            $query->whereHas('payment', fn ($p) => $p->where('payment_status', $ps));
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%")->orWhere('phone', 'like', "%{$search}%"));
            });
        }

        $orders = $query->latest()->paginate(15);

        $statusCounts = [
            'all'        => Order::where('restaurant_id', $restaurant->id)->count(),
            'pending'    => Order::where('restaurant_id', $restaurant->id)->where('status', 'pending')->count(),
            'diproses'   => Order::where('restaurant_id', $restaurant->id)->where('status', 'diproses')->count(),
            'dikirim'    => Order::where('restaurant_id', $restaurant->id)->where('status', 'dikirim')->count(),
            'selesai'    => Order::where('restaurant_id', $restaurant->id)->where('status', 'selesai')->count(),
            'dibatalkan' => Order::where('restaurant_id', $restaurant->id)->where('status', 'dibatalkan')->count(),
        ];

        return view('owner.orders.index', compact('orders', 'statusCounts'));
    }

    public function show(Order $order)
    {
        $restaurant = $this->getRestaurant();
        if ($order->restaurant_id !== $restaurant->id) abort(403);
        $order->load(['user', 'items.product', 'payment']);
        return view('owner.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $restaurant = $this->getRestaurant();
        if ($order->restaurant_id !== $restaurant->id) abort(403);

        $request->validate(['status' => 'required|in:diproses,dikirim,selesai,dibatalkan']);

        DB::transaction(function () use ($request, $order) {
            $oldStatus = $order->status;
            $newStatus = $request->status;


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

            
            if ($newStatus === 'selesai' && $order->payment_method === 'cod' && $order->payment) {
                $order->payment->update([
                    'payment_status' => 'paid',
                    'paid_at'        => now(),
                ]);
            }

            $order->update(['status' => $newStatus]);
        });

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
