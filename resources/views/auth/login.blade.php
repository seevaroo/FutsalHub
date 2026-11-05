@extends('layouts.app')

@section('title', 'Masuk Akun - FutsalHub')

@section('content')
<div class="max-w-md mx-auto px-4 py-12">
    <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-200 p-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-slate-50 border border-slate-100 p-2 mb-4 shadow-sm">
                <img src="{{ asset('images/logo.png') }}" alt="Logo FutsalHub" class="w-full h-full object-contain">
            </div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Selamat Datang</h2>
            <p class="text-xs text-slate-500 mt-1">Masuk ke akun FutsalHub Anda untuk reservasi lapangan</p>
        </div>

        <!-- Quick Fill for LSP Assessor -->
        <div class="mb-6 p-3.5 bg-slate-50 rounded-xl border border-slate-200 text-xs">
            <p class="font-bold text-slate-700 mb-2 flex items-center gap-1.5">
                <i class="fa-solid fa-bolt text-amber-500"></i> Akun Pengujian (LSP SMK):
            </p>
            <div class="grid grid-cols-2 gap-2">
                <button type="button" onclick="fillCredentials('admin@futsal.com', 'password123')" class="px-2.5 py-1.5 bg-white hover:bg-emerald-50 border border-slate-200 hover:border-emerald-300 rounded-lg text-left transition-colors group">
                    <span class="block font-bold text-slate-800 text-[11px] group-hover:text-emerald-700">Admin</span>
                    <span class="block text-[10px] text-slate-500">admin@futsal.com</span>
                </button>
                <button type="button" onclick="fillCredentials('customer@gmail.com', 'password123')" class="px-2.5 py-1.5 bg-white hover:bg-emerald-50 border border-slate-200 hover:border-emerald-300 rounded-lg text-left transition-colors group">
                    <span class="block font-bold text-slate-800 text-[11px] group-hover:text-emerald-700">Customer</span>
                    <span class="block text-[10px] text-slate-500">customer@gmail.com</span>
                </button>
            </div>
        </div>

        <!-- Login Form -->
        <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <i class="fa-regular fa-envelope text-sm"></i>
                    </span>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 border @error('email') border-rose-300 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all"
                        placeholder="contoh@email.com">
                </div>
                @error('email')
                    <p class="text-rose-600 text-xs mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <label for="password" class="block text-xs font-bold text-slate-700">Password</label>
                </div>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <i class="fa-solid fa-lock text-sm"></i>
                    </span>
                    <input type="password" id="password" name="password" required
                        class="w-full pl-10 pr-10 py-2.5 text-sm bg-slate-50 border @error('password') border-rose-300 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all"
                        placeholder="••••••••">
                    <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                        <i id="eye-icon" class="fa-regular fa-eye text-sm"></i>
                    </button>
                </div>
                @error('password')
                    <p class="text-rose-600 text-xs mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                    <span class="text-slate-600 font-medium">Ingat Saya</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-emerald-600/30 transition-all transform active:scale-[0.99] flex items-center justify-center gap-2">
                <span>Masuk Sekarang</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-slate-500">
            Belum punya akun? 
            <a href="{{ route('register') }}" class="font-bold text-emerald-600 hover:text-emerald-700 hover:underline">
                Daftar Akun Baru
            </a>
        </div>
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

