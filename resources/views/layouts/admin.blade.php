<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel - FutsalHub')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo2.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo2.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo2.png') }}">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            900: '#064e3b',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen flex antialiased">

    <!-- Admin Sidebar Navigation -->
    <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col shrink-0 min-h-screen border-r border-slate-800">
        <!-- Brand Header -->
        <div class="h-16 flex items-center px-6 gap-3 border-b border-slate-800">
            <img src="{{ asset('images/logo2.png') }}" alt="Logo FutsalHub" class="w-9 h-9 object-contain">
            <div>
                <h1 class="text-base font-extrabold text-white tracking-tight">Futsal<span class="text-emerald-400">Hub</span></h1>
                <span class="text-[10px] font-bold uppercase tracking-widest text-emerald-400">Admin Control</span>
            </div>
        </div>

        <!-- Navigation Links -->
        <div class="flex-1 py-6 px-4 space-y-1.5 overflow-y-auto">
            <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500 px-3 mb-2">Menu Utama</div>

            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-gauge-high w-5 text-center {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-emerald-400' }}"></i>
                Dashboard
            </a>

            <a href="{{ route('admin.fields.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-colors {{ request()->routeIs('admin.fields.*') ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-futbol w-5 text-center {{ request()->routeIs('admin.fields.*') ? 'text-white' : 'text-emerald-400' }}"></i>
                Kelola Lapangan
            </a>

            <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-colors {{ request()->routeIs('admin.payments.*') ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-money-check-dollar w-5 text-center {{ request()->routeIs('admin.payments.*') ? 'text-white' : 'text-emerald-400' }}"></i>
                <span>Verifikasi Transaksi</span>
            </a>

            <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-colors {{ request()->routeIs('admin.reports.*') ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-chart-line w-5 text-center {{ request()->routeIs('admin.reports.*') ? 'text-white' : 'text-emerald-400' }}"></i>
                Rekap Laporan
            </a>

            <div class="pt-6 pb-2">
                <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500 px-3 mb-2">Akses Cepat</div>
                <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-400 hover:bg-slate-800 hover:text-white transition-colors">
                    <i class="fa-solid fa-arrow-up-right-from-square w-5 text-center text-teal-400"></i>
                    Buka Beranda Web
                </a>
            </div>
        </div>

        <!-- User Profile & Logout at Bottom -->
        <div class="p-4 border-t border-slate-800">
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-2 py-2 mb-2 rounded-xl hover:bg-slate-800/80 transition-colors group">
                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-9 h-9 rounded-full object-cover border border-emerald-500/40">
                <div class="overflow-hidden flex-1">
                    <p class="text-xs font-bold text-white truncate group-hover:text-emerald-400 transition-colors">{{ auth()->user()->name }}</p>
                    <p class="text-[11px] text-slate-400 font-medium flex items-center justify-between">
                        <span>Administrator</span>
                        <i class="fa-solid fa-gear text-[10px] text-slate-500 group-hover:text-emerald-400"></i>
                    </p>
                </div>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2 text-xs font-semibold text-rose-400 hover:text-white hover:bg-rose-500/20 rounded-xl transition-colors">
                    <i class="fa-solid fa-right-from-bracket"></i> Keluar (Logout)
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <!-- Top Header Bar -->
        <header class="h-16 bg-white border-b border-slate-200 px-6 sm:px-8 flex items-center justify-between sticky top-0 z-30 shadow-xs">
            <div>
                <h2 class="text-lg font-bold text-slate-800">@yield('page_title', 'Dashboard')</h2>
                <p class="text-xs text-slate-500 hidden sm:block">Panel Kontrol Reservasi Lapangan FutsalHub</p>
            </div>

            <div class="flex items-center gap-3 sm:gap-4">
                <div class="text-right hidden sm:block">
                    <span class="text-xs font-semibold text-slate-600"><i class="fa-regular fa-calendar-days text-emerald-600 mr-1.5"></i> {{ date('l, d F Y') }}</span>
                </div>
                <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Sistem Aktif
                </span>
                <div class="h-6 w-px bg-slate-200"></div>
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 hover:bg-slate-100 p-1 rounded-xl transition-colors" title="Pengaturan Profil Admin">
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full object-cover border border-emerald-500/40 shadow-xs">
                    <span class="text-xs font-bold text-slate-700 hidden md:inline">{{ auth()->user()->name }}</span>
                </a>
            </div>
        </header>

        <!-- Flash Notifications -->
        <div class="px-6 sm:px-8 pt-6">
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center justify-between shadow-xs mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <span class="text-xs font-semibold">{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 text-sm">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl flex items-center justify-between shadow-xs mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-xs">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <span class="text-xs font-semibold">{{ session('error') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-800 text-sm">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if(session('warning'))
                <div class="bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-xl flex items-center justify-between shadow-xs mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-xs">
                            <i class="fa-solid fa-circle-exclamation"></i>
                        </div>
                        <span class="text-xs font-semibold">{{ session('warning') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-amber-600 hover:text-amber-800 text-sm">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif
        </div>

        <!-- Page Content -->
        <main class="flex-1 p-6 sm:p-8">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>

