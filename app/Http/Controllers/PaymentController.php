<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Show payment checkout page.
     */
    public function checkout(Booking $booking)
    {
        // Ensure only the booking owner can checkout
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk transaksi ini.');
        }

        // If booking already confirmed or completed, redirect to history
        if (in_array($booking->status, ['confirmed', 'completed'], true)) {
            return redirect()->route('booking.my')
                ->with('info', 'Pesanan ini sudah terkonfirmasi / lunas.');
        }

        // If cancelled, redirect to home with info
        if ($booking->status === 'cancelled') {
            return redirect()->route('home')
                ->with('info', 'Pesanan ini telah dibatalkan.');
        }

        $booking->load(['field', 'bookingDetails.timeSlot', 'payment']);

        return view('payments.checkout', compact('booking'));
    }

    /**
     * Process payment submission (QRIS with proof or Cash at venue).
     */
    public function process(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'payment_method' => ['required', 'in:qris,cash'],
            'proof_image' => ['required_if:payment_method,qris', 'nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'payment_method.required' => 'Pilih metode pembayaran terlebih dahulu.',
            'proof_image.required_if' => 'Unggah foto struk / bukti transfer untuk pembayaran QRIS.',
            'proof_image.image' => 'File bukti transfer harus berupa gambar.',
            'proof_image.mimes' => 'Format gambar yang didukung: JPG, JPEG, PNG, WEBP.',
            'proof_image.max' => 'Ukuran file gambar maksimal 2MB.',
        ]);

        $paymentMethod = $request->input('payment_method');
        $proofPath = null;

        if ($paymentMethod === 'qris' && $request->hasFile('proof_image')) {
            $proofPath = $request->file('proof_image')->store('receipts', 'public');

            Payment::updateOrCreate(
                ['booking_id' => $booking->id],
                [
                    'payment_method' => 'qris',
                    'proof_image' => $proofPath,
                    'paid_at' => now(),
                    'status' => 'pending',
                ]
            );

            return redirect()->route('booking.my')
                ->with('success', 'Bukti transfer berhasil diunggah! Pembayaran QRIS Anda sedang menunggu verifikasi admin.');
        }

        if ($paymentMethod === 'cash') {
            Payment::updateOrCreate(
                ['booking_id' => $booking->id],
                [
                    'payment_method' => 'cash',
                    'proof_image' => null,
                    'paid_at' => null,
                    'status' => 'pending',
                ]
            );

            return redirect()->route('booking.my')
                ->with('success', 'Pesanan berhasil dikonfirmasi dengan metode Bayar di Tempat (Cash). Silakan lakukan pelunasan di kasir saat tiba di lokasi.');
        }

        return back()->with('error', 'Terjadi kesalahan saat memproses metode pembayaran.');
    }

    /**
     * Cancel booking/payment while still in pending status.
     */
    public function cancel(Request $request, Booking $booking)
    {
        // Ensure only booking owner can cancel
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk membatalkan pesanan ini.');
        }

        // Only allow cancellation if booking is pending
        if ($booking->status !== 'pending') {
            return redirect()->route('booking.my')
                ->with('error', 'Pesanan ini tidak dapat dibatalkan karena sudah dikonfirmasi atau telah selesai.');
        }

        // If proof image was uploaded, delete it to keep storage clean
        if ($booking->payment && $booking->payment->proof_image) {
            if (Storage::disk('public')->exists($booking->payment->proof_image)) {
                Storage::disk('public')->delete($booking->payment->proof_image);
            }
            $booking->payment->update([
                'status' => 'rejected',
                'proof_image' => null,
            ]);
        } elseif ($booking->payment) {
            $booking->payment->update([
                'status' => 'rejected',
            ]);
        }

        // Mark booking as cancelled, freeing up the time slots
        $booking->update([
            'status' => 'cancelled',
        ]);

        if ($request->query('redirect_to') === 'my_bookings') {
            return redirect()->route('booking.my')
                ->with('info', "Pesanan #{$booking->booking_code} telah dibatalkan dan jadwal telah dikosongkan kembali.");
        }

        return redirect()->route('home')
            ->with('info', "Pesanan #{$booking->booking_code} telah dibatalkan. Jam bermain telah dikosongkan kembali.");
    }
}
