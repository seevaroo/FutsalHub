<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingDetail extends Model
{
    use HasFactory;

    protected $table = 'booking_details';

    protected $fillable = [
        'booking_id',
        'slot_id',
        'price',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    /**
     * Detail belongs to a booking.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    /**
     * Detail belongs to a time slot.
     */
    public function timeSlot(): BelongsTo
    {
        return $this->belongsTo(TimeSlot::class, 'slot_id');
    }

    /**
     * Formatted slot price in IDR.
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}

