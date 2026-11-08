@extends('layouts.auth')

@section('title', 'Masuk Akun - FutsalHub')

@section('content')
<div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 p-8 sm:p-10 transition-all">
    <!-- Header Logo & Title -->
    <div class="text-center mb-7">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-slate-50 border border-slate-100 p-3 mb-4 shadow-xs">
            <img src="{{ asset('images/logo2.png') }}" alt="Logo FutsalHub" class="w-full h-full object-contain">
        </div>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Selamat Datang</h2>
        <p class="text-xs text-slate-500 mt-1.5 font-medium">Masuk ke akun FutsalHub Anda untuk reservasi lapangan</p>
    </div>

    <!-- Quick Fill for LSP Assessor -->
    <div class="mb-6 p-4 bg-slate-50/80 rounded-2xl border border-slate-200/80 text-xs">
        <p class="font-bold text-slate-700 mb-2.5 flex items-center gap-1.5 text-xs">
            <i class="fa-solid fa-bolt text-amber-500"></i> Akun Demo:
        </p>
        <div class="grid grid-cols-2 gap-2.5">
            <button type="button" onclick="fillCredentials('admin@futsal.com', 'password123')" class="px-3 py-2 bg-white hover:bg-emerald-50 border border-slate-200 hover:border-emerald-300 rounded-xl text-left transition-all group cursor-pointer shadow-2xs">
                <span class="block font-bold text-slate-800 text-[11px] group-hover:text-emerald-700">Admin</span>
                <span class="block text-[10px] text-slate-500 font-medium">admin@futsal.com</span>
            </button>
            <button type="button" onclick="fillCredentials('customer@gmail.com', 'password123')" class="px-3 py-2 bg-white hover:bg-emerald-50 border border-slate-200 hover:border-emerald-300 rounded-xl text-left transition-all group cursor-pointer shadow-2xs">
                <span class="block font-bold text-slate-800 text-[11px] group-hover:text-emerald-700">Customer</span>
                <span class="block text-[10px] text-slate-500 font-medium">customer@gmail.com</span>
            </button>
        </div>
    </div>

    <!-- Alert Error / Success -->
    @if(session('error'))
        <div class="mb-5 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if(session('success'))
        <div class="mb-5 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Login Form -->
    <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
        @csrf

        <!-- Email Input -->
        <div>
            <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Email</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                    <i class="fa-regular fa-envelope text-sm"></i>
                </span>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full pl-10 pr-4 py-3 text-xs sm:text-sm bg-white border @error('email') border-rose-300 bg-rose-50/20 @else border-emerald-500/80 ring-2 ring-emerald-500/10 @enderror rounded-2xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-600 transition-all text-slate-800 font-medium"
                    placeholder="contoh@email.com">
            </div>
            @error('email')
                <p class="text-rose-600 text-[11px] mt-1 font-bold">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password Input -->
        <div>
            <div class="flex justify-between items-center mb-1.5">
                <label for="password" class="block text-xs font-bold text-slate-700">Password</label>
            </div>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                    <i class="fa-solid fa-lock text-sm"></i>
                </span>
                <input type="password" id="password" name="password" required
                    class="w-full pl-10 pr-10 py-3 text-xs sm:text-sm bg-slate-50/70 border @error('password') border-rose-300 bg-rose-50/20 @else border-slate-200 @enderror rounded-2xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-slate-800 font-medium"
                    placeholder="••••••••">
                <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                    <i id="eye-icon" class="fa-regular fa-eye text-sm"></i>
                </button>
            </div>
            @error('password')
                <p class="text-rose-600 text-[11px] mt-1 font-bold">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me Checkbox -->
        <div class="flex items-center justify-between text-xs pt-0.5">
            <label class="flex items-center gap-2 cursor-pointer select-none">
                <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                <span class="text-slate-600 font-medium">Ingat Saya</span>
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full py-3.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-sm rounded-2xl shadow-lg shadow-emerald-600/30 transition-all transform active:scale-[0.99] flex items-center justify-center gap-2 cursor-pointer mt-2">
            <span>Masuk Sekarang</span>
            <i class="fa-solid fa-arrow-right text-xs"></i>
        </button>
    </form>

    <div class="mt-7 text-center text-xs text-slate-500">
        Belum punya akun? 
        <a href="{{ route('register') }}" class="font-bold text-emerald-600 hover:text-emerald-700 hover:underline">
            Daftar Akun Baru
        </a>
    </div>
</div>

@push('scripts')
<script>
    function fillCredentials(email, password) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = password;
    }

    function togglePassword() {
        const input = document.getElementById('password');
        const icon = document.getElementById('eye-icon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endpush
@endsection
