@extends('layouts.admin')

@section('title', 'Laporan Rekapitulasi Reservasi - FutsalHub')
@section('page_title', 'Rekap Laporan Reservasi & Pendapatan')

@section('content')
<div class="space-y-6">
    <!-- Filter Box Card -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-extrabold text-slate-900">Filter Laporan Transaksi</h3>
                <p class="text-xs text-slate-500 mt-0.5">Tentukan rentang tanggal dan kriteria untuk menyaring laporan penyewaan</p>
            </div>

            <!-- Print Friendly Button -->
            <a href="{{ route('admin.reports.print', request()->query()) }}" target="_blank"
                class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl shadow-xs transition-colors">
                <i class="fa-solid fa-print text-emerald-400"></i> Cetak Dokumen Laporan
            </a>
        </div>

        <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
            <div>
                <label for="start_date" class="block text-[11px] font-bold text-slate-600 mb-1">Dari Tanggal:</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 font-semibold text-slate-800">
            </div>

            <div>
                <label for="end_date" class="block text-[11px] font-bold text-slate-600 mb-1">Sampai Tanggal:</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 font-semibold text-slate-800">
            </div>

            <div>
                <label for="field_id" class="block text-[11px] font-bold text-slate-600 mb-1">Pilih Lapangan:</label>
                <select id="field_id" name="field_id"
                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-700">
                    <option value="">Semua Lapangan</option>
                    @foreach($fields as $f)
                        <option value="{{ $f->id }}" {{ (string)$fieldId === (string)$f->id ? 'selected' : '' }}>{{ $f->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="block text-[11px] font-bold text-slate-600 mb-1">Status Reservasi:</label>
                <select id="status" name="status"
                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-700">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ $status === 'confirmed' ? 'selected' : '' }}>Confirmed (Siap Main)</option>
                    <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed (Lunas)</option>
                    <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled (Batal)</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="w-full py-2 px-3 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition-colors">
                    <i class="fa-solid fa-magnifying-glass mr-1"></i> Terapkan
                </button>
                <a href="{{ route('admin.reports.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-xl transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Stats Ribbon -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Pendapatan Periode Ini</span>
            <span class="text-xl font-black text-emerald-600 mt-1 block">Rp {{ number_format($totalIncome, 0, ',', '.') }}</span>
            <span class="text-[11px] text-slate-400 mt-0.5 block">Akumulasi transaksi confirmed & completed</span>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Total Transaksi</span>
            <span class="text-xl font-black text-slate-900 mt-1 block">{{ $totalTransactions }} Pesanan</span>
            <span class="text-[11px] text-slate-400 mt-0.5 block">Dalam rentang tanggal terpilih</span>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Selesai / Lunas</span>
            <span class="text-xl font-black text-blue-600 mt-1 block">{{ $completedCount + $confirmedCount }} Sukses</span>
            <span class="text-[11px] text-slate-400 mt-0.5 block">{{ $completedCount }} lunas kasir • {{ $confirmedCount }} valid QRIS</span>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Pending / Batal</span>
            <span class="text-xl font-black text-slate-700 mt-1 block">{{ $pendingCount }} / {{ $cancelledCount }}</span>
            <span class="text-[11px] text-slate-400 mt-0.5 block">{{ $pendingCount }} menunggu • {{ $cancelledCount }} dibatalkan</span>
        </div>
    </div>

    <!-- Report Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-extrabold text-slate-900">Rincian Data Reservasi</h3>
                <p class="text-xs text-slate-500 mt-0.5">Periode: <strong class="text-slate-800">{{ date('d M Y', strtotime($startDate)) }}</strong> s/d <strong class="text-slate-800">{{ date('d M Y', strtotime($endDate)) }}</strong></p>
            </div>
        </div>

        @if($bookings->isEmpty())
            <div class="p-12 text-center text-slate-400 text-xs">
                Tidak ada riwayat reservasi pada periode tanggal ini.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-[11px] uppercase font-bold text-slate-500 tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-3.5">No</th>
                            <th class="px-5 py-3.5">Kode Booking</th>
                            <th class="px-5 py-3.5">Customer</th>
                            <th class="px-5 py-3.5">Lapangan</th>
                            <th class="px-5 py-3.5">Tgl Sewa</th>
                            <th class="px-5 py-3.5">Jam Bermain</th>
                            <th class="px-5 py-3.5">Metode Bayar</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-right">Total Biaya</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($bookings as $index => $b)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-5 py-3.5 text-slate-400 font-bold">{{ $index + 1 }}</td>
                                <td class="px-5 py-3.5 whitespace-nowrap font-mono font-bold text-slate-900">
                                    {{ $b->booking_code }}
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="font-bold text-slate-900 block">{{ $b->user->name }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $b->user->phone }}</span>
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="font-bold text-slate-800">{{ $b->field->name }}</span>
                                    <span class="text-[10px] text-slate-400 block">{{ $b->field->type_label }}</span>
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap font-semibold text-slate-800">
                                    {{ $b->booking_date->format('d/m/Y') }}
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="text-[11px] text-slate-700">
                                        @foreach($b->bookingDetails as $d)
                                            <span class="inline-block bg-slate-100 px-1 py-0.5 rounded text-[10px] mr-1">
                                                {{ substr($d->timeSlot->start_time, 0, 5) }}
                                            </span>
                                        @endforeach
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="uppercase font-bold text-[10px] px-2 py-0.5 rounded {{ $b->payment?->payment_method === 'qris' ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-teal-50 text-teal-700 border border-teal-200' }}">
                                        {{ $b->payment?->payment_method ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $b->status_meta['badge'] }}">
                                        {{ $b->status_meta['label'] }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap text-right font-black text-slate-900">
                                    {{ $b->formatted_total }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-slate-50/80 font-bold border-t border-slate-200">
                        <tr>
                            <td colspan="8" class="px-5 py-3.5 text-right text-slate-700 uppercase tracking-wider text-[11px]">Total Pendapatan Terverifikasi:</td>
                            <td class="px-5 py-3.5 text-right text-sm text-emerald-600 font-black">Rp {{ number_format($totalIncome, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

