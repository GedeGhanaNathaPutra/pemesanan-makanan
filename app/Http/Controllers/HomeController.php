<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::where('is_open', true)
            ->withCount('products')
            ->withAvg('reviews', 'rating')
            ->latest()
            ->take(8)
            ->get();
        $categories = Category::all();
        return view('home', compact('restaurants', 'categories'));
    }

    public function restaurants(Request $request)
    {
        $query = Restaurant::where('is_open', true)
            ->withCount('products')
            ->withAvg('reviews', 'rating');

        if ($request->filled('category')) {
            $query->whereHas('products', fn ($q) => $q->where('category_id', $request->category));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('address', 'like', '%' . $search . '%');
            });
        }

        if ($request->sort === 'rating') {
            $query->orderByDesc('reviews_avg_rating');
        } elseif ($request->sort === 'popular') {
            $query->orderByDesc('products_count');
        } else {
            $query->latest();
        }

        $restaurants = $query->paginate(12)->withQueryString();
        $categories = Category::all();
        return view('view.restaurant', compact('restaurants', 'categories'));
    }

    public function showRestaurant(Restaurant $restaurant)
    {
        $categories = Category::whereHas('products', fn ($q) => $q->where('restaurant_id', $restaurant->id))->get();
        $products   = $restaurant->products()->with('category')->where('is_available', true)->get();
        $reviews    = $restaurant->reviews()->with('user')->latest()->take(10)->get();
        return view('view.Restaurant-detail', compact('restaurant', 'products', 'categories', 'reviews'));
    }

    public function showProduct(Product $product)
    {
        $product->load(['restaurant', 'category']);
        $reviews = $product->reviews()->with('user')->latest()->get();
        $relatedProducts = Product::where('restaurant_id', $product->restaurant_id)
            ->where('id', '!=', $product->id)
            ->where('is_available', true)
            ->take(4)
            ->get();
        return view('product-detail', compact('product', 'reviews', 'relatedProducts'));
    }

    public function customerDashboard()
    {
        $userId = Auth::id();
        $orders = Order::where('user_id', $userId)
            ->with(['restaurant', 'items.product', 'payment'])
            ->latest()
            ->take(5)
            ->get();

        // ponytail: optimize with single aggregate query if traffic scales
        $stats = [
            'total_orders'     => Order::where('user_id', $userId)->count(),
            'active_orders'    => Order::where('user_id', $userId)->whereIn('status', ['pending', 'diproses', 'dikirim'])->count(),
            'completed_orders' => Order::where('user_id', $userId)->where('status', 'selesai')->count(),
            'total_spent'      => Order::where('user_id', $userId)->where('status', 'selesai')->sum('total_price'),
        ];

        $activeOrder = Order::where('user_id', $userId)
            ->whereIn('status', ['pending', 'diproses', 'dikirim'])
            ->with(['restaurant', 'items.product', 'payment'])
            ->latest()
            ->first();

        return view('view.customer.dashboard', compact('orders', 'stats', 'activeOrder'));
    }
}
