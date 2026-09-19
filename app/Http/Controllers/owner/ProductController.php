<?php

namespace App\Http\Controllers\owner;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    private function getRestaurant()
    {
        $restaurant = Auth::user()->restaurant;
        if (!$restaurant) {
            abort(403, 'Anda belum memiliki restoran. Silakan daftarkan restoran Anda terlebih dahulu.');
        }
        return $restaurant;
    }

    public function index(Request $request)
    {
        $restaurant = $this->getRestaurant();
        $query = Product::where('restaurant_id', $restaurant->id)->with('category');

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'available') {
                $query->where('is_available', true)->where('stock', '>', 0);
            } elseif ($status === 'out_of_stock') {
                $query->where('stock', '<=', 0);
            } elseif ($status === 'inactive') {
                $query->where('is_available', false);
            }
        }

        $products   = $query->latest()->paginate(15);
        $categories = Category::all();

        $statusCounts = [
            'all'          => Product::where('restaurant_id', $restaurant->id)->count(),
            'available'    => Product::where('restaurant_id', $restaurant->id)->where('is_available', true)->where('stock', '>', 0)->count(),
            'out_of_stock' => Product::where('restaurant_id', $restaurant->id)->where('stock', '<=', 0)->count(),
            'inactive'     => Product::where('restaurant_id', $restaurant->id)->where('is_available', false)->count(),
        ];

        return view('owner.product.index', compact('products', 'categories', 'restaurant', 'statusCounts'));
    }

    public function create()
    {
        $this->getRestaurant();
        $categories = Category::all();
        return view('owner.product.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $restaurant = $this->getRestaurant();
        $data = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'name'         => 'required|string|max:150',
            'description'  => 'nullable|string',
            'price'        => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'is_available' => 'nullable|boolean',
            'image'        => 'nullable|image|max:2048',
        ]);

        $data['restaurant_id'] = $restaurant->id;
        $data['slug']          = Str::slug($data['name'] . '-' . time());
        $data['is_available']  = $request->boolean('is_available', true);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);
        return redirect()->route('owner.products.index')->with('success', 'Produk berhasil ditambahkan ke menu restoran.');
    }

    public function edit(Product $product)
    {
        $this->authorizeProduct($product);
        $categories = Category::all();
        return view('owner.product.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorizeProduct($product);

        $data = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'name'         => 'required|string|max:150',
            'description'  => 'nullable|string',
            'price'        => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'is_available' => 'nullable|boolean',
            'image'        => 'nullable|image|max:2048',
        ]);

        $data['is_available'] = $request->boolean('is_available', false);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);
        return redirect()->route('owner.products.index')->with('success', 'Menu makanan berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $this->authorizeProduct($product);
        $product->delete();
        return redirect()->route('owner.products.index')->with('success', 'Menu makanan berhasil dihapus.');
    }

    private function authorizeProduct(Product $product)
    {
        $restaurant = $this->getRestaurant();
        if ($product->restaurant_id !== $restaurant->id) {
            abort(403, 'Akses ditolak: Menu ini bukan milik restoran Anda.');
        }
    }
}

// Alias for backwards compatibility with lowercase references
if (!class_exists('App\Http\Controllers\owner\productController', false)) {
    class_alias(ProductController::class, 'App\Http\Controllers\owner\productController');
}
