@extends('layouts.app')

@section('title', 'Beranda - FutsalHub Arena')
@section('page_title', 'FutsalHub Customer Arena')

@section('content')
<!-- Hero Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 sm:pt-6">
    <div class="relative bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 text-white rounded-3xl overflow-hidden py-7 sm:py-8 px-6 sm:px-8 lg:px-12 shadow-xl border border-slate-800/80">
        <div class="absolute inset-0 bg-[radial-gradient(#10b981_1px,transparent_1px)] [background-size:24px_24px] opacity-10"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6 lg:gap-8">
            <!-- Konten Teks & Tombol di Samping Kiri -->
            <div class="flex-1 text-center md:text-left">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight leading-tight mb-2.5">
                    Futsal<span class="text-emerald-400">Hub</span>
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed mb-5 max-w-2xl">
                    Pusat reservasi lapangan futsal modern dengan pilihan lantai Vinyl standar FIFA, Rumput Sintetis lembut, dan Interlock polimer. Nikmati fasilitas arena lengkap dan kemudahan pemesanan online secara real-time!
                </p>

                <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                    <a href="{{ route('catalog') }}" class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-slate-950 font-extrabold text-xs rounded-xl shadow-lg shadow-emerald-500/25 transition-all transform active:scale-[0.99] flex items-center gap-2">
                        <i class="fa-solid fa-futbol text-xs"></i>
                        <span>Buka Katalog & Booking Lapangan</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                    <a href="#tentang-lapangan" class="px-4 py-2.5 bg-white/10 hover:bg-white/15 text-white font-bold text-xs rounded-xl border border-white/15 backdrop-blur-xs transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-emerald-400 text-xs"></i>
                        <span>Lihat Jenis Lapangan & Fasilitas</span>
                    </a>
                </div>
            </div>

        
            <div class="shrink-0 flex items-center justify-center md:mr-8 lg:mr-14">
                <img src="{{ asset('images/image.png') }}" alt="Ilustrasi Lapangan Futsal" class="w-44 h-44 sm:w-52 sm:h-52 md:w-56 md:h-56 lg:w-64 lg:h-64 object-contain filter drop-shadow-2xl select-none">
            </div>
        </div>
    </div>
</div>

