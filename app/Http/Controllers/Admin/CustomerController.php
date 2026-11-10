<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of registered customers.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status', 'active'); // 'active' or 'trashed'

        if ($status === 'trashed') {
            $query = User::onlyTrashed()
                ->where('role', 'customer')
                ->withCount('bookings')
                ->latest('deleted_at');
        } else {
            $query = User::where('role', 'customer')
                ->withCount('bookings')
                ->latest();
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->paginate(10)->withQueryString();

        $activeCount = User::where('role', 'customer')->count();
        $trashedCount = User::onlyTrashed()->where('role', 'customer')->count();
        $newThisMonth = User::where('role', 'customer')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return view('admin.customers.index', compact(
            'customers',
            'search',
            'status',
            'activeCount',
            'trashedCount',
            'newThisMonth'
        ));
    }

    /**
     * Delete (soft-delete) a customer account.
     */
    public function destroy(User $customer)
    {
        if ($customer->isAdmin()) {
            return back()->with('error', 'Akun administrator tidak dapat dihapus.');
        }

        // Snapshot customer details on bookings so history is forever preserved
        Booking::where('user_id', $customer->id)
            ->whereNull('customer_name')
            ->update([
                'customer_name' => $customer->name,
                'customer_phone' => $customer->phone,
            ]);

        $name = $customer->name;
        $email = $customer->email;

        $customer->delete();

        return back()->with('success', "Akun customer {$name} ({$email}) berhasil dihapus dari sistem.");
    }

    /**
     * Restore a soft-deleted customer account.
     */
    public function restore($id)
    {
        $customer = User::onlyTrashed()->where('role', 'customer')->findOrFail($id);
        $customer->restore();

        return back()->with('success', "Akun customer {$customer->name} ({$customer->email}) berhasil dipulihkan / diaktifkan kembali.");
    }
}

