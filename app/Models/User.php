<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is customer.
     */
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    /**
     * User has many bookings.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'user_id');
    }

    /**
     * Get user avatar URL with fallback based on role.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && Storage::disk('public')->exists($this->avatar)) {
            return asset('storage/' . $this->avatar);
        }

        if ($this->role === 'admin') {
            return asset('images/default-admin.png');
        }

        return asset('images/default-customer.png');
    }

    /**
     * Get clean WhatsApp number digits for wa.me URL (e.g. 628123456789).
     */
    public function getWhatsappNumberAttribute(): ?string
    {
        if (empty($this->phone)) {
            return null;
        }

        $digits = preg_replace('/[^0-9]/', '', $this->phone);
        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        } elseif (str_starts_with($digits, '8')) {
            $digits = '62' . $digits;
        }

        return $digits;
    }

    /**
     * Get human-formatted phone number with +62 prefix (e.g. +62 898-7654-3210).
     */
    public function getFormattedPhoneAttribute(): ?string
    {
        $digits = $this->whatsapp_number;
        if (!$digits) {
            return null;
        }

        if (str_starts_with($digits, '62')) {
            $rest = substr($digits, 2);
            if (strlen($rest) >= 9) {
                $part1 = substr($rest, 0, 3);
                $part2 = substr($rest, 3, 4);
                $part3 = substr($rest, 7);
                return '+62 ' . $part1 . '-' . $part2 . '-' . $part3;
            }
            return '+62 ' . $rest;
        }

        return '+' . $digits;
    }

    /**
     * Get direct WhatsApp chat URL with prefilled greeting.
     */
    public function getWhatsappUrlAttribute(): ?string
    {
        $number = $this->whatsapp_number;
        if (!$number) {
            return null;
        }

        $text = "Halo kak {$this->name}, kami dari Admin FutsalHub.";
        return "https://wa.me/{$number}?text=" . rawurlencode($text);
    }
}
