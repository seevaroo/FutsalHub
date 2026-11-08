<?php

namespace App\Providers;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share recent confirmed notification to customer header
        View::composer('layouts.app', function ($view) {
            $customerNotifications = collect();

            if (Auth::check() && Auth::user()->isCustomer()) {
                $customerNotifications = Booking::with(['field', 'payment'])
                    ->where('user_id', Auth::id())
                    ->whereIn('status', ['confirmed', 'completed', 'cancelled'])
                    ->latest('updated_at')
                    ->take(5)
                    ->get();
            }

            $view->with('customerNotifications', $customerNotifications);
        });
    }
}
