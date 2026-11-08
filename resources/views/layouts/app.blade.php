<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'FutsalHub - Sistem Reservasi Lapangan Futsal Modern')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo2.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo2.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo2.png') }}">
    
    <!-- Google Fonts: Plus Jakarta Sans -->
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
<body class="bg-slate-50 text-slate-800 min-h-screen flex antialiased">

    <!-- Customer Sidebar Navigation -->
    <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col shrink-0 min-h-screen border-r border-slate-800 sticky top-0 h-screen print:hidden">
        <!-- Brand Header -->
        <div class="h-16 flex items-center px-6 gap-3 border-b border-slate-800">
            <img src="{{ asset('images/logo2.png') }}" alt="Logo FutsalHub" class="w-9 h-9 object-contain">
            <div>
                <a href="{{ route('home') }}" class="block">
                    <h1 class="text-base font-extrabold text-white tracking-tight">Futsal<span class="text-emerald-400">Hub</span></h1>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-emerald-400">Customer Arena</span>
                </a>
            </div>
        </div>

        <!-- Navigation Links: 3 Main Menus -->
        <div class="flex-1 py-6 px-4 space-y-1.5 overflow-y-auto">
            <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500 px-3 mb-2">Menu Utama</div>

            <!-- 1. Home Page (Hero & Katalog) -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-colors {{ request()->routeIs('home') ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-house w-5 text-center {{ request()->routeIs('home') ? 'text-white' : 'text-emerald-400' }}"></i>
                <span>Home</span>
            </a>

            <!-- 2. History Booking -->
            <a href="{{ route('booking.my') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-colors {{ request()->routeIs('booking.my*') ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-receipt w-5 text-center {{ request()->routeIs('booking.my*') ? 'text-white' : 'text-emerald-400' }}"></i>
                <span>History Booking</span>
            </a>

            <!-- 3. Settings (Pengaturan Akun) -->
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-colors {{ request()->routeIs('profile.*') ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-gear w-5 text-center {{ request()->routeIs('profile.*') ? 'text-white' : 'text-emerald-400' }}"></i>
                <span>Settings</span>
            </a>

            @auth
                @if(auth()->user()->isAdmin())
                    <div class="pt-6 pb-2">
                        <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500 px-3 mb-2">Administrator</div>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-amber-300 hover:bg-slate-800 hover:text-white transition-colors">
                            <i class="fa-solid fa-gauge-high w-5 text-center text-amber-400"></i>
                            <span>Admin Control</span>
                        </a>
                    </div>
                @endif
            @endauth
        </div>

        <!-- Bottom Sidebar: User Avatar, Info & Logout -->
        <div class="p-4 border-t border-slate-800 bg-slate-900/60">
            @auth
                <div class="flex items-center gap-3 px-2 py-2 mb-3 rounded-xl bg-slate-800/40 border border-slate-800">
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full object-cover border border-emerald-500/40 shrink-0">
                    <div class="overflow-hidden flex-1">
                        <p class="text-xs font-bold text-white truncate" title="{{ auth()->user()->name }}">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] text-emerald-400 font-medium truncate flex items-center gap-1 mt-0.5">
                            <i class="fa-brands fa-whatsapp text-xs"></i>
                            <span>{{ auth()->user()->phone ?? 'Belum ada nomor' }}</span>
                        </p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2.5 text-xs font-bold text-rose-400 hover:text-white hover:bg-rose-500/20 rounded-xl transition-colors cursor-pointer border border-rose-500/30">
                        <i class="fa-solid fa-right-from-bracket"></i> Log out
                    </button>
                </form>
            @else
                <div class="space-y-2">
                    <a href="{{ route('login') }}" class="w-full flex items-center justify-center gap-2 px-3 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl transition-colors">
                        <i class="fa-solid fa-arrow-right-to-bracket"></i> Masuk Akun
                    </a>
                    <a href="{{ route('register') }}" class="w-full flex items-center justify-center gap-2 px-3 py-2 text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-800 rounded-xl transition-colors">
                        Daftar Baru
                    </a>
                </div>
            @endauth
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto h-screen print:h-auto print:overflow-visible">
        <!-- Top Minimal Header Bar -->
        <header class="h-16 bg-white border-b border-slate-200 px-6 sm:px-8 flex items-center justify-between sticky top-0 z-30 shadow-xs shrink-0 print:hidden">
            <div>
                <h2 class="text-base sm:text-lg font-bold text-slate-800">@yield('page_title', 'FutsalHub Customer Arena')</h2>
                <p class="text-[11px] text-slate-500 hidden sm:block">Reservasi Lapangan Futsal Standar Internasional</p>
            </div>

            <div class="flex items-center gap-3 sm:gap-4">
                <!-- Date -->
                <div class="text-right hidden sm:block">
                    <span class="text-xs font-semibold text-slate-600"><i class="fa-regular fa-calendar-days text-emerald-600 mr-1.5"></i> {{ date('l, d F Y') }}</span>
                </div>
                <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>
                
                <!-- Status Badge -->
                <span class="hidden md:inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Booking Online Buka
                </span>

                <!-- Notification Bell Dropdown -->
                @auth
                    @if(auth()->user()->isCustomer())
                        <div class="relative" id="notifDropdownWrapper">
                            <button type="button" onclick="toggleNotificationDropdown()" 
                                class="relative w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-900 transition-colors flex items-center justify-center cursor-pointer border border-slate-200 focus:outline-none"
                                title="Notifikasi Verifikasi Booking">
                                <i class="fa-regular fa-bell text-sm"></i>
                                @if(isset($customerNotifications) && $customerNotifications->isNotEmpty())
                                    <span id="notifBadgeCounter" class="absolute -top-1 -right-1 flex h-4 w-4">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-4 w-4 bg-emerald-600 text-[9px] font-black text-white items-center justify-center">
                                            {{ $customerNotifications->count() }}
                                        </span>
                                    </span>
                                @endif
                            </button>

                            <!-- Notification Panel -->
                            <div id="notifDropdownMenu" class="hidden absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-slate-200 py-2 z-50 animate-in fade-in zoom-in-95 duration-150">
                                <div class="px-4 py-2.5 border-b border-slate-100 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-bell text-emerald-600 text-xs"></i>
                                        <span class="text-xs font-extrabold text-slate-900">Notifikasi Booking</span>
                                    </div>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-700">
                                        {{ isset($customerNotifications) ? $customerNotifications->count() : 0 }} Pemberitahuan
                                    </span>
                                </div>

                                <div class="max-h-72 overflow-y-auto divide-y divide-slate-100">
                                    @if(isset($customerNotifications) && $customerNotifications->isNotEmpty())
                                        @foreach($customerNotifications as $notifBooking)
                                            @php
                                                $isConfirmed = in_array($notifBooking->status, ['confirmed', 'completed']);
                                                $isRejectedByAdmin = ($notifBooking->status === 'cancelled' && $notifBooking->cancelled_by === 'admin');
                                            @endphp

                                            <a href="{{ route('booking.show', $notifBooking->id) }}" class="p-3.5 hover:bg-slate-50 flex items-start gap-3 transition-colors block group">
                                                @if($isConfirmed)
                                                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5 group-hover:scale-110 transition-transform">
                                                        <i class="fa-solid fa-circle-check text-sm"></i>
                                                    </div>
                                                    <div class="flex-1 overflow-hidden">
                                                        <div class="flex items-center justify-between gap-2">
                                                            <span class="text-xs font-bold text-slate-900 group-hover:text-emerald-700 transition-colors">Booking Dikonfirmasi!</span>
                                                            <span class="text-[10px] text-slate-400 font-medium whitespace-nowrap">{{ $notifBooking->updated_at->diffForHumans() }}</span>
                                                        </div>
                                                        <p class="text-[11px] text-slate-600 mt-0.5 leading-snug">
                                                            Pesanan <strong class="text-slate-800 font-mono">{{ $notifBooking->booking_code }}</strong> ({{ $notifBooking->field->name }}) telah diverifikasi &amp; disetujui.
                                                        </p>
                                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 mt-1">
                                                            <i class="fa-solid fa-receipt text-[9px]"></i> Lihat di Riwayat Booking &rarr;
                                                        </span>
                                                    </div>
                                                @elseif($isRejectedByAdmin)
                                                    <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0 mt-0.5 group-hover:scale-110 transition-transform">
                                                        <i class="fa-solid fa-circle-xmark text-sm"></i>
                                                    </div>
                                                    <div class="flex-1 overflow-hidden">
                                                        <div class="flex items-center justify-between gap-2">
                                                            <span class="text-xs font-bold text-rose-700 transition-colors">Booking Ditolak Admin</span>
                                                            <span class="text-[10px] text-slate-400 font-medium whitespace-nowrap">{{ $notifBooking->updated_at->diffForHumans() }}</span>
                                                        </div>
                                                        <p class="text-[11px] text-slate-600 mt-0.5 leading-snug">
                                                            Pesanan <strong class="text-slate-800 font-mono">{{ $notifBooking->booking_code }}</strong> ({{ $notifBooking->field->name }}) ditolak oleh admin.
                                                        </p>
                                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-rose-600 mt-1">
                                                            <i class="fa-solid fa-circle-info text-[9px]"></i> Lihat Detail Pembatalan &rarr;
                                                        </span>
                                                    </div>
                                                @else
                                                    <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0 mt-0.5 group-hover:scale-110 transition-transform">
                                                        <i class="fa-solid fa-ban text-sm"></i>
                                                    </div>
                                                    <div class="flex-1 overflow-hidden">
                                                        <div class="flex items-center justify-between gap-2">
                                                            <span class="text-xs font-bold text-slate-800 group-hover:text-amber-700 transition-colors">Booking Dibatalkan</span>
                                                            <span class="text-[10px] text-slate-400 font-medium whitespace-nowrap">{{ $notifBooking->updated_at->diffForHumans() }}</span>
                                                        </div>
                                                        <p class="text-[11px] text-slate-600 mt-0.5 leading-snug">
                                                            Pesanan <strong class="text-slate-800 font-mono">{{ $notifBooking->booking_code }}</strong> ({{ $notifBooking->field->name }}) telah dibatalkan.
                                                        </p>
                                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-600 mt-1">
                                                            <i class="fa-solid fa-receipt text-[9px]"></i> Lihat Detail &rarr;
                                                        </span>
                                                    </div>
                                                @endif
                                            </a>
                                        @endforeach
                                    @else
                                        <div class="p-6 text-center text-slate-400">
                                            <i class="fa-regular fa-bell-slash text-2xl mb-2 text-slate-300 block"></i>
                                            <p class="text-xs font-medium">Belum ada notifikasi baru.</p>
                                        </div>
                                    @endif
                                </div>

                                <div class="px-4 py-2 border-t border-slate-100 bg-slate-50/60 text-center">
                                    <a href="{{ route('booking.my') }}" class="text-[11px] font-bold text-emerald-700 hover:text-emerald-800 hover:underline inline-flex items-center gap-1">
                                        <span>Buka Semua Riwayat Booking</span>
                                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                @endauth

                <!-- Profile Avatar on Top Right -->
                <div class="h-6 w-px bg-slate-200"></div>
                @auth
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 hover:bg-slate-100 p-1 rounded-xl transition-colors" title="Pengaturan Profil">
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full object-cover border border-emerald-500/40 shadow-xs">
                        <span class="text-xs font-bold text-slate-700 hidden sm:inline">{{ auth()->user()->name }}</span>
                    </a>
                @endauth
            </div>
        </header>

        <!-- Global Flash Alerts -->
        <div class="px-6 sm:px-8 pt-6 w-full print:hidden">
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

        <!-- Main Content Area -->
        <main class="flex-1 pb-16 print:pb-0 print:overflow-visible">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-slate-900 text-slate-400 text-xs border-t border-slate-800 py-6 px-6 sm:px-8 mt-auto shrink-0 print:hidden">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/logo2.png') }}" alt="Logo FutsalHub" class="w-6 h-6 object-contain">
                    <span class="text-sm font-extrabold text-white">Futsal<span class="text-emerald-400">Hub</span> Arena</span>
                    <span class="text-slate-600">•</span>
                    <span class="text-slate-500 text-[11px]">&copy; {{ date('Y') }} Uji Kompetensi Keahlian (LSP SMK RPL)</span>
                </div>
                <div class="flex items-center gap-4 text-[11px] text-slate-400">
                    <span><i class="fa-brands fa-whatsapp text-emerald-400 mr-1"></i> 0812-3456-7890</span>
                    <span><i class="fa-solid fa-location-dot text-emerald-400 mr-1"></i> Arena Indoor Blok A</span>
                </div>
            </div>
        </footer>
    </div>

    <script>
        const CURRENT_USER_ID = {{ auth()->check() ? auth()->id() : 'null' }};
        const STORAGE_KEY = CURRENT_USER_ID ? `futsalhub_last_seen_notif_${CURRENT_USER_ID}` : null;
        const LATEST_NOTIF_TIME = {{ (isset($customerNotifications) && $customerNotifications->isNotEmpty()) ? $customerNotifications->first()->updated_at->timestamp : 0 }};

        // Check on page load if user already viewed current notifications
        document.addEventListener('DOMContentLoaded', function() {
            const badge = document.getElementById('notifBadgeCounter');
            if (badge && STORAGE_KEY) {
                const lastSeenTime = parseInt(localStorage.getItem(STORAGE_KEY) || '0', 10);
                // If user already viewed at or after latest notification timestamp, hide badge
                if (lastSeenTime >= LATEST_NOTIF_TIME) {
                    badge.classList.add('hidden');
                }
            }
        });

        function toggleNotificationDropdown() {
            const menu = document.getElementById('notifDropdownMenu');
            const badge = document.getElementById('notifBadgeCounter');

            if (menu) {
                const willOpen = menu.classList.contains('hidden');
                menu.classList.toggle('hidden');

                // When user opens notification dropdown, mark as read and hide counter badge
                if (willOpen) {
                    if (badge) {
                        badge.classList.add('hidden');
                    }
                    if (STORAGE_KEY) {
                        // Save current timestamp or latest notification timestamp to localStorage
                        const nowTs = Math.floor(Date.now() / 1000);
                        localStorage.setItem(STORAGE_KEY, Math.max(nowTs, LATEST_NOTIF_TIME));
                    }
                }
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('notifDropdownWrapper');
            const menu = document.getElementById('notifDropdownMenu');
            if (wrapper && menu && !wrapper.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
