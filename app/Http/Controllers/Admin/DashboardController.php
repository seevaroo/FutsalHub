<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Field;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard with key operational statistics.
     */
    public function index()
    {
        $totalBookings = Booking::count();

        // Revenue from verified transactions (confirmed and completed)
        $totalRevenue = Booking::whereIn('status', ['confirmed', 'completed'])->sum('total_price');

        $pendingBookings = Booking::where('status', 'pending')->count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();
        $completedBookings = Booking::where('status', 'completed')->count();
        $cancelledBookings = Booking::where('status', 'cancelled')->count();

        $totalFields = Field::count();
        $totalCustomers = User::where('role', 'customer')->count();

        $pendingPayments = Payment::where('status', 'pending')->count();

        $recentBookings = Booking::with(['user', 'field', 'bookingDetails.timeSlot', 'payment'])
            ->latest()
            ->take(6)
            ->get();

        return view('admin.dashboard', compact(
            'totalBookings',
            'totalRevenue',
            'pendingBookings',
            'confirmedBookings',
            'completedBookings',
            'cancelledBookings',
            'totalFields',
            'totalCustomers',
            'pendingPayments',
            'recentBookings'
        ));
    }
}

