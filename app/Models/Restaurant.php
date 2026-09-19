<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Restaurant extends Model
{
    use HasFactory;

    protected $table = 'restaurants';
    protected $guarded = ['id'];
    protected $casts = [
        'is_open' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($restaurant) {
            if (empty($restaurant->slug)) {
                $restaurant->slug = Str::slug($restaurant->name);
            }
        });
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'restaurant_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'restaurant_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'restaurant_id');
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class, 'restaurant_id');
    }

    // Accessors
    public function getAverageRatingAttribute(): float
    {
        if (isset($this->attributes['reviews_avg_rating'])) {
            return round((float) $this->attributes['reviews_avg_rating'], 1);
        }
        return round((float) ($this->reviews()->avg('rating') ?? 0), 1);
    }

    public function getTotalReviewsAttribute(): int
    {
        return (int) $this->reviews()->count();
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image && file_exists(public_path('storage/' . $this->image))) {
            return asset('storage/' . $this->image);
        }
        return 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=800&q=80';
    }

    public function getStatusBadgeAttribute(): string
    {
        return $this->is_open ? 'success' : 'secondary';
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->is_open ? 'Buka' : 'Tutup';
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->where('is_open', true);
    }

    public function scopeSearch($query, ?string $keyword)
    {
        if (!$keyword) return $query;
        return $query->where(function ($q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%")
              ->orWhere('address', 'like', "%{$keyword}%")
              ->orWhere('description', 'like', "%{$keyword}%");
        });
    }
}

// Alias for backwards compatibility with lowercase references
if (!class_exists('App\Models\restaurant', false)) {
    class_alias(Restaurant::class, 'App\Models\restaurant');
}