<!-- Section 1: Tentang Lapangan (Jenis Lantai & Spesifikasi) -->
<div id="tentang-lapangan" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
    <div class="text-center max-w-2xl mx-auto mb-12">
        <span class="px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800">
            Karakteristik Arena
        </span>
        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mt-3">
            Pilihan Jenis Lantai Lapangan Futsal
        </h2>
        <p class="text-xs sm:text-sm text-slate-500 mt-2 leading-relaxed">
            FutsalHub menyediakan 3 tipe permukaan lapangan berstandar internasional yang disesuaikan dengan gaya permainan dan kenyamanan tim Anda.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Card 1: Vinyl FIFA -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-7 shadow-xs hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 flex flex-col justify-between group">
            <div>
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl mb-5 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-certificate"></i>
                </div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-blue-100 text-blue-700">Tipe Vinyl</span>
                    <span class="text-[11px] text-slate-400 font-semibold">Standar FIFA</span>
                </div>
                <h3 class="text-lg font-black text-slate-900 mb-2">Lantai Vinyl Kompetisi</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Lantai vinyl impor berkualitas tinggi dengan lapisan peredam benturan elastis. Memberikan bantalan terbaik untuk melindungi lutut dan persendian pemain saat meluncur atau melompat.
                </p>

                <ul class="mt-5 space-y-2 text-xs text-slate-600 border-t border-slate-100 pt-4">
                    <li class="flex items-center gap-2">
                        <i class="fa-solid fa-check text-emerald-500 text-[11px]"></i>
                        <span>Anti-slip & daya cengkeram optimal</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fa-solid fa-check text-emerald-500 text-[11px]"></i>
                        <span>Pantulan dan laju bola sangat presisi</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fa-solid fa-check text-emerald-500 text-[11px]"></i>
                        <span>Ideal untuk turnamen resmi & laga intens</span>
                    </li>
                </ul>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100">
                <a href="{{ route('catalog', ['type' => 'vinyl']) }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 inline-flex items-center gap-1.5 group-hover:gap-2 transition-all">
                    <span>Lihat Lapangan Vinyl</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- Card 2: Rumput Sintetis -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-7 shadow-xs hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 flex flex-col justify-between group">
            <div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl mb-5 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-leaf"></i>
                </div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-emerald-100 text-emerald-700">Tipe Sintetis</span>
                    <span class="text-[11px] text-slate-400 font-semibold">Tebal 50mm</span>
                </div>
                <h3 class="text-lg font-black text-slate-900 mb-2">Rumput Sintetis Premium</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Rumput sintetis monofilamen lembut dengan taburan butiran karet tebal (*rubber infill*) yang merata. Menghadirkan sensasi bermain seperti di lapangan stadion sepak bola asli.
                </p>

                <ul class="mt-5 space-y-2 text-xs text-slate-600 border-t border-slate-100 pt-4">
                    <li class="flex items-center gap-2">
                        <i class="fa-solid fa-check text-emerald-500 text-[11px]"></i>
                        <span>Lembut dan tidak kasar saat terjatuh</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fa-solid fa-check text-emerald-500 text-[11px]"></i>
                        <span>Grip sol sepatu futsal sangat kuat</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fa-solid fa-check text-emerald-500 text-[11px]"></i>
                        <span>Favorit untuk fun match & sparring santai</span>
                    </li>
                </ul>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100">
                <a href="{{ route('catalog', ['type' => 'sintetis']) }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 inline-flex items-center gap-1.5 group-hover:gap-2 transition-all">
                    <span>Lihat Lapangan Sintetis</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- Card 3: Interlock Modular -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-7 shadow-xs hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 flex flex-col justify-between group">
            <div>
                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl mb-5 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-shapes"></i>
                </div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-purple-100 text-purple-700">Tipe Interlock</span>
                    <span class="text-[11px] text-slate-400 font-semibold">Modular Polymer</span>
                </div>
                <h3 class="text-lg font-black text-slate-900 mb-2">Interlock Polimer Kuat</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Lantai modular interlocking berbahan poly-propylene tahan benturan keras. Dilengkapi pori ventilasi sirkulasi udara yang menjaga temperatur lapangan tetap sejuk.
                </p>

                <ul class="mt-5 space-y-2 text-xs text-slate-600 border-t border-slate-100 pt-4">
                    <li class="flex items-center gap-2">
                        <i class="fa-solid fa-check text-emerald-500 text-[11px]"></i>
                        <span>Permukaan anti-slip bertekstur stabil</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fa-solid fa-check text-emerald-500 text-[11px]"></i>
                        <span>Tahan aus untuk latihan rutin berkelanjutan</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fa-solid fa-check text-emerald-500 text-[11px]"></i>
                        <span>Laju bola lincah dan cepat dikontrol</span>
                    </li>
                </ul>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100">
                <a href="{{ route('catalog', ['type' => 'interlock']) }}" class="text-xs font-bold text-purple-600 hover:text-purple-700 inline-flex items-center gap-1.5 group-hover:gap-2 transition-all">
                    <span>Lihat Lapangan Interlock</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Section 2: Fasilitas Lengkap Arena -->
