<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show profile edit page.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update user profile information.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'phone.required' => 'Nomor WhatsApp / HP wajib diisi.',
            'avatar.image' => 'Berkas foto profil harus berupa gambar.',
            'avatar.mimes' => 'Format gambar yang diperbolehkan: JPG, JPEG, PNG.',
            'avatar.max' => 'Ukuran foto profil maksimal 2MB.',
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.min' => 'Password baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $updateData = [
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
        ];

        // Handle Avatar Upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar from storage if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store new file
            $path = $request->file('avatar')->store('avatars', 'public');
            $updateData['avatar'] = $path;
        }

        // Handle Password Change
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->input('password'));
        }

        // Update database
        $user->update($updateData);

        // Immediately refresh user instance in memory & session
        Auth::setUser($user->fresh());

        return back()->with('success', 'Profil Anda berhasil diperbarui.');
    }
}
