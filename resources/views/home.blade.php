@extends('layouts.app')

@section('title', 'Katalog Lapangan Futsal - FutsalHub')

@section('content')
<!-- Hero Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-8">
    <div class="relative bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 text-white rounded-3xl overflow-hidden py-12 sm:py-16 px-6 sm:px-10 lg:px-12 shadow-xl border border-slate-800/80">
        <div class="absolute inset-0 bg-[radial-gradient(#10b981_1px,transparent_1px)] [background-size:24px_24px] opacity-10"></div>
        <div class="relative z-10 max-w-3xl">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 mb-4">
                <i class="fa-solid fa-trophy text-amber-400"></i> Arena Futsal Modern Standar Nasional
            </span>
            <h1 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight mb-4">
                Mainkan Pertandingan Terbaik di <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-300">FutsalHub</span>
            </h1>
            <p class="text-sm sm:text-base text-slate-300 leading-relaxed">
                Pilih lapangan favorit Anda dengan lantai Vinyl, Rumput Sintetis, atau Interlock. Cek ketersediaan jam operasional secara real-time dan pesan langsung tanpa ribet!
            </p>
        </div>
    </div>
</div>

<!-- Catalog & Filter Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Filter Tabs Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Katalog Lapangan</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Pilih lapangan sesuai jenis lantai dan kenyamanan tim Anda</p>
        </div>

        <!-- Filter Pills -->
        <div class="flex flex-wrap items-center gap-2 bg-white p-1.5 rounded-2xl border border-slate-200 shadow-xs">
            <a href="{{ route('home') }}" class="px-4 py-2 text-xs font-bold rounded-xl transition-all {{ empty($type) ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                Semua Lapangan ({{ $stats['total_fields'] }})
            </a>
            <a href="{{ route('home', ['type' => 'vinyl']) }}" class="px-4 py-2 text-xs font-bold rounded-xl transition-all {{ $type === 'vinyl' ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                Vinyl ({{ $stats['types_count']['vinyl'] }})
            </a>
            <a href="{{ route('home', ['type' => 'sintetis']) }}" class="px-4 py-2 text-xs font-bold rounded-xl transition-all {{ $type === 'sintetis' ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                Sintetis ({{ $stats['types_count']['sintetis'] }})
            </a>
            <a href="{{ route('home', ['type' => 'interlock']) }}" class="px-4 py-2 text-xs font-bold rounded-xl transition-all {{ $type === 'interlock' ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                Interlock ({{ $stats['types_count']['interlock'] }})
            </a>
        </div>
    </div>

    <!-- Court Cards Grid -->
    @if($fields->isEmpty())
        <div class="bg-white rounded-2xl border border-dashed border-slate-300 p-12 text-center">
            <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                <i class="fa-solid fa-futbol text-2xl"></i>
            </div>
            <h3 class="text-base font-bold text-slate-800">Tidak ada lapangan ditemukan</h3>
            <p class="text-xs text-slate-500 mt-1">Belum ada lapangan yang sesuai dengan filter pilihan Anda.</p>
            <a href="{{ route('home') }}" class="mt-4 inline-block px-4 py-2 bg-emerald-600 text-white text-xs font-bold rounded-xl">
                Tampilkan Semua Lapangan
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($fields as $field)
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 flex flex-col group">
                    <!-- Image Area -->
                    <div class="relative h-52 bg-slate-900 overflow-hidden">
                        <img src="{{ $field->image ? asset('storage/' . $field->image) : asset('images/katalog.png') }}" alt="{{ $field->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                        <!-- Floor Type Badge -->
                        <div class="absolute top-3 left-3">
                            @if($field->type === 'vinyl')
                                <span class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-blue-600 text-white shadow-md">
                                    <i class="fa-solid fa-layer-group mr-1"></i> Vinyl FIFA
                                </span>
                            @elseif($field->type === 'sintetis')
                                <span class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-emerald-600 text-white shadow-md">
                                    <i class="fa-solid fa-seedling mr-1"></i> Sintetis
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-purple-600 text-white shadow-md">
                                    <i class="fa-solid fa-grip mr-1"></i> Interlock
                                </span>
                            @endif
                        </div>

                        <!-- Status Badge -->
                        <div class="absolute top-3 right-3">
                            @if($field->isAvailable())
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-white/95 text-emerald-700 shadow-md backdrop-blur">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Tersedia
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-white/95 text-rose-700 shadow-md backdrop-blur">
                                    <span class="w-2 h-2 rounded-full bg-rose-500"></span> Maintenance
                                </span>
                            @endif
                        </div>

                        <!-- Price Tag Ribbon at Bottom -->
                        <div class="absolute bottom-3 right-3 bg-slate-900/90 backdrop-blur text-white px-3 py-1.5 rounded-xl border border-white/10 shadow-lg">
                            <span class="text-[10px] text-slate-400 block font-medium">Tarif Sewa</span>
                            <span class="text-sm font-extrabold text-emerald-400">{{ $field->formatted_price }}</span>
                            <span class="text-[10px] text-slate-400">/ jam</span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900 group-hover:text-emerald-600 transition-colors">
                                {{ $field->name }}
                            </h3>
                            <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                                {{ $field->description ?: 'Lapangan futsal berstandar dengan penerangan LED terang, jaring keliling kokoh, dan sirkulasi udara yang nyaman.' }}
                            </p>
                        </div>

                        <!-- Card Footer Features & CTA -->
                        <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-3 text-slate-400 text-xs">
                                <span title="Bola & Rompi Tersedia"><i class="fa-solid fa-shirt"></i></span>
                                <span title="Penerangan LED High Bay"><i class="fa-solid fa-lightbulb"></i></span>
                                <span title="Papan Skor Digital"><i class="fa-solid fa-stopwatch"></i></span>
                            </div>

                            @if($field->isAvailable())
                                <a href="{{ route('booking.create', $field->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-600/20 transition-all transform active:scale-95">
                                    <span>Pesan Jam</span>
                                    <i class="fa-solid fa-calendar-check text-[11px]"></i>
                                </a>
                            @else
                                <button disabled class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 text-slate-400 text-xs font-bold rounded-xl cursor-not-allowed">
                                    <span>Sedang Diperbaiki</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

