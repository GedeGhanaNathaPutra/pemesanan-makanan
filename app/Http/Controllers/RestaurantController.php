<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function index(Request $request)
    {
        $query = Restaurant::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->has('is_open') && $request->is_open !== '' && $request->is_open !== null) {
            $query->where('is_open', (bool) $request->is_open);
        }

        $restaurants = $query->latest()->paginate(15);
        $statusCounts = [
            'all'    => Restaurant::count(),
            'open'   => Restaurant::where('is_open', true)->count(),
            'closed' => Restaurant::where('is_open', false)->count(),
        ];

        return view('view.admin.restaurant.index', compact('restaurants', 'statusCounts'));
    }
 
    public function create()
    {
        $owners = User::where('role','owner')->get();
        return view('view.admin.restaurant.create', compact('owners'));
    }
 
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'     => 'required|exists:users,id',
            'name'        => 'required|string|max:150',
            'description' => 'nullable|string',
            'address'     => 'required|string',
            'phone'       => 'nullable|string|max:20',
            'is_open'     => 'boolean',
        ]);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('restaurants','public');
        }
        Restaurant::create($data);
        return redirect()->route('admin.restaurants.index')->with('success', 'Restoran berhasil dibuat.');
    }
 
    public function edit(Restaurant $restaurant)
    {
        $owners = User::where('role','owner')->get();
        return view('view.admin.restaurant.edit', compact('restaurant','owners'));
    }
 
    public function update(Request $request, Restaurant $restaurant)
    {
        $data = $request->validate([
            'user_id'     => 'required|exists:users,id',
            'name'        => 'required|string|max:150',
            'description' => 'nullable|string',
            'address'     => 'required|string',
            'phone'       => 'nullable|string|max:20',
            'is_open'     => 'boolean',
        ]);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('restaurants','public');
        }
        $restaurant->update($data);
        return redirect()->route('admin.restaurants.index')->with('success', 'Restoran berhasil diperbarui.');
    }
 
    public function destroy(Restaurant $restaurant)
    {
        $restaurant->delete();
        return redirect()->route('admin.restaurants.index')->with('success', 'Restoran berhasil dihapus.');
    }
}
