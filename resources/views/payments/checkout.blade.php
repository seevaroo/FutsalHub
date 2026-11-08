@extends('layouts.app')

@section('title', 'Selesaikan Pembayaran - ' . $booking->booking_code . ' - FutsalHub')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Top Action Bar (Back) -->
    <div class="flex items-center justify-between gap-3 mb-6">
        <a href="{{ route('booking.create', $booking->field_id) }}" class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold text-slate-700 bg-white hover:bg-slate-100 rounded-xl border border-slate-200 shadow-xs transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Pemilihan Jadwal</span>
        </a>
    </div>

    <!-- Header -->
    <div class="mb-6">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200 mb-2">
            <i class="fa-solid fa-hourglass-half text-amber-500"></i> Menunggu Konfirmasi Pembayaran
        </span>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Checkout Pembayaran</h1>
        <p class="text-xs text-slate-500 mt-1">Kode Transaksi: <span class="font-bold font-mono text-emerald-600">{{ $booking->booking_code }}</span></p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left 2 Cols: Payment Methods Form -->
        <div class="lg:col-span-2 space-y-6">
            <form method="POST" action="{{ route('payment.process', $booking->id) }}" enctype="multipart/form-data" id="paymentForm">
                @csrf

                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
                    <h2 class="text-base font-black text-slate-900 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-credit-card text-emerald-600"></i> Pilih Metode Pembayaran
                    </h2>

                    <!-- Payment Option Tabs -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        <!-- Option 1: QRIS -->
                        <label class="payment-method-card relative flex flex-col p-4 rounded-2xl border-2 border-emerald-600 bg-emerald-50/30 cursor-pointer transition-all select-none group" id="cardQris">
                            <input type="radio" name="payment_method" value="qris" checked class="sr-only" onchange="togglePaymentMethod('qris')">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-black text-slate-900 flex items-center gap-2">
                                    <i class="fa-solid fa-qrcode text-emerald-600 text-base"></i> QRIS Realtime
                                </span>
                                <span class="w-4 h-4 rounded-full border-2 border-emerald-600 flex items-center justify-center" id="radioDotQris">
                                    <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-500 leading-relaxed">
                                Bebas biaya admin. Mendukung semua e-Wallet & Mobile Banking (BCA, Mandiri, GoPay, OVO, Dana).
                            </p>
                        </label>

                        <!-- Option 2: Cash -->
                        <label class="payment-method-card relative flex flex-col p-4 rounded-2xl border-2 border-slate-200 bg-white hover:bg-slate-50 cursor-pointer transition-all select-none group" id="cardCash">
                            <input type="radio" name="payment_method" value="cash" class="sr-only" onchange="togglePaymentMethod('cash')">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-black text-slate-900 flex items-center gap-2">
                                    <i class="fa-solid fa-money-bill-wave text-teal-600 text-base"></i> Bayar di Tempat
                                </span>
                                <span class="w-4 h-4 rounded-full border-2 border-slate-300 flex items-center justify-center" id="radioDotCash">
                                    <span class="w-2 h-2 rounded-full bg-transparent"></span>
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-500 leading-relaxed">
                                Bayar tunai langsung di meja kasir saat tim Anda tiba di arena futsal.
                            </p>
                        </label>
                    </div>

                    <!-- Panel Section: QRIS Content -->
                    <div id="sectionQris" class="space-y-6 pt-2">
                        <!-- QRIS Static Card -->
                        <div class="bg-slate-900 text-white rounded-2xl p-6 text-center shadow-lg relative overflow-hidden">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[11px] font-bold bg-white/10 text-emerald-300 border border-white/10 mb-4">
                                <i class="fa-solid fa-shield-check"></i> Merchant Resmi: PT FUTSALHUB INDONESIA
                            </div>
                            <h3 class="text-base font-extrabold mb-1">Scan QRIS untuk Pembayaran</h3>
                            <p class="text-xs text-slate-400 mb-5">Total Tagihan: <span class="font-extrabold text-emerald-400 text-sm">{{ $booking->formatted_total }}</span></p>

                            <!-- QRIS Image Box -->
                            <div class="inline-block bg-white p-3 sm:p-4 rounded-2xl shadow-xl mx-auto max-w-xs">
                                <img src="{{ asset('images/qris.png') }}" alt="QRIS FutsalHub" class="w-56 h-auto max-h-72 object-contain mx-auto rounded-xl">
                                <div class="text-[10px] font-bold text-slate-700 tracking-wider mt-2 uppercase">
                                    NMID: ID1020038927 • PT FUTSALHUB
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center justify-center gap-3 sm:gap-4 mt-6 text-xs text-slate-300">
                                <span class="flex items-center gap-1.5"><i class="fa-solid fa-mobile-screen text-emerald-400"></i> Buka M-Banking / E-Wallet</span>
                                <span class="hidden sm:inline text-slate-600">•</span>
                                <span class="flex items-center gap-1.5"><i class="fa-solid fa-qrcode text-emerald-400"></i> Scan & Bayar Sesuai Total</span>
                            </div>
                        </div>

                        <!-- Upload Proof Input -->
                        <div class="border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center hover:border-emerald-400 transition-colors bg-slate-50">
                            <label for="proof_image" class="cursor-pointer block">
                                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-3">
                                    <i class="fa-solid fa-cloud-arrow-up text-xl"></i>
                                </div>
                                <span class="text-xs font-bold text-slate-800 block">Unggah Bukti Struk / Screenshot Pembayaran</span>
                                <span class="text-[11px] text-slate-500 mt-1 block">Format: JPG, JPEG, PNG (Maksimal 2 MB)</span>
                                
                                <input type="file" id="proof_image" name="proof_image" accept="image/*" class="sr-only" onchange="previewImage(this)">

                                <span class="mt-4 inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-slate-200 hover:border-emerald-400 rounded-xl text-xs font-bold text-slate-700 shadow-xs">
                                    <i class="fa-solid fa-image text-emerald-600"></i> Pilih File Gambar
                                </span>
                            </label>

                            <!-- Image Preview Container -->
                            <div id="imagePreviewContainer" class="hidden mt-4 pt-4 border-t border-slate-200">
                                <p class="text-xs font-bold text-emerald-700 mb-2">Pratinjau Bukti Transfer:</p>
                                <img id="imagePreview" class="max-h-56 mx-auto rounded-xl shadow-md border border-slate-200">
                                <p id="fileName" class="text-[11px] text-slate-500 mt-1"></p>
                            </div>

                            @error('proof_image')
                                <p class="text-rose-600 text-xs mt-2 font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Panel Section: Cash Content -->
                    <div id="sectionCash" class="hidden space-y-4 pt-2">
                        <div class="bg-teal-50 border border-teal-200 rounded-2xl p-5 text-teal-900">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-hand-holding-dollar text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-extrabold">Instruksi Pembayaran di Tempat (Cash)</h4>
                                    <p class="text-xs text-teal-800 mt-1 leading-relaxed">
                                        Setelah menekan tombol konfirmasi di bawah, jadwal Anda akan dicatat dalam status <strong>Pending</strong>.
                                    </p>
                                    <ul class="text-xs text-teal-800 mt-2 space-y-1 list-disc list-inside">
                                        <li>Tunjukkan Kode Booking: <strong>{{ $booking->booking_code }}</strong> kepada kasir lapangan.</li>
                                        <li>Lakukan pelunasan tunai sebesar <strong>{{ $booking->formatted_total }}</strong> selambatnya 15 menit sebelum waktu kick-off.</li>
                                        <li>Kasir akan menandai pesanan Anda sebagai lunas.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full mt-6 py-3.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-sm rounded-xl shadow-lg shadow-emerald-600/30 transition-all transform active:scale-[0.99] flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-lock text-xs"></i>
                        <span id="submitBtnText">Konfirmasi & Kirim Bukti QRIS</span>
                    </button>

                    <!-- Secondary Back & Cancel Actions -->
                    <div class="mt-4 pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                        <a href="{{ route('home') }}" class="text-slate-500 hover:text-slate-800 font-semibold flex items-center gap-1.5 transition-colors">
                            <i class="fa-solid fa-house text-[11px]"></i> Kembali ke Beranda
                        </a>

                        <button type="button" onclick="if(confirm('Apakah Anda yakin ingin membatalkan pesanan ini? Jam bermain yang sudah Anda pilih akan dilepaskan kembali.')) { document.getElementById('formCancelBookingBottom').submit(); }" class="text-rose-600 hover:text-rose-700 font-bold flex items-center gap-1.5 transition-colors cursor-pointer">
                            <i class="fa-solid fa-ban text-[11px]"></i> Batalkan Pesanan
                        </button>
                    </div>
                </div>
            </form>

            <!-- Hidden Form for Bottom Cancel Button -->
            <form id="formCancelBookingBottom" method="POST" action="{{ route('payment.cancel', $booking->id) }}" class="hidden">
                @csrf
            </form>
        </div>

        <!-- Right Col: Booking Detail Card -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs sticky top-24">
                <h3 class="text-base font-black text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                    <i class="fa-solid fa-clipboard-check text-emerald-600"></i> Detail Reservasi
                </h3>

                <div class="py-4 space-y-3.5 text-xs">
                    <div>
                        <span class="text-slate-500 block mb-0.5">Kode Booking</span>
                        <span class="text-base font-extrabold font-mono text-emerald-600">{{ $booking->booking_code }}</span>
                    </div>

                    <div class="flex justify-between items-center text-slate-600">
                        <span>Nama Pemesan:</span>
                        <strong class="text-slate-900 font-bold">{{ $booking->customer_name }}</strong>
                    </div>

                    <div class="flex justify-between items-center text-slate-600">
                        <span>Lapangan:</span>
                        <strong class="text-slate-900 font-bold">{{ $booking->field->name }}</strong>
                    </div>

                    <div class="flex justify-between items-center text-slate-600">
                        <span>Lantai:</span>
                        <span class="font-bold text-slate-900">{{ $booking->field->type_label }}</span>
                    </div>

                    <div class="flex justify-between items-center text-slate-600">
                        <span>Tanggal Sewa:</span>
                        <strong class="text-slate-900 font-bold">{{ $booking->booking_date->format('d F Y') }}</strong>
                    </div>

                    <div class="pt-2 border-t border-slate-100">
                        <span class="text-slate-500 block mb-1.5 font-bold">Slot Jam Operasional:</span>
                        <div class="space-y-1.5">
                            @foreach($booking->bookingDetails as $detail)
                                <div class="flex justify-between items-center bg-slate-50 px-2.5 py-1.5 rounded-lg border border-slate-100">
                                    <span class="font-bold text-slate-800">
                                        <i class="fa-regular fa-clock text-emerald-600 mr-1"></i> {{ $detail->timeSlot->time_range }}
                                    </span>
                                    <span class="text-slate-600 font-semibold">{{ $detail->formatted_price }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex justify-between items-baseline">
                        <span class="text-xs font-bold text-slate-700">Total Tagihan:</span>
                        <span class="text-2xl font-black text-emerald-600">{{ $booking->formatted_total }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function togglePaymentMethod(method) {
        const cardQris = document.getElementById('cardQris');
        const cardCash = document.getElementById('cardCash');
        const radioDotQris = document.getElementById('radioDotQris').firstElementChild;
        const radioDotCash = document.getElementById('radioDotCash').firstElementChild;
        const sectionQris = document.getElementById('sectionQris');
        const sectionCash = document.getElementById('sectionCash');
        const submitBtnText = document.getElementById('submitBtnText');
        const proofInput = document.getElementById('proof_image');

        if (method === 'qris') {
            cardQris.className = 'payment-method-card relative flex flex-col p-4 rounded-2xl border-2 border-emerald-600 bg-emerald-50/30 cursor-pointer transition-all select-none group';
            cardCash.className = 'payment-method-card relative flex flex-col p-4 rounded-2xl border-2 border-slate-200 bg-white hover:bg-slate-50 cursor-pointer transition-all select-none group';
            radioDotQris.className = 'w-2 h-2 rounded-full bg-emerald-600';
            radioDotCash.className = 'w-2 h-2 rounded-full bg-transparent';
            sectionQris.classList.remove('hidden');
            sectionCash.classList.add('hidden');
            submitBtnText.textContent = 'Konfirmasi & Kirim Bukti QRIS';
            proofInput.required = true;
        } else {
            cardCash.className = 'payment-method-card relative flex flex-col p-4 rounded-2xl border-2 border-emerald-600 bg-emerald-50/30 cursor-pointer transition-all select-none group';
            cardQris.className = 'payment-method-card relative flex flex-col p-4 rounded-2xl border-2 border-slate-200 bg-white hover:bg-slate-50 cursor-pointer transition-all select-none group';
            radioDotCash.className = 'w-2 h-2 rounded-full bg-emerald-600';
            radioDotQris.className = 'w-2 h-2 rounded-full bg-transparent';
            sectionCash.classList.remove('hidden');
            sectionQris.classList.add('hidden');
            submitBtnText.textContent = 'Konfirmasi Pesanan Bayar di Tempat';
            proofInput.required = false;
        }
    }

    function previewImage(input) {
        const container = document.getElementById('imagePreviewContainer');
        const img = document.getElementById('imagePreview');
        const name = document.getElementById('fileName');

        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                img.src = e.target.result;
                name.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
                container.classList.remove('hidden');
            }

            reader.readAsDataURL(file);
        }
    }
</script>
@endpush
@endsection

