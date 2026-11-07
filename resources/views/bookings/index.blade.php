@extends('layouts.app')

@section('title', 'Riwayat Booking Saya - FutsalHub')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Riwayat Pemesanan Saya</h1>
            <p class="text-xs text-slate-500 mt-1">Daftar semua jadwal sewa lapangan futsal yang pernah Anda pesan</p>
        </div>

        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-600/20 transition-all">
            <i class="fa-solid fa-plus text-xs"></i> Pesan Lapangan Lagi
        </a>
    </div>

    <!-- Filter Tabs -->
    <div class="flex flex-wrap items-center gap-2 mb-6 bg-white p-1.5 rounded-2xl border border-slate-200 shadow-xs">
        <a href="{{ route('booking.my') }}" class="px-3.5 py-2 text-xs font-bold rounded-xl transition-all {{ empty($status) ? 'bg-slate-900 text-white' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
            Semua Pesanan
        </a>
        <a href="{{ route('booking.my', ['status' => 'pending']) }}" class="px-3.5 py-2 text-xs font-bold rounded-xl transition-all {{ $status === 'pending' ? 'bg-amber-500 text-white' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
            Menunggu Verifikasi
        </a>
        <a href="{{ route('booking.my', ['status' => 'confirmed']) }}" class="px-3.5 py-2 text-xs font-bold rounded-xl transition-all {{ in_array($status, ['confirmed', 'completed']) ? 'bg-emerald-600 text-white' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
            Terkonfirmasi
        </a>
        <a href="{{ route('booking.my', ['status' => 'cancelled']) }}" class="px-3.5 py-2 text-xs font-bold rounded-xl transition-all {{ $status === 'cancelled' ? 'bg-rose-600 text-white' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
            Dibatalkan
        </a>
    </div>

    <!-- Bookings List Table -->
    @if($bookings->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center shadow-xs">
            <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                <i class="fa-solid fa-receipt text-2xl"></i>
            </div>
            <h3 class="text-base font-bold text-slate-800">Belum ada riwayat booking</h3>
            <p class="text-xs text-slate-500 mt-1">Anda belum memiliki jadwal penyewaan lapangan futsal.</p>
            <a href="{{ route('home') }}" class="mt-4 inline-block px-4 py-2 bg-emerald-600 text-white text-xs font-bold rounded-xl shadow-sm">
                Lihat Katalog Lapangan Sekarang
            </a>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-[11px] uppercase font-bold text-slate-500 tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-3.5">Kode Transaksi</th>
                            <th class="px-5 py-3.5">Lapangan</th>
                            <th class="px-5 py-3.5">Jadwal Bermain</th>
                            <th class="px-5 py-3.5">Total Biaya</th>
                            <th class="px-5 py-3.5">Metode Bayar</th>
                            <th class="px-5 py-3.5">Status Booking</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($bookings as $booking)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <!-- Code -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="font-mono font-extrabold text-slate-900 text-xs block">{{ $booking->booking_code }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $booking->created_at->format('d M Y, H:i') }}</span>
                                </td>

                                <!-- Field -->
                                <td class="px-5 py-4">
                                    <span class="font-bold text-slate-900 block">{{ $booking->field->name }}</span>
                                    <span class="text-[10px] text-slate-500">{{ $booking->field->type_label }}</span>
                                </td>

                                <!-- Schedule -->
                                <td class="px-5 py-4">
                                    <div class="font-bold text-slate-800">{{ $booking->booking_date->format('d M Y') }}</div>
                                    <div class="text-[11px] text-emerald-700 font-semibold mt-0.5">
                                        {{ $booking->bookingDetails->count() }} Jam: 
                                        @foreach($booking->bookingDetails as $d)
                                            <span class="inline-block bg-slate-100 px-1.5 py-0.5 rounded text-[10px] text-slate-700 mr-1 mt-0.5">
                                                {{ substr($d->timeSlot->start_time, 0, 5) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>

                                <!-- Total -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="font-extrabold text-slate-900 text-xs">{{ $booking->formatted_total }}</span>
                                </td>

                                <!-- Payment Method & Status -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    @if($booking->payment)
                                        <div class="flex items-center gap-1.5">
                                            @if($booking->payment->payment_method === 'qris')
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                                    <i class="fa-solid fa-qrcode mr-1"></i> QRIS
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-teal-50 text-teal-700 border border-teal-200">
                                                    <i class="fa-solid fa-money-bill-wave mr-1"></i> Cash di Kasir
                                                </span>
                                            @endif
                                        </div>
                                        <span class="block text-[10px] text-slate-400 mt-1 capitalize">
                                            Status: {{ $booking->payment->status_meta['label'] }}
                                        </span>
                                    @else
                                        <span class="text-[11px] text-rose-500 font-semibold italic">Belum checkout</span>
                                    @endif
                                </td>

                                <!-- Booking Status Badge -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $booking->status_meta['badge'] }}">
                                        {{ $booking->status_meta['label'] }}
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td class="px-5 py-4 whitespace-nowrap text-right space-x-1.5">
                                    @if($booking->status === 'pending' && (!$booking->payment || $booking->payment->status === 'rejected'))
                                        <a href="{{ route('payment.checkout', $booking->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-lg shadow-xs transition-colors">
                                            <i class="fa-solid fa-credit-card text-[10px]"></i> Bayar
                                        </a>

                                        <form method="POST" action="{{ route('payment.cancel', ['booking' => $booking->id, 'redirect_to' => 'my_bookings']) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan #{{ $booking->booking_code }}?')">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold text-xs rounded-lg border border-rose-200 transition-colors cursor-pointer">
                                                <i class="fa-solid fa-xmark text-[10px]"></i> Batal
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('booking.show', $booking->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-lg transition-colors">
                                        <i class="fa-regular fa-file-lines text-[10px]"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($bookings->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $bookings->withQueryString()->links() }}
                </div>
            @endif
        </div>
    @endif
</div>
@endsection

