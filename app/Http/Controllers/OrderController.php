<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $query = Order::where('user_id', $userId)
            ->with(['restaurant', 'items.product', 'payment']);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('restaurant', fn ($r) => $r->where('name', 'like', "%{$search}%"));
            });
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        // ponytail: cached aggregation later if order volume exceeds 10k per user
        $counts = [
            'all'        => Order::where('user_id', $userId)->count(),
            'pending'    => Order::where('user_id', $userId)->where('status', 'pending')->count(),
            'diproses'   => Order::where('user_id', $userId)->where('status', 'diproses')->count(),
            'dikirim'    => Order::where('user_id', $userId)->where('status', 'dikirim')->count(),
            'selesai'    => Order::where('user_id', $userId)->where('status', 'selesai')->count(),
            'dibatalkan' => Order::where('user_id', $userId)->where('status', 'dibatalkan')->count(),
        ];

        return view('view.customer.orders', compact('orders', 'counts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'address'        => 'required|string',
            'payment_method' => 'required|string|in:transfer,cod,ewallet',
        ]);

        $cart = Cart::where('user_id', Auth::id())->with('items.product')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('customer.cart.index')->with('error', 'Keranjang kosong.');
        }

        try {
            DB::transaction(function () use ($request, $cart) {
                // 1. Kunci dan validasi ketersediaan stok (Race Condition Protection)
                foreach ($cart->items as $item) {
                    $product = Product::where('id', $item->product_id)->lockForUpdate()->first();

                    if (!$product || !$product->is_available || $product->stock < $item->quantity) {
                        throw new \Exception("Maaf, stok menu {$item->product->name} tidak mencukupi atau sedang tidak tersedia.");
                    }
                }

                $total = $cart->items->sum(fn($i) => $i->price * $i->quantity);

                // 2. Buat Order
                $order = Order::create([
                    'user_id'        => Auth::id(),
                    'restaurant_id'  => $cart->restaurant_id,
                    'total_price'    => $total,
                    'status'         => 'pending',
                    'payment_method' => $request->payment_method,
                    'address'        => $request->address,
                ]);

                // 3. Catat OrderItem dan kurangi stok
                foreach ($cart->items as $item) {
                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $item->product_id,
                        'quantity'   => $item->quantity,
                        'price'      => $item->price,
                    ]);

                    // Kurangi stok produk
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

            return redirect()->route('customer.cart.index')->with('success', 'pesanan berhasil dibuat');
        } catch (\Exception $e) {
            return redirect()->route('customer.cart.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) abort(403);
        $order->load('restaurant', 'items.product', 'payment');
        return view('view.customer.order-detail', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function uploadPayment(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) abort(403);
        $request->validate(['payment_proof' => 'required|image|max:2048']);

        $path = $request->file('payment_proof')->store('payment_proof', 'public');
        $order->payment()->updateOrCreate(
            ['order_id' => $order->id],
            ['payment_proof' => $path, 'payment_status' => 'paid', 'paid_at' => now()]
        );

        return back()->with('success', 'Bukti pembayaran berhasil diunggah.');
    }

    // Alias in case any legacy route still references the typo
    public function uplaodPayment(Request $request, Order $order)
    {
        return $this->uploadPayment($request, $order);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id()) abort(403);
        if ($order->status !== 'pending') {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan karena sudah diproses atau selesai.');
        }

        DB::transaction(function () use ($order) {
            $order->load('items.product');
            // Rollback inventory / stock
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }
            $order->update(['status' => 'dibatalkan']);
            if ($order->payment) {
                $order->payment->update(['payment_status' => 'cancelled']);
            }
        });

        return redirect()->route('customer.orders.index')->with('success', 'Pesanan berhasil dibatalkan dan stok dikembalikan.');
    }


    
}
