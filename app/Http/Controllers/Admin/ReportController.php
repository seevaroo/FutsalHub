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
        $startDate = $request->query('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', Carbon::now()->endOfMonth()->toDateString());
        $status = $request->query('status');
        $fieldId = $request->query('field_id');

        $query = Booking::with(['user', 'field', 'bookingDetails.timeSlot', 'payment'])
            ->whereBetween('booking_date', [$startDate, $endDate]);

        if ($status) {
            $query->where('status', $status);
        }

        if ($fieldId) {
            $query->where('field_id', $fieldId);
        }

        $bookings = $query->orderBy('booking_date', 'desc')->get();

        $totalIncome = $bookings->whereIn('status', ['confirmed', 'completed'])->sum('total_price');
        $totalTransactions = $bookings->count();
        $successfulBookings = $bookings->whereIn('status', ['confirmed', 'completed']);
        $cashCount = $successfulBookings->filter(fn($b) => $b->payment && $b->payment->payment_method === 'cash')->count();
        $qrisCount = $successfulBookings->filter(fn($b) => $b->payment && $b->payment->payment_method === 'qris')->count();
        $pendingCount = $bookings->where('status', 'pending')->count();
        $cancelledCount = $bookings->where('status', 'cancelled')->count();

        $fields = Field::all();

        return view('admin.reports.index', compact(
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

        $query = Booking::with(['user', 'field', 'bookingDetails.timeSlot', 'payment'])
            ->whereBetween('booking_date', [$startDate, $endDate]);

        if ($status) {
            $query->where('status', $status);
        }

        if ($fieldId) {
            $query->where('field_id', $fieldId);
        }

        $bookings = $query->orderBy('booking_date', 'asc')->get();

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

