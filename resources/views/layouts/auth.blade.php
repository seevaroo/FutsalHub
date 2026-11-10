<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Masuk Akun - FutsalHub')</title>

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
<body class="bg-slate-50 text-slate-800 min-h-screen flex items-center justify-center p-4 antialiased relative">

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
        <div id="dynamicIslandToastAuth" 
            class="fixed top-3 sm:top-4 left-1/2 -translate-x-1/2 z-[9999] w-[calc(100%-1.5rem)] sm:w-auto max-w-md transition-all duration-500 ease-out transform -translate-y-16 opacity-0 scale-95 pointer-events-none flex justify-center">
            <div class="flex items-center gap-2.5 sm:gap-3 px-3.5 sm:px-4 py-2 sm:py-2.5 bg-slate-950/95 hover:bg-slate-950 text-white rounded-2xl sm:rounded-full shadow-2xl shadow-slate-950/70 border border-slate-700/80 backdrop-blur-md pointer-events-auto max-w-full">
                <div class="w-6 h-6 rounded-full {{ $flashColor }} border flex items-center justify-center text-xs shrink-0 shadow-xs">
                    <i class="{{ $flashIcon }} text-[10px]"></i>
                </div>
                
                <span class="text-xs font-semibold text-slate-100 tracking-wide leading-snug line-clamp-2 sm:line-clamp-none break-words select-none flex-1 min-w-0 text-left">
                    {{ $flashMessage }}
                </span>

                <button type="button" onclick="dismissDynamicIslandAuth()" class="text-slate-400 hover:text-white transition-colors p-1 rounded-full text-xs cursor-pointer ml-1 shrink-0" title="Tutup">
                    <i class="fa-solid fa-xmark text-[10px]"></i>
                </button>
            </div>
        </div>
    @endif

    <!-- Main Auth Container -->
    <div class="w-full max-w-lg">
        @yield('content')
    </div>

    <script>
        // Dynamic Island Entrance & Auto-Dismissal
        document.addEventListener('DOMContentLoaded', function() {
            const toast = document.getElementById('dynamicIslandToastAuth');
            if (toast) {
                setTimeout(() => {
                    toast.classList.remove('-translate-y-16', 'opacity-0', 'scale-95');
                    toast.classList.add('translate-y-0', 'opacity-100', 'scale-100');
                }, 150);

                setTimeout(() => {
                    dismissDynamicIslandAuth();
                }, 4000);
            }
        });

        function dismissDynamicIslandAuth() {
            const toast = document.getElementById('dynamicIslandToastAuth');
            if (toast) {
                toast.classList.remove('translate-y-0', 'opacity-100', 'scale-100');
                toast.classList.add('-translate-y-16', 'opacity-0', 'scale-95');
                setTimeout(() => {
                    toast.remove();
                }, 500);
            }
        }
    </script>
    @stack('scripts')
</body>
</html>

