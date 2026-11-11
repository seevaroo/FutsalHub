@extends('layouts.admin')

@section('title', 'Verifikasi Transaksi Pembayaran - FutsalHub')
@section('page_title', 'Verifikasi Transaksi & Pembayaran')

@section('content')
<div class="space-y-6">
    <!-- Filter Tabs & Stats Ribbon -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- Search and Filter Form -->
        <form method="GET" action="{{ route('admin.payments.index') }}" class="flex flex-wrap items-center gap-2 flex-1 max-w-2xl">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari kode booking / nama / no HP..."
                class="px-3.5 py-2 text-xs bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 flex-1 min-w-48">

            <select name="status" class="px-3 py-2 text-xs bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-700">
                <option value="">Semua Status Bayar</option>
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="verified" {{ $status === 'verified' ? 'selected' : '' }}>Verified (Lunas)</option>
                <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected (Ditolak)</option>
            </select>

            <select name="method" class="px-3 py-2 text-xs bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-700">
                <option value="">Semua Metode</option>
                <option value="qris" {{ $method === 'qris' ? 'selected' : '' }}>QRIS</option>
                <option value="cash" {{ $method === 'cash' ? 'selected' : '' }}>Cash di Tempat</option>
            </select>

            <button type="submit" class="px-3.5 py-2 bg-slate-900 text-white text-xs font-bold rounded-xl hover:bg-slate-800 transition-colors">
                <i class="fa-solid fa-filter mr-1"></i> Filter
            </button>

            @if($search || $status || $method)
                <a href="{{ route('admin.payments.index') }}" class="px-3 py-2 bg-slate-200 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-300 transition-colors">
                    Reset
                </a>
            @endif
        </form>

        <!-- Quick Status Pills -->
        <div class="flex items-center gap-2 text-xs self-start md:self-auto">
            <span class="px-3 py-1.5 rounded-xl bg-amber-50 text-amber-800 border border-amber-200 font-bold">
                <i class="fa-solid fa-hourglass-half mr-1 text-amber-500"></i> {{ $counts['pending_verif'] }} Menunggu Verifikasi
            </span>
        </div>
    </div>

    <!-- Payments Verification Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        @if($bookings->isEmpty())
            <div class="p-12 text-center text-slate-400 text-xs">
                <i class="fa-solid fa-receipt text-3xl mb-2 block text-slate-300"></i>
                Tidak ada data transaksi yang cocok dengan kriteria filter.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-[11px] uppercase font-bold text-slate-500 tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3.5">Kode Transaksi</th>
                            <th class="px-4 py-3.5">Pelanggan</th>
                            <th class="px-4 py-3.5">Lapangan & Jadwal</th>
                            <th class="px-4 py-3.5">Total Bayar</th>
                            <th class="px-4 py-3.5">Metode</th>
                            <th class="px-4 py-3.5 text-center">Bukti Transfer</th>
                            <th class="px-4 py-3.5">Status Booking</th>
                            <th class="px-4 py-3.5 text-right">Tindakan Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($bookings as $booking)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <!-- Booking Code -->
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="font-mono font-black text-slate-900 block text-xs">{{ $booking->booking_code }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $booking->created_at->format('d/m/Y H:i') }}</span>
                                </td>

                                <!-- Customer -->
                                <td class="px-4 py-4">
                                    <span class="font-bold text-slate-900 block">{{ $booking->customer_name }}</span>
                                    @if($booking->whatsapp_url)
                                        <a href="{{ $booking->whatsapp_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 text-[10px] text-emerald-700 hover:text-emerald-800 font-semibold hover:underline mt-0.5" title="Chat via WhatsApp">
                                            <i class="fa-brands fa-whatsapp text-[11px] text-emerald-600"></i>
                                            <span>{{ $booking->customer_phone }}</span>
                                        </a>
                                    @else
                                        <span class="text-[10px] text-slate-500">{{ $booking->customer_phone }}</span>
                                    @endif
                                </td>

                                <!-- Field & Schedule -->
                                <td class="px-4 py-4">
                                    <span class="font-bold text-slate-900 block">{{ $booking->field->name }}</span>
                                    <span class="text-[11px] text-slate-500 block">{{ $booking->booking_date->format('d M Y') }}</span>
                                    <span class="text-[10px] text-emerald-700 font-semibold">
                                        {{ $booking->bookingDetails->count() }} Jam ({{ $booking->bookingDetails->first()?->timeSlot?->time_range ?? '' }})
                                    </span>
                                </td>

                                <!-- Total -->
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="font-black text-slate-900 text-xs">{{ $booking->formatted_total }}</span>
                                </td>

                                <!-- Method & Payment Status -->
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @if($booking->payment)
                                        @if($booking->payment->payment_method === 'qris')
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200 block w-max">
                                                <i class="fa-solid fa-qrcode mr-1"></i> QRIS
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-teal-50 text-teal-700 border border-teal-200 block w-max">
                                                <i class="fa-solid fa-money-bill-wave mr-1"></i> Cash Kasir
                                            </span>
                                        @endif
                                        <span class="text-[10px] text-slate-400 mt-1 block">
                                            Status: <strong class="text-slate-600">{{ $booking->payment->status_meta['label'] }}</strong>
                                        </span>
                                    @else
                                        <span class="text-rose-500 italic text-[11px]">Belum Checkout</span>
                                    @endif
                                </td>

                                <!-- Proof Image Modal Trigger & Direct Link -->
                                <td class="px-4 py-4 text-center whitespace-nowrap">
                                    @if($booking->payment && $booking->payment->proof_image)
                                        <div class="inline-flex items-center gap-1.5">
                                            <button type="button" 
                                                onclick="openProofModal('{{ asset('storage/' . $booking->payment->proof_image) }}', '{{ $booking->booking_code }}', '{{ addslashes($booking->customer_name) }}', '{{ $booking->formatted_total }}')"
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 text-[11px] font-bold transition-colors cursor-pointer group"
                                                title="Pratinjau Bukti Transfer">
                                                <i class="fa-regular fa-image text-xs group-hover:scale-110 transition-transform"></i>
                                                <span>Lihat Bukti Bayar</span>
                                            </button>
                                            <a href="{{ asset('storage/' . $booking->payment->proof_image) }}" target="_blank"
                                                class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 transition-colors"
                                                title="Buka Gambar di Tab Baru">
                                                <i class="fa-solid fa-arrow-up-right-from-square text-[11px]"></i>
                                            </a>
                                        </div>
                                    @elseif($booking->payment && $booking->payment->payment_method === 'cash')
                                        <span class="text-[10px] text-slate-400 italic">Bayar di Kasir</span>
                                    @else
                                        <span class="text-[10px] text-slate-400 italic">Tidak ada foto</span>
                                    @endif
                                </td>

                                <!-- Booking Status Badge -->
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $booking->status_meta['badge'] }}">
                                        {{ $booking->status_meta['label'] }}
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td class="px-4 py-4 whitespace-nowrap text-right space-x-1">
                                    <!-- QRIS Verification Buttons -->
                                    @if($booking->payment && $booking->payment->payment_method === 'qris' && $booking->status === 'pending')
                                        <form method="POST" action="{{ route('admin.payments.approve', $booking->id) }}" class="inline-block" onsubmit="return confirm('Setujui bukti transfer QRIS untuk booking {{ $booking->booking_code }}?')">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg shadow-xs transition-colors">
                                                <i class="fa-solid fa-check text-[10px]"></i> Setujui
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.payments.reject', $booking->id) }}" class="inline-block" onsubmit="return confirm('Tolak bukti transfer dan batalkan booking {{ $booking->booking_code }}?')">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-lg shadow-xs transition-colors">
                                                <i class="fa-solid fa-xmark text-[10px]"></i> Tolak
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Cash at Venue Mark Completed & Cancel Buttons -->
                                    @if($booking->payment && $booking->payment->payment_method === 'cash' && $booking->status === 'pending')
                                        <form method="POST" action="{{ route('admin.payments.cash_completed', $booking->id) }}" class="inline-block" onsubmit="return confirm('Konfirmasi bahwa customer telah membayar tunai {{ $booking->formatted_total }} di kasir?')">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg shadow-xs transition-colors">
                                                <i class="fa-solid fa-check text-[10px]"></i> Konfirmasi
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.payments.cash_reject', $booking->id) }}" class="inline-block" onsubmit="return confirm('Batalkan pesanan cash untuk booking {{ $booking->booking_code }}?')">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-lg shadow-xs transition-colors">
                                                <i class="fa-solid fa-xmark text-[10px]"></i> Batalkan
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($bookings->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $bookings->withQueryString()->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

<!-- Modal Popup for Proof Image Preview -->
<div id="proofModal" class="fixed inset-0 z-50 bg-slate-900/80 backdrop-blur-xs hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl border border-slate-200 animate-in fade-in zoom-in-95 duration-200">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-extrabold text-slate-900">Pratinjau Bukti Transfer QRIS</h3>
                <p id="modalSubtitle" class="text-xs text-slate-500 mt-0.5"></p>
            </div>
            <button type="button" onclick="closeProofModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="p-5 bg-slate-950 flex items-center justify-center min-h-64 max-h-[70vh] overflow-auto">
            <img id="modalImage" src="" alt="Bukti Transfer" class="max-h-[65vh] rounded-xl object-contain shadow-md">
        </div>

        <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs">
            <span class="text-slate-500 font-medium">Periksa nominal & waktu transfer sesuai pesanan.</span>
            <button type="button" onclick="closeProofModal()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openProofModal(imageUrl, bookingCode, customerName, total) {
        document.getElementById('modalImage').src = imageUrl;
        document.getElementById('modalSubtitle').textContent = `Pesanan #${bookingCode} • ${customerName} • ${total}`;
        document.getElementById('proofModal').classList.remove('hidden');
    }

    function closeProofModal() {
        document.getElementById('proofModal').classList.add('hidden');
    }

    // Close on backdrop click
    document.getElementById('proofModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeProofModal();
        }
    });
</script>
@endpush
@endsection

