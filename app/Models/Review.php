<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';
    protected $guarded = ['id'];

    protected $casts = [
        'rating'     => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Accessors
    public function getStarsAttribute(): string
    {
        return str_repeat('⭐', max(1, min(5, (int)$this->rating)));
    }

    // Scopes
    public function scopeForRestaurant($query, $restaurantId)
    {
        return $query->where('restaurant_id', $restaurantId);
    }

    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeLatestFirst($query)
    {
        return $query->latest();
    }
}

// Alias for backwards compatibility with lowercase references
if (!class_exists('App\Models\review', false)) {
    class_alias(Review::class, 'App\Models\review');
}
