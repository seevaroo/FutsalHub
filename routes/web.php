<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FieldController as AdminFieldController;
use App\Http\Controllers\Admin\PaymentVerificationController as AdminPaymentVerificationController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
// Saat membuka link utama (/), langsung arahkan ke halaman Login
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('home');
    }
    return redirect()->route('login');
});

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Halaman Beranda Utama & Katalog Lapangan
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::get('/katalog', [HomeController::class, 'catalog'])->name('catalog')->middleware('auth');

// Profile routes (Customer & Admin)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Internal API for real-time slot checking
Route::get('/api/check-slots', [BookingController::class, 'checkSlots'])->name('api.check-slots');

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/booking/field/{field}', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('booking.my');
    Route::get('/booking/invoice/{booking}', [BookingController::class, 'show'])->name('booking.show');

    // Payment Checkout, Upload & Cancel
    Route::get('/payment/checkout/{booking}', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::post('/payment/process/{booking}', [PaymentController::class, 'process'])->name('payment.process');
    Route::post('/payment/cancel/{booking}', [PaymentController::class, 'cancel'])->name('payment.cancel');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Master Field CRUD
    Route::resource('fields', AdminFieldController::class)->except(['show']);

    // Payment Verification
    Route::get('/payments', [AdminPaymentVerificationController::class, 'index'])->name('payments.index');
    Route::post('/payments/{booking}/approve', [AdminPaymentVerificationController::class, 'approve'])->name('payments.approve');
    Route::post('/payments/{booking}/reject', [AdminPaymentVerificationController::class, 'reject'])->name('payments.reject');
    Route::post('/payments/{booking}/cash-completed', [AdminPaymentVerificationController::class, 'markCashCompleted'])->name('payments.cash_completed');

    // Rental Reports
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/print', [AdminReportController::class, 'print'])->name('reports.print');
});
