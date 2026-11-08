@extends('layouts.admin')

@section('title', 'Dashboard Administrator - FutsalHub')
@section('page_title', 'Ringkasan Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Top Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Card 1: Total Revenue -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Total Pendapatan</span>
                <span class="text-xl sm:text-2xl font-black text-slate-900 mt-1 block">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                <span class="text-[11px] text-emerald-600 font-semibold mt-1 inline-flex items-center gap-1">
                    <i class="fa-solid fa-arrow-trend-up"></i> Dari booking lunas & confirmed
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-wallet"></i>
            </div>
        </div>

        <!-- Card 2: Total Bookings -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Total Transaksi</span>
                <span class="text-xl sm:text-2xl font-black text-slate-900 mt-1 block">{{ $totalBookings }} Pesanan</span>
                <span class="text-[11px] text-slate-500 font-medium mt-1 inline-block">
                    {{ $confirmedBookings + $completedBookings }} terkonfirmasi • {{ $pendingBookings }} pending
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-receipt"></i>
            </div>
        </div>

        <!-- Card 3: Pending Bookings / Payments -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Booking Menunggu</span>
                <span class="text-xl sm:text-2xl font-black text-amber-600 mt-1 block">{{ $pendingBookings }} Menunggu</span>
                <a href="{{ route('admin.payments.index', ['status' => 'pending']) }}" class="text-[11px] text-amber-700 font-bold hover:underline mt-1 inline-flex items-center gap-1">
                    <i class="fa-solid fa-arrow-right text-[10px]"></i> Verifikasi sekarang
                </a>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
        </div>

        <!-- Card 4: Master Fields & Customers -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Total Fasilitas</span>
                <span class="text-xl sm:text-2xl font-black text-slate-900 mt-1 block">{{ $totalFields }} Lapangan</span>
                <span class="text-[11px] text-slate-500 font-medium mt-1 inline-block">
                    {{ $totalCustomers }} Member customer terdaftar
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-futbol"></i>
            </div>
        </div>
    </div>

    <!-- Quick Action Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-emerald-950 rounded-2xl p-6 text-white flex flex-col md:flex-row items-start md:items-center justify-between gap-4 shadow-md">
        <div>
            <h3 class="text-base font-extrabold flex items-center gap-2">
                <i class="fa-solid fa-bolt text-amber-400"></i> Pintasan Tindakan Cepat Admin
            </h3>
            <p class="text-xs text-slate-300 mt-1">Akses cepat menu pengelolaan lapangan, verifikasi transfer QRIS, dan rekapitulasi laporan transaksi.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.fields.create') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                <i class="fa-solid fa-plus text-[11px]"></i> Tambah Lapangan
            </a>
            <a href="{{ route('admin.payments.index') }}" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white text-xs font-bold rounded-xl border border-white/20 transition-colors flex items-center gap-1.5">
                <i class="fa-solid fa-money-check-dollar text-[11px]"></i> Cek Transaksi Masuk
            </a>
            <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white text-xs font-bold rounded-xl border border-white/20 transition-colors flex items-center gap-1.5">
                <i class="fa-solid fa-print text-[11px]"></i> Rekap Laporan
            </a>
        </div>
    </div>

    <!-- Recent Bookings Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-extrabold text-slate-900">Reservasi Terbaru</h3>
                <p class="text-xs text-slate-500 mt-0.5">Daftar transaksi sewa lapangan yang baru masuk</p>
            </div>

            <a href="{{ route('admin.payments.index') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                Lihat Semua <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        @if($recentBookings->isEmpty())
            <div class="p-10 text-center text-slate-400 text-xs">
                Belum ada transaksi sewa yang tercatat dalam sistem.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-[11px] uppercase font-bold text-slate-500 tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-3">Kode Booking</th>
                            <th class="px-5 py-3">Customer</th>
                            <th class="px-5 py-3">Lapangan</th>
                            <th class="px-5 py-3">Tgl Sewa</th>
                            <th class="px-5 py-3">Total</th>
                            <th class="px-5 py-3">Metode</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($recentBookings as $booking)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-5 py-3.5 whitespace-nowrap font-mono font-bold text-slate-900">
                                    {{ $booking->booking_code }}
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="font-bold text-slate-900 block">{{ $booking->customer_name }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $booking->customer_phone }}</span>
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    {{ $booking->field->name }}
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap font-semibold text-slate-800">
                                    {{ $booking->booking_date->format('d/m/Y') }}
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap font-bold text-slate-900">
                                    {{ $booking->formatted_total }}
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    @if($booking->payment)
                                        <span class="uppercase font-bold text-[10px] px-2 py-0.5 rounded bg-slate-100 text-slate-700">
                                            {{ $booking->payment->payment_method }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 italic text-[10px]">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $booking->status_meta['badge'] }}">
                                        {{ $booking->status_meta['label'] }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap text-right">
                                    <a href="{{ route('admin.payments.index', ['search' => $booking->booking_code]) }}" class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px] rounded-lg transition-colors">
                                        Periksa <i class="fa-solid fa-chevron-right text-[9px]"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

