<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            'restaurant_id' => 'nullable|exists:restaurants,id',
            'product_id'    => 'nullable|exists:products,id',
            'rating'        => 'required|integer|min:1|max:5',
            'comment'       => 'nullable|string|max:1000',
        ]);

        // Ensure user has completed an order from this restaurant
        $hasOrder = Order::where('user_id', Auth::id())
            ->where('status', 'selesai')
            ->when($request->restaurant_id, fn ($q) => $q->where('restaurant_id', $request->restaurant_id))
            ->exists();

        if (!$hasOrder) {
            return back()->with('error', 'Anda hanya dapat memberikan ulasan setelah pesanan selesai.');
        }

        Review::create([
            'user_id'       => Auth::id(),
            'restaurant_id' => $request->restaurant_id,
            'product_id'    => $request->product_id,
            'rating'        => $request->rating,
            'comment'       => $request->comment,
        ]);

        return back()->with('success', 'Ulasan Anda berhasil dikirim.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        if ($review->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $review->delete();
        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}
