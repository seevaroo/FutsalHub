<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TimeSlot extends Model
{
    use HasFactory;

    protected $table = 'time_slots';

    protected $fillable = [
        'start_time',
        'end_time',
    ];

    /**
     * TimeSlot has many booking details.
     */
    public function bookingDetails(): HasMany
    {
        return $this->hasMany(BookingDetail::class, 'slot_id');
    }

    /**
     * Formatted time range e.g. "08:00 - 09:00"
     */
    public function getTimeRangeAttribute(): string
    {
        $start = substr($this->start_time, 0, 5);
        $end = substr($this->end_time, 0, 5);
        return "{$start} - {$end}";
    }
}

