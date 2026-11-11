<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Field;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display rental report with date range filters.
     */
    public function index(Request $request)
    {
        $fields = Field::all();

        // Cek apakah filter sudah diterapkan
        $isFiltered = $request->has('filter');

        $startDate = $request->query('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', Carbon::now()->endOfMonth()->toDateString());
        $status = $request->query('status');
        $fieldId = $request->query('field_id');

        if (!$isFiltered) {
            // Kondisi awal: Menunggu difilter
            $bookings = Booking::whereRaw('1 = 0')->paginate(10);
            $totalIncome = 0;
            $totalTransactions = 0;
            $successfulBookings = collect();
            $cashCount = 0;
            $qrisCount = 0;
            $pendingCount = 0;
            $cancelledCount = 0;

            return view('admin.reports.index', compact(
                'isFiltered',
                'bookings',
                'startDate',
                'endDate',
                'status',
                'fieldId',
                'fields',
                'totalIncome',
                'totalTransactions',
                'successfulBookings',
                'cashCount',
                'qrisCount',
                'pendingCount',
                'cancelledCount'
            ));
        }

        // Kondisi setelah difilter
        $query = Booking::with(['user', 'field', 'bookingDetails.timeSlot', 'payment']);

        if ($startDate && $endDate) {
            $query->whereBetween('booking_date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('booking_date', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('booking_date', '<=', $endDate);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($fieldId) {
            $query->where('field_id', $fieldId);
        }

        // Urutkan dari tanggal booking terbaru, waktu dibuat terbaru, dan ID terbaru
        $query->orderBy('booking_date', 'desc')
              ->orderBy('created_at', 'desc')
              ->orderBy('id', 'desc');

        // Hitung akumulasi statistik dari seluruh data hasil filter (sebelum dipaginasi)
        $allFiltered = (clone $query)->get();
        $totalIncome = $allFiltered->whereIn('status', ['confirmed', 'completed'])->sum('total_price');
        $totalTransactions = $allFiltered->count();
        $successfulBookings = $allFiltered->whereIn('status', ['confirmed', 'completed']);
        $cashCount = $successfulBookings->filter(fn($b) => $b->payment && $b->payment->payment_method === 'cash')->count();
        $qrisCount = $successfulBookings->filter(fn($b) => $b->payment && $b->payment->payment_method === 'qris')->count();
        $pendingCount = $allFiltered->where('status', 'pending')->count();
        $cancelledCount = $allFiltered->where('status', 'cancelled')->count();

        // Paginasi 10 data per halaman dengan format prev - next
        $bookings = $query->paginate(10)->withQueryString();

        return view('admin.reports.index', compact(
            'isFiltered',
            'bookings',
            'startDate',
            'endDate',
            'status',
            'fieldId',
            'fields',
            'totalIncome',
            'totalTransactions',
            'successfulBookings',
            'cashCount',
            'qrisCount',
            'pendingCount',
            'cancelledCount'
        ));
    }

    /**
     * Display printable report view.
     */
    public function print(Request $request)
    {
        $startDate = $request->query('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', Carbon::now()->endOfMonth()->toDateString());
        $status = $request->query('status');
        $fieldId = $request->query('field_id');

        $query = Booking::with(['user', 'field', 'bookingDetails.timeSlot', 'payment']);

        if ($startDate && $endDate) {
            $query->whereBetween('booking_date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('booking_date', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('booking_date', '<=', $endDate);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($fieldId) {
            $query->where('field_id', $fieldId);
        }

        // Urutkan terbaru agar konsisten dengan tampilan tabel
        $bookings = $query->orderBy('booking_date', 'desc')
                          ->orderBy('created_at', 'desc')
                          ->orderBy('id', 'desc')
                          ->get();

        $totalIncome = $bookings->whereIn('status', ['confirmed', 'completed'])->sum('total_price');
        $totalTransactions = $bookings->count();

        $selectedField = $fieldId ? Field::find($fieldId) : null;

        return view('admin.reports.print', compact(
            'bookings',
            'startDate',
            'endDate',
            'status',
            'selectedField',
            'totalIncome',
            'totalTransactions'
        ));
    }
}

