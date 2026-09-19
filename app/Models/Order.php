<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $guarded = ['id'];

    protected $casts = [
        'total_price' => 'decimal:2',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
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

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function orderItems(): HasMany
    {
        return $this->items();
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'order_id');
    }

    // Accessors
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending'    => 'warning',
            'diproses'   => 'info',
            'dikirim'    => 'primary',
            'selesai'    => 'success',
            'dibatalkan' => 'danger',
            default      => 'secondary',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'    => 'Menunggu Konfirmasi',
            'diproses'   => 'Sedang Diproses',
            'dikirim'    => 'Dalam Pengiriman',
            'selesai'    => 'Pesanan Selesai',
            'dibatalkan' => 'Pesanan Dibatalkan',
            default      => ucfirst($this->status ?? 'Menunggu'),
        };
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    public function getOrderNumberAttribute(): string
    {
        return 'ORD-' . str_pad((string)$this->id, 6, '0', STR_PAD_LEFT);
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'transfer' => 'Transfer Bank',
            'cod'      => 'Cash on Delivery (COD)',
            'ewallet'  => 'E-Wallet / QRIS',
            default    => strtoupper($this->payment_method ?? '-'),
        };
    }

    // State Check Helpers
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'diproses';
    }

    public function isDelivering(): bool
    {
        return $this->status === 'dikirim';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'selesai';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'dibatalkan';
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'diproses']);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'diproses');
    }

    public function scopeDelivering($query)
    {
        return $query->where('status', 'dikirim');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'selesai');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'dibatalkan');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForRestaurant($query, $restaurantId)
    {
        return $query->where('restaurant_id', $restaurantId);
    }
}

// Alias for backwards compatibility with lowercase references
if (!class_exists('App\Models\order', false)) {
    class_alias(Order::class, 'App\Models\order');
}
