<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'booking_id',
        'payment_method',
        'proof_image',
        'paid_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
        ];
    }

    /**
     * Payment belongs to a booking.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    /**
     * Readable payment method name.
     */
    public function getMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'qris' => 'QRIS (Transfer / E-Wallet)',
            'cash' => 'Bayar di Tempat (Cash)',
            default => strtoupper($this->payment_method ?? '-'),
        };
    }

    /**
     * Status label and badge color classes.
     */
    public function getStatusMetaAttribute(): array
    {
        return match ($this->status) {
            'pending' => [
                'label' => 'Menunggu Verifikasi',
                'badge' => 'bg-amber-100 text-amber-800 border-amber-300',
                'color' => 'amber',
            ],
            'verified' => [
                'label' => 'Lunas / Terverifikasi',
                'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                'color' => 'emerald',
            ],
            'rejected' => [
                'label' => 'Ditolak',
                'badge' => 'bg-rose-100 text-rose-800 border-rose-300',
                'color' => 'rose',
            ],
            default => [
                'label' => ucfirst($this->status),
                'badge' => 'bg-gray-100 text-gray-800 border-gray-300',
                'color' => 'gray',
            ],
        };
    }
}

