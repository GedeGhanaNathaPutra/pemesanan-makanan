<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'       => User::count(),
            'total_customers'   => User::where('role', 'customer')->count(),
            'total_owners'      => User::where('role', 'owner')->count(),
            'total_restaurants' => Restaurant::count(),
            'total_orders'      => Order::count(),
            'total_products'    => Product::count(),
            'pending_orders'    => Order::where('status', 'pending')->count(),
            'processing_orders' => Order::where('status', 'diproses')->count(),
            'completed_orders'  => Order::where('status', 'selesai')->count(),
            'cancelled_orders'  => Order::where('status', 'dibatalkan')->count(),
            'revenue'           => Order::where('status', 'selesai')->sum('total_price'),
        ];

        $recentOrders = Order::with(['user', 'restaurant', 'payment'])
            ->latest()
            ->take(6)
            ->get();

        $recentRestaurants = Restaurant::with('user')
            ->latest()
            ->take(4)
            ->get();

        return view('view.admin.dashboard', compact('stats', 'recentOrders', 'recentRestaurants'));
    }
}

// Alias for backwards compatibility with lowercase references
if (!class_exists('App\Http\Controllers\admin\dashboard', false)) {
    class_alias(DashboardController::class, 'App\Http\Controllers\admin\dashboard');
}
