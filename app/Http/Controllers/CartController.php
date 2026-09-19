<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    private function authorizeCartItem(CartItem $item)
    {
        $cart = $item->cart;
        if (!$cart || $cart->user_id !== Auth::id()) {
            abort(403);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cart = Cart::where('user_id', Auth::id())->with('items.product', 'restaurant')->first();
        return view('view.customer.cart', compact('cart'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::with('restaurant')->findOrFail($request->product_id);
        if (!$product->is_available || $product->stock < $request->quantity) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Produk tidak tersedia atau stok tidak mencukupi.'], 422);
            }
            return back()->with('error', 'Produk tidak tersedia atau stok tidak mencukupi.');
        }

        $cart = Cart::where('user_id', Auth::id())->first();

        if ($cart && $cart->restaurant_id !== $product->restaurant_id) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success'          => false,
                    'different_restaurant' => true,
                    'message'          => 'Keranjang Anda telah berisi item dari restoran lain. Kosongkan terlebih dahulu untuk memesan dari restoran ini.',
                    'current_restaurant' => $cart->restaurant->name ?? 'Restoran lain',
                ], 409);
            }
            return back()->with('error', 'Keranjang Anda telah berisi item dari restoran lain. Kosongkan terlebih dahulu untuk memesan dari restoran ini.');
        }

        if (!$cart) {
            $cart = Cart::create([
                'user_id'       => Auth::id(),
                'restaurant_id' => $product->restaurant_id,
            ]);
        }

        $item = CartItem::where('cart_id', $cart->id)->where('product_id', $product->id)->first();
        if ($item) {
            $item->update(['quantity' => $item->quantity + $request->quantity]);
        } else {
            CartItem::create([
                'cart_id'    => $cart->id,
                'product_id' => $product->id,
                'quantity'   => $request->quantity,
                'price'      => $product->price,
            ]);
        }

        if ($request->expectsJson()) {
            $cart->load('items.product');
            return response()->json([
                'success'    => true,
                'message'    => 'Menu berhasil ditambahkan ke keranjang.',
                'cart_count' => $cart->items->sum('quantity'),
                'cart_total' => $cart->items->sum(fn ($i) => $i->price * $i->quantity),
            ]);
        }

        return back()->with('success', 'Menu berhasil ditambahkan ke keranjang.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CartItem $item)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        $this->authorizeCartItem($item);

        if ($item->product && $item->product->stock < $request->quantity) {
            return back()->with('error', 'Stok produk tidak mencukupi.');
        }

        $item->update(['quantity' => $request->quantity]);
        return back()->with('success', 'Jumlah pesanan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function remove(CartItem $item)
    {
        $this->authorizeCartItem($item);
        $cart = $item->cart;
        $item->delete();

        // If cart has no items left, clean up the cart record
        if ($cart && $cart->items()->count() === 0) {
            $cart->delete();
        }

        return back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    public function clear()
    {
        $cart = Cart::where('user_id', Auth::id())->first();
        if ($cart) {
            $cart->items()->delete();
            $cart->delete();
        }

        return back()->with('success', 'Keranjang berhasil dikosongkan.');
    }


    
}
