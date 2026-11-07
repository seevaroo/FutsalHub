<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentVerificationController extends Controller
{
    /**
     * Display table of incoming transactions and payments.
     */
    public function index(Request $request)
    {
        $status = $request->query('status'); // payment status: pending, verified, rejected
        $method = $request->query('method'); // qris, cash
        $search = $request->query('search'); // booking code or customer name

        $query = Booking::with(['user', 'field', 'bookingDetails.timeSlot', 'payment']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        if ($status) {
            $query->whereHas('payment', function ($pq) use ($status) {
                $pq->where('status', $status);
            });
        }

        if ($method) {
            $query->whereHas('payment', function ($pq) use ($method) {
                $pq->where('payment_method', $method);
            });
        }

        $bookings = $query->latest()->paginate(10);

        $counts = [
            'all' => Booking::count(),
            'pending_verif' => Booking::whereHas('payment', fn($q) => $q->where('status', 'pending'))->count(),
            'qris_pending' => Booking::whereHas('payment', fn($q) => $q->where('payment_method', 'qris')->where('status', 'pending'))->count(),
            'cash_pending' => Booking::whereHas('payment', fn($q) => $q->where('payment_method', 'cash')->where('status', 'pending'))->count(),
        ];

        return view('admin.payments.index', compact('bookings', 'status', 'method', 'search', 'counts'));
    }

    /**
     * Approve valid QRIS payment proof.
     */
    public function approve(Booking $booking)
    {
        if ($booking->payment) {
            $booking->payment->update([
                'status' => 'verified',
                'paid_at' => now(),
            ]);
        }

        $booking->update([
            'status' => 'confirmed',
        ]);

        return redirect()->back()
            ->with('success', "Transaksi #{$booking->booking_code} berhasil disetujui. Status booking: Terkonfirmasi.");
    }

    /**
     * Reject invalid QRIS payment proof and cancel booking.
     */
    public function reject(Booking $booking)
    {
        if ($booking->payment) {
            $booking->payment->update([
                'status' => 'rejected',
            ]);
        }

        $booking->update([
            'status' => 'cancelled',
        ]);

        return redirect()->back()
            ->with('warning', "Transaksi #{$booking->booking_code} ditolak. Status booking diubah menjadi Dibatalkan.");
    }

    /**
     * Mark cash transaction as paid and booking as confirmed when customer arrives at venue.
     */
    public function markCashCompleted(Booking $booking)
    {
        if ($booking->payment) {
            $booking->payment->update([
                'status' => 'verified',
                'paid_at' => now(),
            ]);
        } else {
            Payment::create([
                'booking_id' => $booking->id,
                'payment_method' => 'cash',
                'status' => 'verified',
                'paid_at' => now(),
            ]);
        }

        $booking->update([
            'status' => 'confirmed',
        ]);

        return redirect()->back()
            ->with('success', "Pembayaran tunai #{$booking->booking_code} diterima. Status booking: Terkonfirmasi.");
    }
}

