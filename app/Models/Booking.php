<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';

    protected $fillable = [
        'booking_code',
        'user_id',
        'customer_name',
        'customer_phone',
        'field_id',
        'booking_date',
        'total_price',
        'status',
        'cancelled_by',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'total_price' => 'decimal:2',
        ];
    }

    /**
     * Booking belongs to a user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Booking belongs to a field.
     */
    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class, 'field_id');
    }

    /**
     * Booking has many booking details.
     */
    public function bookingDetails(): HasMany
    {
        return $this->hasMany(BookingDetail::class, 'booking_id');
    }

    /**
     * Booking has one payment.
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'booking_id');
    }

    /**
     * Formatted total price in IDR.
     */
    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    /**
     * Get display customer name, fallback if user was deleted.
     */
    public function getCustomerNameAttribute($value): string
    {
        if ($this->user) {
            return $this->user->name;
        }
        return $value ?: 'Pengguna (Akun Dihapus)';
    }

    /**
     * Get display customer phone, fallback if user was deleted.
     */
    public function getCustomerPhoneAttribute($value): string
    {
        if ($this->user) {
            return $this->user->phone;
        }
        return $value ?: '-';
    }

    /**
     * WhatsApp chat URL for the booking's customer.
     */
    public function getWhatsappUrlAttribute(): ?string
    {
        $phone = $this->customer_phone;
        if (!$phone || $phone === '-') {
            return null;
        }

        $digits = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        } elseif (str_starts_with($digits, '8')) {
            $digits = '62' . $digits;
        }

        $text = "Halo kak {$this->customer_name}, kami dari Admin FutsalHub Arena terkait pesanan booking #{$this->booking_code}.";
        return "https://wa.me/{$digits}?text=" . rawurlencode($text);
    }

    /**
     * Status label and badge color classes.
     */
    public function getStatusMetaAttribute(): array
    {
        if ($this->status === 'pending') {
            // Jika sudah ada record payment & ada bukti gambar atau metode QRIS
            if ($this->payment && $this->payment->proof_image && $this->payment->status === 'pending') {
                return [
                    'label' => 'Menunggu Verifikasi Admin',
                    'badge' => 'bg-amber-100 text-amber-800 border-amber-300',
                    'color' => 'amber',
                ];
            }

            if ($this->payment && $this->payment->payment_method === 'cash') {
                return [
                    'label' => 'Bayar di Kasir (Pending)',
                    'badge' => 'bg-teal-100 text-teal-800 border-teal-300',
                    'color' => 'teal',
                ];
            }

            return [
                'label' => 'Menunggu Pembayaran',
                'badge' => 'bg-amber-100 text-amber-800 border-amber-300',
                'color' => 'amber',
            ];
        }

        return match ($this->status) {
            'confirmed', 'completed' => [
                'label' => 'Terkonfirmasi',
                'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                'color' => 'emerald',
            ],
            'cancelled' => [
                'label' => 'Dibatalkan',
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

