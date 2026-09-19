<?php

namespace App\Http\Controllers\owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $restaurant = Auth::user()->restaurant;
        if (!$restaurant) {
            return redirect()->route('owner.restaurant.create')->with('info', 'Silakan buat restoran Anda terlebih dahulu.');
        }

        $stats = [
            'total_products'    => Product::where('restaurant_id', $restaurant->id)->count(),
            'total_orders'      => Order::where('restaurant_id', $restaurant->id)->count(),
            'pending_orders'    => Order::where('restaurant_id', $restaurant->id)->where('status', 'pending')->count(),
            'processing_orders' => Order::where('restaurant_id', $restaurant->id)->where('status', 'diproses')->count(),
            'shipping_orders'   => Order::where('restaurant_id', $restaurant->id)->where('status', 'dikirim')->count(),
            'completed_orders'  => Order::where('restaurant_id', $restaurant->id)->where('status', 'selesai')->count(),
            'revenue'           => Order::where('restaurant_id', $restaurant->id)->where('status', 'selesai')->sum('total_price'),
        ];

        $recentOrders = Order::where('restaurant_id', $restaurant->id)
            ->with(['user', 'items.product', 'payment'])
            ->latest()
            ->take(6)
            ->get();

        $lowStockProducts = Product::where('restaurant_id', $restaurant->id)
            ->where('stock', '<=', 5)
            ->take(4)
            ->get();

        return view('owner.dashboard', compact('restaurant', 'stats', 'recentOrders', 'lowStockProducts'));
    }
}

if (!class_exists('App\Http\Controllers\owner\dashboardController', false)) {
    class_alias(DashboardController::class, 'App\Http\Controllers\owner\dashboardController');
}
