@extends('layouts.auth')

@section('title', 'Daftar Akun Baru - FutsalHub')

@section('content')
<div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 p-8 sm:p-10 transition-all">
    <!-- Header -->
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-slate-50 border border-slate-100 p-3 mb-3 shadow-xs">
            <img src="{{ asset('images/logo2.png') }}" alt="Logo FutsalHub" class="w-full h-full object-contain">
        </div>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Daftar Akun Customer</h2>
        <p class="text-xs text-slate-500 mt-1 font-medium">Buat akun untuk memesan lapangan futsal dengan mudah</p>
    </div>

    <!-- Register Form -->
    <form method="POST" action="{{ route('register.post') }}" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                    <i class="fa-regular fa-user text-sm"></i>
                </span>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                    class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('name') border-rose-300 bg-rose-50/30 @else border-slate-200 @enderror rounded-2xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-medium text-slate-800"
                    placeholder="Contoh: Budi Pratama">
            </div>
            @error('name')
                <p class="text-rose-600 text-xs mt-1 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-xs font-bold text-slate-700 mb-1">Alamat Email</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                    <i class="fa-regular fa-envelope text-sm"></i>
                </span>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('email') border-rose-300 bg-rose-50/30 @else border-slate-200 @enderror rounded-2xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-medium text-slate-800"
                    placeholder="budi@gmail.com">
            </div>
            @error('email')
                <p class="text-rose-600 text-xs mt-1 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="phone" class="block text-xs font-bold text-slate-700 mb-1">Nomor WhatsApp / HP</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                    <i class="fa-brands fa-whatsapp text-sm"></i>
                </span>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required
                    class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('phone') border-rose-300 bg-rose-50/30 @else border-slate-200 @enderror rounded-2xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-medium text-slate-800"
                    placeholder="Contoh: 081234567890">
            </div>
            @error('phone')
                <p class="text-rose-600 text-xs mt-1 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 mb-1">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <i class="fa-solid fa-lock text-sm"></i>
                    </span>
                    <input type="password" id="password" name="password" required
                        class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('password') border-rose-300 bg-rose-50/30 @else border-slate-200 @enderror rounded-2xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-medium text-slate-800"
                        placeholder="Minimal 6 karakter">
                </div>
                @error('password')
                    <p class="text-rose-600 text-xs mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1">Konfirmasi Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <i class="fa-solid fa-lock text-sm"></i>
                    </span>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-medium text-slate-800"
                        placeholder="Ulangi password">
                </div>
            </div>
        </div>

        <button type="submit" class="w-full py-3.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-sm rounded-2xl shadow-lg shadow-emerald-600/30 transition-all transform active:scale-[0.99] flex items-center justify-center gap-2 cursor-pointer mt-4">
            <span>Daftar Sekarang</span>
            <i class="fa-solid fa-arrow-right text-xs"></i>
        </button>
    </form>

    <div class="mt-6 text-center text-xs text-slate-500">
        Sudah memiliki akun? 
        <a href="{{ route('login') }}" class="font-bold text-emerald-600 hover:text-emerald-700 hover:underline">
            Masuk ke Akun
        </a>
    </div>
</div>
@endsection
