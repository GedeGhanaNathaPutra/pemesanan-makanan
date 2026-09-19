<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';
    protected $guarded = ['id'];

    protected $casts = [
        'paid_at'    => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // Accessors
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->payment_status) {
            'paid'      => 'success',
            'pending'   => 'warning',
            'failed'    => 'danger',
            'cancelled' => 'secondary',
            default     => 'secondary',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            'paid'      => 'Lunas',
            'pending'   => 'Menunggu Pembayaran',
            'failed'    => 'Pembayaran Gagal',
            'cancelled' => 'Dibatalkan',
            default     => ucfirst($this->payment_status ?? 'Menunggu'),
        };
    }

    public function getIsPaidAttribute(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function getProofUrlAttribute(): ?string
    {
        if ($this->payment_proof && file_exists(public_path('storage/' . $this->payment_proof))) {
            return asset('storage/' . $this->payment_proof);
        }
        return $this->payment_proof ? asset('storage/' . $this->payment_proof) : null;
    }

    // Helper Methods
    public function markAsPaid(): bool
    {
        return $this->update([
            'payment_status' => 'paid',
            'paid_at'        => now(),
        ]);
    }

    public function markAsFailed(): bool
    {
        return $this->update([
            'payment_status' => 'failed',
        ]);
    }
}

// Alias for backwards compatibility with lowercase references
if (!class_exists('App\Models\payment', false)) {
    class_alias(Payment::class, 'App\Models\payment');
}
