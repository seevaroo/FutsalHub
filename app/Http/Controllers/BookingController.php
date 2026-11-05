<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\Field;
use App\Models\TimeSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * Show court booking form (select date and operational slots).
     */
    public function create(Field $field)
    {
        if ($field->status === 'maintenance') {
            return redirect()->route('home')->with('error', 'Lapangan ini sedang dalam tahap perbaikan (Maintenance).');
        }

        $timeSlots = TimeSlot::orderBy('start_time')->get();

        return view('bookings.create', compact('field', 'timeSlots'));
    }

    /**
     * Internal API endpoint to check occupied slots for a given field and date.
     * GET /api/check-slots?field_id=...&date=...
     */
    public function checkSlots(Request $request)
    {
        $fieldId = $request->query('field_id');
        $date = $request->query('date');

        if (!$fieldId || !$date) {
            return response()->json([
                'status' => 'error',
                'message' => 'Parameter field_id dan date wajib disertakan.',
                'booked_slots' => [],
            ], 422);
        }

        // Get slots that are already booked and not cancelled
        $bookedSlots = BookingDetail::whereHas('booking', function ($query) use ($fieldId, $date) {
            $query->where('field_id', $fieldId)
                  ->where('booking_date', $date)
                  ->whereIn('status', ['pending', 'confirmed', 'completed']);
        })->pluck('slot_id')->unique()->values();

        return response()->json([
            'status' => 'success',
            'field_id' => (int) $fieldId,
            'date' => $date,
            'booked_slots' => $bookedSlots,
        ]);
    }

    /**
     * Store new booking with DB transaction and pessimistic lock to prevent double booking.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'field_id' => ['required', 'exists:fields,id'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'slots' => ['required', 'array', 'min:1'],
            'slots.*' => ['required', 'exists:time_slots,id'],
        ], [
            'field_id.required' => 'Lapangan wajib dipilih.',
            'booking_date.required' => 'Tanggal sewa wajib dipilih.',
            'booking_date.after_or_equal' => 'Tanggal sewa tidak boleh di masa lalu.',
            'slots.required' => 'Pilih minimal satu slot jam bermain.',
            'slots.min' => 'Pilih minimal satu slot jam bermain.',
        ]);

        $field = Field::findOrFail($validated['field_id']);

        if ($field->status === 'maintenance') {
            return back()->with('error', 'Maaf, lapangan ini sedang dalam perbaikan.')->withInput();
        }

        $bookingDate = $validated['booking_date'];
        $selectedSlots = array_unique($validated['slots']);

        try {
            $booking = DB::transaction(function () use ($field, $bookingDate, $selectedSlots) {
                // Pessimistic locking: check if any of the chosen slots are already taken
                $conflictingSlot = BookingDetail::whereIn('slot_id', $selectedSlots)
                    ->whereHas('booking', function ($query) use ($field, $bookingDate) {
                        $query->where('field_id', $field->id)
                              ->where('booking_date', $bookingDate)
                              ->whereIn('status', ['pending', 'confirmed', 'completed']);
                    })
                    ->lockForUpdate()
                    ->first();

                if ($conflictingSlot) {
                    throw new \Exception('Maaf, salah satu jam yang Anda pilih baru saja dipesan oleh orang lain. Silakan pilih jam lain.');
                }

                // Generate unique booking code with format FTS-XXXXXX
                do {
                    $bookingCode = 'FTS-' . strtoupper(Str::random(6));
                } while (Booking::where('booking_code', $bookingCode)->exists());

                $totalSlots = count($selectedSlots);
                $totalPrice = $totalSlots * $field->price_per_hour;

                // Create booking record
                $newBooking = Booking::create([
                    'booking_code' => $bookingCode,
                    'user_id' => Auth::id(),
                    'field_id' => $field->id,
                    'booking_date' => $bookingDate,
                    'total_price' => $totalPrice,
                    'status' => 'pending',
                ]);

                // Create booking details
                foreach ($selectedSlots as $slotId) {
                    BookingDetail::create([
                        'booking_id' => $newBooking->id,
                        'slot_id' => $slotId,
                        'price' => $field->price_per_hour,
                    ]);
                }

                return $newBooking;
            });

            return redirect()->route('payment.checkout', $booking->id)
                ->with('success', 'Booking berhasil dibuat! Silakan selesaikan pembayaran untuk mengonfirmasi jadwal Anda.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Display personal booking history for logged-in customer.
     */
    public function myBookings(Request $request)
    {
        $status = $request->query('status');

        $query = Booking::with(['field', 'bookingDetails.timeSlot', 'payment'])
            ->where('user_id', Auth::id());

        if ($status && in_array($status, ['pending', 'confirmed', 'completed', 'cancelled'], true)) {
            $query->where('status', $status);
        }

        $bookings = $query->latest()->paginate(10);

        return view('bookings.index', compact('bookings', 'status'));
    }

    /**
     * Display details of a single booking.
     */
    public function show(Booking $booking)
    {
        // Restrict customer from viewing another user's booking
        if (Auth::user()->isCustomer() && $booking->user_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak melihat data pesanan ini.');
        }

        $booking->load(['user', 'field', 'bookingDetails.timeSlot', 'payment']);

        return view('bookings.show', compact('booking'));
    }
}

