@extends('layouts.app')

@section('title', 'Detail Reservasi - ' . $booking->booking_code . ' - FutsalHub')

@push('styles')
<style>
    @media print {
        @page {
            size: A4 portrait;
            margin: 10mm;
        }
        body {
            background-color: #ffffff !important;
            color: #0f172a !important;
            padding: 0 !important;
            margin: 0 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .print-receipt-card {
            border: 1px solid #cbd5e1 !important;
            box-shadow: none !important;
            border-radius: 16px !important;
            overflow: hidden !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 auto !important;
        }
        /* Ensure dark invoice header retains styling */
        .receipt-header {
            background-color: #0f172a !important;
            color: #ffffff !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>
@endpush

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 print:p-0 print:max-w-none">
    <!-- Top Bar Navigation & Print Action -->
    <div class="flex items-center justify-between mb-6 print:hidden">
        <a href="{{ route('booking.my') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-emerald-600 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Riwayat
        </a>

        <div class="flex items-center gap-3">
            @if($booking->status === 'pending' && (!$booking->payment || $booking->payment->status === 'rejected'))
                <a href="{{ route('payment.checkout', $booking->id) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-xl shadow-xs transition-colors">
                    <i class="fa-solid fa-credit-card"></i> Selesaikan Pembayaran
                </a>
            @endif

            <button type="button" onclick="window.print()" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl shadow-xs transition-colors cursor-pointer">
                <i class="fa-solid fa-print"></i> Cetak Struk / Invoice
            </button>
        </div>
    </div>

    <!-- Official Invoice Receipt Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/50 overflow-hidden print-receipt-card">
        <!-- Invoice Header Banner -->
        <div class="receipt-header bg-slate-900 text-white p-6 sm:p-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo2.png') }}" alt="Logo FutsalHub" class="w-12 h-12 object-contain">
                <div>
                    <h2 class="text-xl font-black tracking-tight text-white">Futsal<span class="text-emerald-400">Hub</span> Arena</h2>
                    <p class="text-xs text-slate-400">Bukti Reservasi & Kuitansi Pembayaran Resmi</p>
                </div>
            </div>

            <div class="sm:text-right">
                <span class="text-[10px] uppercase font-black tracking-widest text-emerald-400 block">Kode Reservasi</span>
                <span class="text-xl font-mono font-black text-white">{{ $booking->booking_code }}</span>
            </div>
        </div>

        <!-- Meta Info Grid -->
        <div class="p-6 sm:p-8 border-b border-slate-100 grid grid-cols-1 sm:grid-cols-3 gap-6 text-xs bg-white">
            <div>
                <span class="text-slate-400 uppercase font-bold tracking-wider text-[10px] block mb-1">Informasi Pemesan</span>
                <p class="font-bold text-slate-900 text-sm">{{ $booking->customer_name }}</p>
                <p class="text-slate-500">{{ $booking->user?->email ?? '-' }}</p>
                <p class="text-slate-500"><i class="fa-brands fa-whatsapp text-emerald-600 mr-1 print:hidden"></i> {{ $booking->customer_phone }}</p>
            </div>

            <div>
                <span class="text-slate-400 uppercase font-bold tracking-wider text-[10px] block mb-1">Jadwal Pertandingan</span>
                <p class="font-bold text-slate-900 text-sm">{{ $booking->booking_date->format('l, d F Y') }}</p>
                <p class="text-slate-600 font-semibold">{{ $booking->field->name }}</p>
                <p class="text-emerald-700 font-bold">Lantai: {{ $booking->field->type_label }}</p>
            </div>

            <div>
                <span class="text-slate-400 uppercase font-bold tracking-wider text-[10px] block mb-1">Status Reservasi</span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $booking->status_meta['badge'] }} mb-2">
                    {{ $booking->status_meta['label'] }}
                </span>
                <p class="text-slate-400 text-[10px]">Dipesan pada: {{ $booking->created_at->format('d/m/Y H:i') }} WIB</p>
            </div>
        </div>

        <!-- Breakdown Table -->
        <div class="p-6 sm:p-8 bg-white">
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-400 mb-4">Rincian Slot Jam Operasional</h3>
            
            <div class="border border-slate-200 rounded-2xl overflow-hidden mb-6">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 font-bold text-slate-600 border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Slot Jam Bermain</th>
                            <th class="px-4 py-3">Durasi</th>
                            <th class="px-4 py-3 text-right">Tarif</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($booking->bookingDetails as $index => $detail)
                            <tr>
                                <td class="px-4 py-3 text-slate-400 font-bold">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-bold text-slate-800">
                                    <i class="fa-regular fa-clock text-emerald-600 mr-1.5 print:hidden"></i>
                                    {{ $detail->timeSlot->time_range }} WIB
                                </td>
                                <td class="px-4 py-3 text-slate-600">60 Menit</td>
                                <td class="px-4 py-3 text-right font-bold text-slate-900">{{ $detail->formatted_price }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-slate-50/80 font-bold border-t border-slate-200">
                        <tr>
                            <td colspan="3" class="px-4 py-3.5 text-right text-slate-700">Total Biaya Reservasi:</td>
                            <td class="px-4 py-3.5 text-right text-base text-emerald-600 font-black">{{ $booking->formatted_total }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Payment Info Box -->
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 text-xs">
                <div>
                    <span class="text-slate-400 font-bold uppercase text-[10px] block mb-1">Status Pembayaran</span>
                    @if($booking->payment)
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-slate-900 text-sm">{{ $booking->payment->method_label }}</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $booking->payment->status_meta['badge'] }}">
                                {{ $booking->payment->status_meta['label'] }}
                            </span>
                        </div>
                        @if($booking->payment->paid_at)
                            <p class="text-slate-500 mt-1 text-[11px]">Waktu Bayar: {{ $booking->payment->paid_at->format('d/m/Y H:i') }} WIB</p>
                        @endif
                    @else
                        <span class="text-rose-600 font-bold">Belum Melakukan Pembayaran</span>
                    @endif
                </div>

                <div class="text-right">
                    <p class="text-slate-500 text-[11px] font-medium">Tunjukkan lembar ini atau kode booking ke resepsionis lapangan saat check-in.</p>
                </div>
            </div>
        </div>

        <!-- Footer Notice (hidden on print as requested, only receipt content is shown) -->
        <div class="bg-slate-100 px-6 sm:px-8 py-4 border-t border-slate-200 text-center text-[11px] text-slate-500 print:hidden">
            Terima kasih telah memilih FutsalHub. Patuhi protokol keselamatan & bawa sepatu futsal yang sesuai dengan jenis lantai.
        </div>
    </div>
</div>
@endsection