<div class="bg-white border-y border-slate-200/80 py-14 sm:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800">
                Fasilitas FutsalHub
            </span>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mt-3">
                Kenyamanan Penuh untuk Setiap Pemain
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-2 leading-relaxed">
                Kami menyediakan sarana dan prasarana penunjang yang lengkap agar pengalaman bertanding tim Anda selalu aman, bersih, dan berkesan.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Facility 1 -->
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50/30 transition-all duration-200 group">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-shower"></i>
                </div>
                <h4 class="text-sm font-extrabold text-slate-900 mb-1">Shower Air Hangat</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Kamar mandi bersih dengan fasilitas shower air hangat untuk menyegarkan badan setelah berolahraga.
                </p>
            </div>

            <!-- Facility 2 -->
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50/30 transition-all duration-200 group">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-lightbulb"></i>
                </div>
                <h4 class="text-sm font-extrabold text-slate-900 mb-1">Lampu LED Sport High-Bay</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Penerangan merata tanpa sudut gelap dan bebas silau, mendukung permainan malam hari dengan jelas.
                </p>
            </div>

            <!-- Facility 3 -->
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50/30 transition-all duration-200 group">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-shirt"></i>
                </div>
                <h4 class="text-sm font-extrabold text-slate-900 mb-1">Rompi & Bola Gratis</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Setiap pemesanan lapangan mendapatkan peminjaman bola standar FIFA dan 2 set rompi bertanding gratis.
                </p>
            </div>

            <!-- Facility 4 -->
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50/30 transition-all duration-200 group">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-stopwatch-20"></i>
                </div>
                <h4 class="text-sm font-extrabold text-slate-900 mb-1">Papan Skor Digital</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Scoreboard elektronik di sisi lapangan untuk menghitung poin gol dan waktu tanding secara profesional.
                </p>
            </div>

            <!-- Facility 5 -->
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50/30 transition-all duration-200 group">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-mug-hot"></i>
                </div>
                <h4 class="text-sm font-extrabold text-slate-900 mb-1">Kantin & Kafe Rest Area</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Menyediakan berbagai minuman isotonik dingin, air mineral, camilan, dan kopi untuk waktu rehat tim.
                </p>
            </div>

            <!-- Facility 6 -->
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50/30 transition-all duration-200 group">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-wifi"></i>
                </div>
                <h4 class="text-sm font-extrabold text-slate-900 mb-1">Free WiFi & Colokan</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Koneksi internet cepat dan colokan listrik di area tunggu bagi pengunjung serta penonton pertandingan.
                </p>
            </div>

            <!-- Facility 7 -->
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50/30 transition-all duration-200 group">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-square-parking"></i>
                </div>
                <h4 class="text-sm font-extrabold text-slate-900 mb-1">Parkir Luas & CCTV 24 Jam</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Area parkir motor dan mobil yang luas dengan pengawasan petugas keamanan serta kamera CCTV 24 jam.
                </p>
            </div>

            <!-- Facility 8 -->
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50/30 transition-all duration-200 group">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-people-group"></i>
                </div>
                <h4 class="text-sm font-extrabold text-slate-900 mb-1">Tribun Penonton Nyaman</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Tempat duduk tribun bertingkat yang nyaman untuk suporter dan teman yang menonton jalannya pertandingan.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Section 3: Daftar Lapangan Futsal (Lihat Detail & Fasilitas) -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
        <div>
            <span class="px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800">
                Pilihan Lapangan
            </span>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mt-3">
                Daftar Lapangan Tersedia
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Pilih lapangan dan klik <strong>Lihat Detail & Fasilitas</strong> untuk melihat spesifikasi lengkap
            </p>
        </div>

        <a href="{{ route('catalog') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs rounded-xl shadow-xs transition-colors self-start sm:self-auto">
            <span>Buka Katalog Lengkap</span>
            <i class="fa-solid fa-arrow-right text-xs"></i>
        </a>
    </div>

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
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-blue-600 text-white shadow-md">
                                Vinyl FIFA
                            </span>
                        @elseif($field->type === 'sintetis')
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-600 text-white shadow-md">
                                Sintetis
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-purple-600 text-white shadow-md">
                                Interlock
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
                        <!-- Button Lihat Detail & Fasilitas -->
                        <button type="button" 
                            onclick="openHomeDetailModal('{{ $field->id }}')" 
                            class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-colors cursor-pointer flex items-center gap-1.5">
                            <i class="fa-regular fa-eye text-xs"></i>
                            <span>Detail & Fasilitas</span>
                        </button>

                        <!-- Button Pesan -->
                        @if($field->isAvailable())
                            <a href="{{ route('booking.create', $field->id) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition-colors">
                                <span>Pesan Jam</span>
                                <i class="fa-solid fa-calendar-check text-[11px]"></i>
                            </a>
                        @else
                            <button disabled class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-100 text-slate-400 font-bold text-xs rounded-xl cursor-not-allowed">
                                <span>Perbaikan</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Modal Detail & Fasilitas Lapangan untuk Home -->
            <div id="homeModal-{{ $field->id }}" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs hidden flex items-center justify-center p-4">
                <div class="bg-white rounded-3xl max-w-xl w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-slate-200 animate-in fade-in zoom-in-95 duration-150">
                    <div class="relative h-56 bg-slate-900 overflow-hidden rounded-t-3xl">
                        <img src="{{ $field->image ? asset('storage/' . $field->image) : asset('images/katalog.png') }}" class="w-full h-full object-cover">
                        <button type="button" onclick="closeHomeDetailModal('{{ $field->id }}')" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-900/70 hover:bg-slate-900 text-white flex items-center justify-center transition-colors cursor-pointer">
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
                                <p class="text-xs text-slate-500 mt-0.5">Spesifikasi Lapangan & Fasilitas</p>
                            </div>
                            <div class="text-right">
                                <span class="text-xs text-slate-400 block font-medium">Tarif Sewa</span>
                                <span class="text-lg font-black text-emerald-600">{{ $field->formatted_price }} <span class="text-xs font-normal text-slate-500">/jam</span></span>
                            </div>
                        </div>

                        <div class="py-4">
                            <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-2">Tentang Lapangan</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">{{ $field->description }}</p>
                        </div>

                        <div class="py-4 border-t border-slate-100">
                            <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-3">Fasilitas yang Termasuk:</h4>
                            <div class="grid grid-cols-2 gap-2.5 text-xs text-slate-700">
                                <div class="flex items-center gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                    <i class="fa-solid fa-shirt text-emerald-600"></i>
                                    <span>Rompi Tim 2 Warna (Gratis)</span>
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
                                <div class="flex items-center gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                    <i class="fa-solid fa-wifi text-emerald-600"></i>
                                    <span>Free WiFi High-Speed</span>
                                </div>
                                <div class="flex items-center gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                    <i class="fa-solid fa-stopwatch-20 text-emerald-600"></i>
                                    <span>Scoreboard Digital</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                            <button type="button" onclick="closeHomeDetailModal('{{ $field->id }}')" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-colors cursor-pointer">
                                Tutup
                            </button>
                            @if($field->isAvailable())
                                <a href="{{ route('booking.create', $field->id) }}" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-md shadow-emerald-600/30 transition-all flex items-center gap-1.5">
                                    <span>Pesan Jam Sekarang</span>
                                    <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Bottom CTA Card -->
    <div class="mt-14 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-3xl p-8 sm:p-10 text-white flex flex-col md:flex-row items-center justify-between gap-6 shadow-xl shadow-emerald-600/20">
        <div>
            <h3 class="text-xl sm:text-2xl font-black">Siap Menentukan Jadwal Bertanding?</h3>
            <p class="text-xs sm:text-sm text-emerald-100 mt-1 max-w-xl">
                Cek ketersediaan jam operasional secara real-time dan kunci slot bermain tim Anda tanpa khawatir bentrok jadwal.
            </p>
        </div>
        <a href="{{ route('catalog') }}" class="px-6 py-3.5 bg-white hover:bg-emerald-50 text-emerald-800 font-black text-xs sm:text-sm rounded-xl shadow-md transition-all shrink-0 flex items-center gap-2">
            <span>Buka Katalog & Booking</span>
            <i class="fa-solid fa-arrow-right text-xs"></i>
        </a>
    </div>
</div>

@push('scripts')
<script>
    function openHomeDetailModal(id) {
        const modal = document.getElementById(`homeModal-${id}`);
        if (modal) modal.classList.remove('hidden');
    }

    function closeHomeDetailModal(id) {
        const modal = document.getElementById(`homeModal-${id}`);
        if (modal) modal.classList.add('hidden');
    }

    // Close on backdrop click
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[id^="homeModal-"]').forEach(modal => {
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
