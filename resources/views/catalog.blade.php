@extends('layouts.app')

@section('title', 'Katalog Lapangan Futsal - FutsalHub')
@section('page_title', 'Katalog Lapangan Futsal')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-2">
                <a href="{{ route('home') }}" class="hover:text-emerald-600 font-semibold transition-colors">Beranda</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                <span class="text-slate-800 font-bold">Katalog Lapangan</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Katalog Lapangan</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Pilih lapangan sesuai jenis lantai dan kenyamanan tim Anda</p>
        </div>

        <!-- Search Bar -->
        <form method="GET" action="{{ route('catalog') }}" class="flex items-center gap-2 w-full lg:w-96">
            @if(!empty($type))
                <input type="hidden" name="type" value="{{ $type }}">
            @endif
            <div class="relative w-full">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ $search ?? '' }}" 
                    placeholder="Cari nama lapangan atau lantai..." 
                    class="w-full pl-9 pr-8 py-2.5 text-xs bg-white border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-800 font-semibold shadow-xs">
                @if(!empty($search))
                    <a href="{{ route('catalog', array_filter(['type' => $type])) }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs p-1" title="Hapus pencarian">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>
            <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-md shadow-emerald-600/30 transition-all flex items-center gap-1.5 cursor-pointer shrink-0">
                <i class="fa-solid fa-magnifying-glass text-[11px]"></i>
                <span>Cari</span>
            </button>
        </form>
    </div>

    <!-- Filter Pills Section -->
    <div class="flex flex-wrap items-center gap-2 pb-6 border-b border-slate-200/80 mb-8">
        <a href="{{ route('catalog', array_filter(['search' => $search])) }}" 
            class="px-4 py-2 text-xs font-bold rounded-xl transition-all {{ empty($type) ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-600/30' : 'bg-white text-slate-600 border border-slate-200 hover:text-slate-900 hover:bg-slate-100' }}">
            Semua Lapangan ({{ $stats['total_fields'] }})
        </a>
        <a href="{{ route('catalog', array_filter(['type' => 'vinyl', 'search' => $search])) }}" 
            class="px-4 py-2 text-xs font-bold rounded-xl transition-all {{ $type === 'vinyl' ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-600/30' : 'bg-white text-slate-600 border border-slate-200 hover:text-slate-900 hover:bg-slate-100' }}">
            Vinyl ({{ $stats['types_count']['vinyl'] }})
        </a>
        <a href="{{ route('catalog', array_filter(['type' => 'sintetis', 'search' => $search])) }}" 
            class="px-4 py-2 text-xs font-bold rounded-xl transition-all {{ $type === 'sintetis' ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-600/30' : 'bg-white text-slate-600 border border-slate-200 hover:text-slate-900 hover:bg-slate-100' }}">
            Sintetis ({{ $stats['types_count']['sintetis'] }})
        </a>
        <a href="{{ route('catalog', array_filter(['type' => 'interlock', 'search' => $search])) }}" 
            class="px-4 py-2 text-xs font-bold rounded-xl transition-all {{ $type === 'interlock' ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-600/30' : 'bg-white text-slate-600 border border-slate-200 hover:text-slate-900 hover:bg-slate-100' }}">
            Interlock ({{ $stats['types_count']['interlock'] }})
        </a>

        @if(!empty($search) || !empty($type))
            <a href="{{ route('catalog') }}" class="ml-auto text-xs font-bold text-rose-600 hover:text-rose-700 hover:underline flex items-center gap-1">
                <i class="fa-solid fa-arrows-rotate text-[10px]"></i> Reset Filter
            </a>
        @endif
    </div>

    <!-- Active Search/Filter Notice -->
    @if(!empty($search))
        <div class="mb-6 flex items-center justify-between p-3.5 bg-emerald-50/60 border border-emerald-200 rounded-xl text-xs text-emerald-900">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-emerald-600"></i>
                <span>Menampilkan hasil pencarian untuk: <strong class="text-emerald-950 font-bold">"{{ $search }}"</strong> ({{ $fields->count() }} lapangan ditemukan)</span>
            </div>
            <a href="{{ route('catalog', array_filter(['type' => $type])) }}" class="text-emerald-700 hover:text-emerald-900 font-bold hover:underline">Hapus Kata Kunci &times;</a>
        </div>
    @endif

    <!-- Court Cards Grid -->
    @if($fields->isEmpty())
        <div class="bg-white rounded-3xl border border-dashed border-slate-300 p-12 text-center my-6">
            <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4 text-2xl">
                <i class="fa-solid fa-futbol"></i>
            </div>
            <h3 class="text-base font-bold text-slate-800">Tidak ada lapangan yang sesuai</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Kami tidak menemukan lapangan yang sesuai dengan kata kunci atau filter pilihan Anda.</p>
            <a href="{{ route('catalog') }}" class="mt-5 inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition-colors">
                <i class="fa-solid fa-rotate-left text-[11px]"></i> Tampilkan Semua Lapangan
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($fields as $field)
                <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 flex flex-col group">
                    <!-- Image Area -->
                    <div class="relative h-56 bg-slate-900 overflow-hidden">
                        <img src="{{ $field->image ? asset('storage/' . $field->image) : asset('images/katalog.png') }}" 
                            alt="{{ $field->name }}" 
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                        <!-- Floor Type Badge -->
                        <div class="absolute top-3 left-3">
                            @if($field->type === 'vinyl')
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-blue-600 text-white shadow-md flex items-center gap-1">
                                    <i class="fa-solid fa-certificate text-[9px]"></i> Vinyl FIFA
                                </span>
                            @elseif($field->type === 'sintetis')
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-600 text-white shadow-md flex items-center gap-1">
                                    <i class="fa-solid fa-leaf text-[9px]"></i> Sintetis
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-purple-600 text-white shadow-md flex items-center gap-1">
                                    <i class="fa-solid fa-shapes text-[9px]"></i> Interlock
                                </span>
                            @endif
                        </div>

                        <!-- Status Badge -->
                        <div class="absolute top-3 right-3">
                            @if($field->isAvailable())
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-white/95 text-emerald-700 backdrop-blur-xs shadow-md">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Tersedia
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-500 text-white shadow-md">
                                    <i class="fa-solid fa-wrench text-[9px]"></i> Perbaikan
                                </span>
                            @endif
                        </div>

                        <!-- Price Tag Overlay -->
                        <div class="absolute bottom-3 right-3 bg-slate-950/85 backdrop-blur-sm text-white px-3 py-1.5 rounded-xl border border-white/10 shadow-lg">
                            <span class="text-[9px] text-slate-300 block uppercase font-bold tracking-wider">Tarif Sewa</span>
                            <span class="text-sm font-black text-emerald-400">{{ $field->formatted_price }} <span class="text-[10px] text-slate-400 font-normal">/jam</span></span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="text-base font-black text-slate-900 group-hover:text-emerald-600 transition-colors">
                                {{ $field->name }}
                            </h3>
                            <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                                {{ $field->description }}
                            </p>
                        </div>

                        <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between gap-2">
                            <!-- Quick Facility Icons -->
                            <div class="flex items-center gap-2 text-slate-400 text-xs">
                                <span title="Rompi Tim Gratis"><i class="fa-solid fa-shirt hover:text-emerald-600 transition-colors"></i></span>
                                <span title="Bola Futsal Gratis"><i class="fa-regular fa-futbol hover:text-emerald-600 transition-colors"></i></span>
                                <span title="Shower Air Hangat"><i class="fa-solid fa-shower hover:text-emerald-600 transition-colors"></i></span>
                            </div>

                            <div class="flex items-center gap-1.5">
                                <!-- Tombol Lihat Detail -->
                                <button type="button" 
                                    onclick="openFieldDetailModal('{{ $field->id }}')" 
                                    class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-colors cursor-pointer flex items-center gap-1">
                                    <i class="fa-regular fa-eye text-[11px]"></i>
                                    <span>Detail</span>
                                </button>

                                <!-- Tombol Pesan Jam -->
                                @if($field->isAvailable())
                                    <a href="{{ route('booking.create', $field->id) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition-colors">
                                        <span>Pesan Jam</span>
                                        <i class="fa-solid fa-calendar-check text-[11px]"></i>
                                    </a>
                                @else
                                    <button disabled class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-100 text-slate-400 font-bold text-xs rounded-xl cursor-not-allowed">
                                        <span>Tutup</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hidden Modal Container for this field -->
                <div id="fieldModal-{{ $field->id }}" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs hidden flex items-center justify-center p-4">
                    <div class="bg-white rounded-3xl max-w-xl w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-slate-200 animate-in fade-in zoom-in-95 duration-150">
                        <div class="relative h-56 bg-slate-900 overflow-hidden rounded-t-3xl">
                            <img src="{{ $field->image ? asset('storage/' . $field->image) : asset('images/katalog.png') }}" class="w-full h-full object-cover">
                            <button type="button" onclick="closeFieldDetailModal('{{ $field->id }}')" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-900/70 hover:bg-slate-900 text-white flex items-center justify-center transition-colors cursor-pointer">
                                <i class="fa-solid fa-xmark text-sm"></i>
                            </button>
                            <div class="absolute bottom-4 left-4">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-600 text-white shadow-md">
                                    {{ $field->type_label }}
                                </span>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="flex items-start justify-between gap-4 pb-4 border-b border-slate-100">
                                <div>
                                    <h3 class="text-xl font-black text-slate-900">{{ $field->name }}</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Spesifikasi Lapangan Standar Nasional</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs text-slate-400 block font-medium">Tarif Sewa</span>
                                    <span class="text-lg font-black text-emerald-600">{{ $field->formatted_price }} <span class="text-xs font-normal text-slate-500">/jam</span></span>
                                </div>
                            </div>

                            <div class="py-4">
                                <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-2">Deskripsi Lapangan</h4>
                                <p class="text-xs text-slate-600 leading-relaxed">{{ $field->description }}</p>
                            </div>

                            <div class="py-4 border-t border-slate-100">
                                <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-3">Fasilitas yang Termasuk:</h4>
                                <div class="grid grid-cols-2 gap-2.5 text-xs text-slate-700">
                                    <div class="flex items-center gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                        <i class="fa-solid fa-shirt text-emerald-600"></i>
                                        <span>Rompi Tim 2 Warna</span>
                                    </div>
                                    <div class="flex items-center gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                        <i class="fa-regular fa-futbol text-emerald-600"></i>
                                        <span>Bola Futsal Standar FIFA</span>
                                    </div>
                                    <div class="flex items-center gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                        <i class="fa-solid fa-lightbulb text-emerald-600"></i>
                                        <span>Lampu LED Sport Terang</span>
                                    </div>
                                    <div class="flex items-center gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                        <i class="fa-solid fa-shower text-emerald-600"></i>
                                        <span>Shower Air Hangat</span>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                                <button type="button" onclick="closeFieldDetailModal('{{ $field->id }}')" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-colors cursor-pointer">
                                    Tutup
                                </button>
                                @if($field->isAvailable())
                                    <a href="{{ route('booking.create', $field->id) }}" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-md shadow-emerald-600/30 transition-all flex items-center gap-1.5">
                                        <span>Pesan Jadwal Sekarang</span>
                                        <i class="fa-solid fa-arrow-right text-xs"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@push('scripts')
<script>
    function openFieldDetailModal(id) {
        const modal = document.getElementById(`fieldModal-${id}`);
        if (modal) modal.classList.remove('hidden');
    }

    function closeFieldDetailModal(id) {
        const modal = document.getElementById(`fieldModal-${id}`);
        if (modal) modal.classList.add('hidden');
    }

    // Close on backdrop click
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[id^="fieldModal-"]').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });
        });
    });
</script>
@endpush
@endsection

