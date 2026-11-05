<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Field extends Model
{
    use HasFactory;

    protected $table = 'fields';

    protected $fillable = [
        'name',
        'type',
        'price_per_hour',
        'description',
        'image',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price_per_hour' => 'decimal:2',
        ];
    }

    /**
     * Field has many bookings.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'field_id');
    }

    /**
     * Check if field is available.
     */
    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    /**
     * Formatted price per hour in IDR.
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price_per_hour, 0, ',', '.');
    }

    /**
     * Human-friendly floor type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'vinyl' => 'Vinyl',
            'sintetis' => 'Rumput Sintetis',
            'interlock' => 'Interlock Polimer',
            default => ucfirst($this->type),
        };
    }
}

