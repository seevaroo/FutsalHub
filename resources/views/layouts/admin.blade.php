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

    <!-- Mobile Backdrop Overlay -->
    <div id="adminSidebarBackdrop" onclick="toggleAdminMobileSidebar()" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-40 hidden md:hidden transition-opacity"></div>

    <!-- Admin Sidebar Navigation (Desktop Static & Mobile Drawer) -->
    <aside id="adminSidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 flex flex-col shrink-0 min-h-screen border-r border-slate-800 transform -translate-x-full md:translate-x-0 md:static md:sticky md:top-0 md:h-screen transition-transform duration-300 ease-in-out">
        <!-- Brand Header -->
        <div class="h-16 flex items-center justify-between px-6 border-b border-slate-800">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo2.png') }}" alt="Logo FutsalHub" class="w-9 h-9 object-contain">
                <div>
                    <h1 class="text-base font-extrabold text-white tracking-tight">Futsal<span class="text-emerald-400">Hub</span></h1>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-emerald-400">Admin Control</span>
                </div>
            </div>
            <!-- Mobile Close Button -->
            <button type="button" onclick="toggleAdminMobileSidebar()" class="md:hidden text-slate-400 hover:text-white p-1 rounded-lg" title="Tutup Menu">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
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
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <!-- Top Header Bar -->
        <header class="h-16 bg-white border-b border-slate-200 px-4 sm:px-6 lg:px-8 flex items-center justify-between sticky top-0 z-30 shadow-xs">
            <div class="flex items-center gap-3">
                <!-- Hamburger Button (Mobile Only) -->
                <button type="button" onclick="toggleAdminMobileSidebar()" class="md:hidden w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center cursor-pointer border border-slate-200 focus:outline-none" title="Buka Menu Admin">
                    <i class="fa-solid fa-bars text-sm"></i>
                </button>

                <!-- Mobile Brand Header -->
                <div class="md:hidden flex items-center gap-2">
                    <img src="{{ asset('images/logo2.png') }}" alt="Logo FutsalHub" class="w-7 h-7 object-contain">
                    <span class="text-sm font-extrabold text-slate-900 tracking-tight">Admin<span class="text-emerald-500">Hub</span></span>
                </div>

                <div class="hidden md:block">
                    <h2 class="text-lg font-bold text-slate-800">@yield('page_title', 'Dashboard')</h2>
                    <p class="text-xs text-slate-500 hidden sm:block">Panel Kontrol Reservasi Lapangan FutsalHub</p>
                </div>
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

        <!-- Dynamic Island Floating Notification Toast -->
        @php
            $flashType = null;
            $flashMessage = null;
            $flashIcon = null;
            $flashColor = null;

            if (session('success')) {
                $flashType = 'success';
                $flashMessage = session('success');
                $flashIcon = 'fa-solid fa-check';
                $flashColor = 'text-emerald-400 bg-emerald-500/20 border-emerald-500/30';
            } elseif (session('error')) {
                $flashType = 'error';
                $flashMessage = session('error');
                $flashIcon = 'fa-solid fa-xmark';
                $flashColor = 'text-rose-400 bg-rose-500/20 border-rose-500/30';
            } elseif (session('warning')) {
                $flashType = 'warning';
                $flashMessage = session('warning');
                $flashIcon = 'fa-solid fa-triangle-exclamation';
                $flashColor = 'text-amber-400 bg-amber-500/20 border-amber-500/30';
            } elseif (session('info')) {
                $flashType = 'info';
                $flashMessage = session('info');
                $flashIcon = 'fa-solid fa-circle-info';
                $flashColor = 'text-sky-400 bg-sky-500/20 border-sky-500/30';
            }
        @endphp

        @if($flashMessage)
            <div id="dynamicIslandToastAdmin" 
                class="fixed top-3 sm:top-4 left-1/2 -translate-x-1/2 z-[100] w-[calc(100%-1.5rem)] sm:w-auto max-w-md transition-all duration-500 ease-out transform -translate-y-16 opacity-0 scale-95 pointer-events-none flex justify-center">
                <div class="flex items-center gap-2.5 sm:gap-3 px-3.5 sm:px-4 py-2 sm:py-2.5 bg-slate-950/95 hover:bg-slate-950 text-white rounded-2xl sm:rounded-full shadow-2xl shadow-slate-950/70 border border-slate-700/80 backdrop-blur-md pointer-events-auto max-w-full">
                    <div class="w-6 h-6 rounded-full {{ $flashColor }} border flex items-center justify-center text-xs shrink-0 shadow-xs">
                        <i class="{{ $flashIcon }} text-[10px]"></i>
                    </div>
                    
                    <span class="text-xs font-semibold text-slate-100 tracking-wide leading-snug line-clamp-2 sm:line-clamp-none break-words select-none flex-1 min-w-0 text-left">
                        {{ $flashMessage }}
                    </span>

                    <button type="button" onclick="dismissDynamicIslandAdmin()" class="text-slate-400 hover:text-white transition-colors p-1 rounded-full text-xs cursor-pointer ml-1 shrink-0" title="Tutup">
                        <i class="fa-solid fa-xmark text-[10px]"></i>
                    </button>
                </div>
            </div>
        @endif

        <!-- Page Content -->
        <main class="flex-1 p-6 sm:p-8">
            @yield('content')
        </main>
    </div>

    <script>
        // Dynamic Island Entrance & Auto-Dismissal
        document.addEventListener('DOMContentLoaded', function() {
            const toast = document.getElementById('dynamicIslandToastAdmin');
            if (toast) {
                setTimeout(() => {
                    toast.classList.remove('-translate-y-16', 'opacity-0', 'scale-95');
                    toast.classList.add('translate-y-0', 'opacity-100', 'scale-100');
                }, 150);

                setTimeout(() => {
                    dismissDynamicIslandAdmin();
                }, 4000);
            }
        });

        function dismissDynamicIslandAdmin() {
            const toast = document.getElementById('dynamicIslandToastAdmin');
            if (toast) {
                toast.classList.remove('translate-y-0', 'opacity-100', 'scale-100');
                toast.classList.add('-translate-y-16', 'opacity-0', 'scale-95');
                setTimeout(() => {
                    toast.remove();
                }, 500);
            }
        }

        // Toggle Admin Mobile Drawer Sidebar
        function toggleAdminMobileSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const backdrop = document.getElementById('adminSidebarBackdrop');
            if (sidebar && backdrop) {
                const isOpen = !sidebar.classList.contains('-translate-x-full');
                if (isOpen) {
                    sidebar.classList.add('-translate-x-full');
                    backdrop.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                } else {
                    sidebar.classList.remove('-translate-x-full');
                    backdrop.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                }
            }
        }
    </script>
    @stack('scripts')
</body>
</html>

