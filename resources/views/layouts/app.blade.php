<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'FutsalHub - Sistem Reservasi Lapangan Futsal Modern')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
    
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
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col antialiased">

    <!-- Main Navigation Bar -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Brand Logo -->
                <div class="flex items-center gap-8">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo FutsalHub" class="w-10 h-10 object-contain">
                        <div>
                            <span class="text-xl font-extrabold tracking-tight text-slate-900 group-hover:text-emerald-600 transition-colors">Futsal<span class="text-emerald-600">Hub</span></span>
                            <span class="block text-[10px] uppercase font-bold tracking-widest text-slate-400 -mt-1">Arena Booking</span>
                        </div>
                    </a>

                    <!-- Navigation Links -->
                    <div class="hidden md:flex items-center gap-1">
                        <a href="{{ route('home') }}" class="px-3.5 py-2 text-sm font-semibold rounded-lg transition-colors flex items-center gap-2 {{ request()->routeIs('home') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            <img src="{{ asset('images/katalog.png') }}" alt="Katalog" class="w-8 h-8 rounded-xs object-cover">
                            <span>Katalog Lapangan</span>
                        </a>
                        @auth
                            @if(auth()->user()->isCustomer())
                                <a href="{{ route('booking.my') }}" class="px-3.5 py-2 text-sm font-semibold rounded-lg transition-colors {{ request()->routeIs('booking.my*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                    <i class="fa-solid fa-receipt mr-1.5"></i> Riwayat Booking
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>

                <!-- User Profile / Auth Area -->
                <div class="flex items-center gap-3">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-lg transition-colors">
                                <i class="fa-solid fa-gauge-high text-emerald-400"></i> Panel Admin
                            </a>
                        @endif

                        <div class="relative group">
                            <div class="flex items-center gap-3 cursor-pointer py-1 px-2 rounded-xl hover:bg-slate-100 transition-colors">
                                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-9 h-9 rounded-full object-cover border border-emerald-200">
                                <div class="hidden md:block text-left">
                                    <div class="text-xs font-bold text-slate-800 leading-tight">{{ auth()->user()->name }}</div>
                                    <div class="text-[11px] text-slate-500 capitalize">Role: <span class="font-semibold text-emerald-600">{{ auth()->user()->role }}</span></div>
                                </div>
                                <i class="fa-solid fa-chevron-down text-slate-400 text-xs ml-1"></i>
                            </div>

                            <!-- Dropdown Menu -->
                            <div class="absolute right-0 mt-1 w-52 bg-white rounded-xl shadow-xl border border-slate-200 py-1.5 hidden group-hover:block transition-all z-50">
                                <div class="px-4 py-2 border-b border-slate-100">
                                    <p class="text-xs text-slate-500">Masuk sebagai</p>
                                    <p class="text-xs font-bold text-slate-900 truncate">{{ auth()->user()->email }}</p>
                                </div>

                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                                    <i class="fa-solid fa-user-gear w-4 text-emerald-600"></i> Edit Profil Saya
                                </a>

                                @if(auth()->user()->isCustomer())
                                    <a href="{{ route('booking.my') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                                        <i class="fa-solid fa-ticket-simple w-4 text-emerald-600"></i> Pesanan Saya
                                    </a>
                                @endif

                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                                        <i class="fa-solid fa-gauge-high w-4 text-emerald-600"></i> Dashboard Admin
                                    </a>
                                    <a href="{{ route('admin.fields.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                                        <i class="fa-solid fa-futbol w-4 text-emerald-600"></i> Kelola Lapangan
                                    </a>
                                    <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                                        <i class="fa-solid fa-money-check-dollar w-4 text-emerald-600"></i> Verifikasi Pembayaran
                                    </a>
                                    <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                                        <i class="fa-solid fa-chart-line w-4 text-emerald-600"></i> Rekap Laporan
                                    </a>
                                @endif

                                <div class="border-t border-slate-100 my-1"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 text-left">
                                        <i class="fa-solid fa-right-from-bracket w-4"></i> Keluar (Logout)
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-xs font-bold text-slate-700 hover:text-slate-900 rounded-lg hover:bg-slate-100 transition-colors">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-sm shadow-emerald-600/30 transition-colors">
                            Daftar Akun
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Global Flash Alerts -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-4">
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center justify-between shadow-xs mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 text-sm">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl flex items-center justify-between shadow-xs mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-rose-100 text-rose-700 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-800 text-sm">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if(session('warning'))
            <div class="bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-xl flex items-center justify-between shadow-xs mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-circle-exclamation"></i>
                    </div>
                    <span class="text-sm font-medium">{{ session('warning') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-amber-600 hover:text-amber-800 text-sm">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="flex-1 pb-16">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 text-xs border-t border-slate-800 py-10 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="space-y-3">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo FutsalHub" class="w-8 h-8 object-contain">
                    <span class="text-base font-extrabold text-white">Futsal<span class="text-emerald-400">Hub</span></span>
                </div>
                <p class="text-slate-400 leading-relaxed text-xs">
                    Platform reservasi lapangan futsal modern, cepat, dan transparan. Dilengkapi sistem real-time slot checking untuk kemudahan tim Anda.
                </p>
                <p class="text-emerald-400 font-semibold text-[11px]">
                    Uji Kompetensi Keahlian (UKK) LSP SMK RPL
                </p>
            </div>

            <div>
                <h4 class="text-white font-bold text-sm mb-3">Tipe Lapangan</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('home', ['type' => 'vinyl']) }}" class="hover:text-emerald-400 transition-colors">Lapangan Vinyl (Standar FIFA)</a></li>
                    <li><a href="{{ route('home', ['type' => 'sintetis']) }}" class="hover:text-emerald-400 transition-colors">Rumput Sintetis Premium</a></li>
                    <li><a href="{{ route('home', ['type' => 'interlock']) }}" class="hover:text-emerald-400 transition-colors">Interlock Polimer Anti-Slip</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-bold text-sm mb-3">Metode Pembayaran</h4>
                <ul class="space-y-2">
                    <li class="flex items-center gap-2"><i class="fa-solid fa-qrcode text-emerald-400"></i> QRIS (Semua Bank & E-Wallet)</li>
                    <li class="flex items-center gap-2"><i class="fa-solid fa-money-bill-wave text-emerald-400"></i> Bayar di Tempat (Cash Kasir)</li>
                    <li class="flex items-center gap-2"><i class="fa-solid fa-shield-halved text-emerald-400"></i> Verifikasi Cepat & Otomatis</li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-bold text-sm mb-3">Kontak & Lokasi</h4>
                <p class="leading-relaxed mb-2">Arena FutsalHub, Jl. Olahraga Utama Blok A No. 10</p>
                <p class="text-slate-300 font-semibold"><i class="fa-solid fa-phone mr-1.5 text-emerald-400"></i> (021) 7890-1234</p>
                <p class="text-slate-300 font-semibold mt-1"><i class="fa-brands fa-whatsapp mr-1.5 text-emerald-400"></i> 0812-3456-7890</p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 pt-6 border-t border-slate-800 text-center text-slate-500 text-[11px]">
            &copy; {{ date('Y') }} FutsalHub Arena. Hak Cipta Dilindungi Undang-Undang. Dibangun untuk Sistem Uji Kompetensi Keahlian (UKK) Rekayasa Perangkat Lunak.
        </div>
    </footer>

    @stack('scripts')
</body>
</html>

