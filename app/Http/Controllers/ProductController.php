<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Menampilkan katalog menu / produk makanan yang tersedia.
     */
    public function index(Request $request)
    {
        $query = Product::where('is_available', true)
            ->whereHas('restaurant', fn ($r) => $r->where('is_open', true))
            ->with(['restaurant', 'category']);

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($c) => $c->where('slug', $request->category));
        }

        if ($request->filled('sort')) {
            match ($request->sort) {
                'price_asc'  => $query->orderBy('price', 'asc'),
                'price_desc' => $query->orderBy('price', 'desc'),
                'newest'     => $query->latest(),
                default      => $query->latest(),
            };
        } else {
            $query->latest();
        }

        $products = $query->paginate(12);
        $categories = Category::all();

        return view('home', compact('products', 'categories'));
    }

    /**
     * Menampilkan detail satu menu makanan beserta ulasannya.
     */
    public function show(Product $product)
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
}

// Alias for backwards compatibility with lowercase references
if (!class_exists('App\Http\Controllers\product', false)) {
    class_alias(ProductController::class, 'App\Http\Controllers\product');
}
