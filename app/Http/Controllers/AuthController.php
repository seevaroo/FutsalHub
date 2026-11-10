<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return Auth::user()->role === 'admin'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('home');
        }

        return view('auth.login');
    }

    /**
     * Handle login authentication.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $remember = $request->boolean('remember');

        // Cek jika akun telah dihapus / dinonaktifkan oleh administrator
        $trashedUser = User::onlyTrashed()->where('email', $credentials['email'])->first();
        if ($trashedUser) {
            return back()->with('error', 'Akun Anda telah dinonaktifkan atau dihapus oleh administrator. Silakan hubungi pengelola arena jika ini sebuah kekeliruan.')->onlyInput('email');
        }

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Selamat datang di FutsalHub, ' . $user->name . '!');
            }

            return redirect()->route('home')
                ->with('success', 'Selamat datang di FutsalHub, ' . $user->name . '!');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan tidak sesuai.',
        ])->onlyInput('email');
    }

    /**
     * Show register form.
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return Auth::user()->role === 'admin'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('home');
        }

        return view('auth.register');
    }

    /**
     * Handle registration for new customers.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'phone.required' => 'Nomor WhatsApp / HP wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Normalisasi nomor WhatsApp ke format internasional (+62...)
        $phoneInput = preg_replace('/[^0-9]/', '', $validated['phone']);
        if (str_starts_with($phoneInput, '0')) {
            $phoneFormatted = '+62' . substr($phoneInput, 1);
        } elseif (str_starts_with($phoneInput, '8')) {
            $phoneFormatted = '+62' . $phoneInput;
        } elseif (str_starts_with($phoneInput, '62')) {
            $phoneFormatted = '+' . $phoneInput;
        } else {
            $phoneFormatted = '+62' . $phoneInput;
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $phoneFormatted,
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
        ]);

        return redirect()->route('login')
            ->with('success', 'Akun berhasil dibuat! Silakan masuk dengan email dan password Anda.');
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil logout.');
    }
}

