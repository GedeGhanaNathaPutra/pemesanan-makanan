<?php

namespace App\Http\Controllers\owner;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestaurantController extends Controller
{
    public function index()
    {
        $restaurant = Auth::user()->restaurant;
        return view('owner.restaurant.index', compact('restaurant'));
    }

    public function create()
    {
        return view('owner.restaurant.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->restaurant) {
            return redirect()->route('owner.restaurant.index')->with('error', 'Anda sudah memiliki restoran.');
        }

        $data = $request->validate([
            'name'        => 'required|string|max:150',
            'description' => 'nullable|string',
            'address'     => 'required|string',
            'phone'       => 'nullable|string|max:20',
        ]);

        $data['user_id'] = Auth::id();
        $data['is_open'] = true;
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('restaurants', 'public');
        }

        Restaurant::create($data);
        return redirect()->route('owner.dashboard')->with('success', 'Restoran berhasil dibuat!');
    }

    public function edit(Restaurant $restaurant)
    {
        $this->authorizeRestaurant($restaurant);
        return view('owner.restaurant.edit', compact('restaurant'));
    }

    public function update(Request $request, Restaurant $restaurant)
    {
        $this->authorizeRestaurant($restaurant);

        $data = $request->validate([
            'name'        => 'required|string|max:150',
            'description' => 'nullable|string',
            'address'     => 'required|string',
            'phone'       => 'nullable|string|max:20',
            'is_open'     => 'boolean',
        ]);

        $data['is_open'] = $request->boolean('is_open', false);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('restaurants', 'public');
        }

        $restaurant->update($data);
        return redirect()->route('owner.restaurant.index')->with('success', 'Restoran berhasil diperbarui.');
    }

    private function authorizeRestaurant(Restaurant $restaurant)
    {
        if ($restaurant->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak: Anda bukan pemilik restoran ini.');
        }
    }
}

if (!class_exists('App\Http\Controllers\owner\restaurantController', false)) {
    class_alias(RestaurantController::class, 'App\Http\Controllers\owner\restaurantController');
}
