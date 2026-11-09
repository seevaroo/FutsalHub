@extends('layouts.app')

@section('title', 'Pilih Jadwal Booking - ' . $field->name . ' - FutsalHub')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs text-slate-500 mb-6">
        <a href="{{ route('catalog') }}" class="hover:text-emerald-600 transition-colors">Katalog Lapangan</a>
        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
        <span class="text-slate-800 font-bold">Booking {{ $field->name }}</span>
    </div>

    <form method="POST" action="{{ route('booking.store') }}" id="bookingForm">
        @csrf
        <input type="hidden" name="field_id" value="{{ $field->id }}">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left 2 Cols: Date Picker & Slot Grid -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Field Quick Summary -->
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-xl bg-slate-900 overflow-hidden flex items-center justify-center shrink-0 border border-slate-200 shadow-xs">
                            <img src="{{ $field->image ? asset('storage/' . $field->image) : asset('images/katalog.png') }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h1 class="text-lg font-black text-slate-900">{{ $field->name }}</h1>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    {{ $field->type_label }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">Tarif reguler: <strong class="text-emerald-600">{{ $field->formatted_price }}</strong> / jam</p>
                        </div>
                    </div>

                    <a href="{{ route('catalog') }}" class="text-xs font-bold text-slate-600 hover:text-emerald-600 flex items-center gap-1.5 px-3 py-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                        <i class="fa-solid fa-arrows-rotate text-[11px]"></i> Ganti Lapangan
                    </a>
                </div>

                <!-- Date Picker Section -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
                                <i class="fa-regular fa-calendar-check text-emerald-600"></i> 1. Tentukan Tanggal Bermain
                            </h2>
                            <p class="text-xs text-slate-500 mt-0.5">Jadwal slot akan diperbarui secara otomatis sesuai tanggal yang dipilih</p>
                        </div>
                    </div>

                    @php
                        $todayDate = now()->setTimezone('Asia/Jakarta')->toDateString();
                        $currentTime = now()->setTimezone('Asia/Jakarta')->format('H:i:s');
                        $initialDate = old('booking_date', $todayDate);
                    @endphp

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="booking_date" class="block text-xs font-bold text-slate-700 mb-1.5">Pilih Tanggal:</label>
                            <input type="date" id="booking_date" name="booking_date" 
                                value="{{ $initialDate }}" 
                                min="{{ $todayDate }}"
                                required
                                class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 font-semibold text-slate-800">
                        </div>
                        <div class="flex items-end">
                            <div class="w-full bg-emerald-50 border border-emerald-200 text-emerald-900 p-3 rounded-xl text-xs flex items-center gap-2.5">
                                <i class="fa-solid fa-circle-info text-emerald-600 text-base"></i>
                                <span>Pilih satu atau lebih slot jam di bawah ini untuk bermain berkelanjutan.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Interactive Time Slots Grid -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                        <div>
                            <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
                                <i class="fa-regular fa-clock text-emerald-600"></i> 2. Pilih Slot Jam Operasional
                            </h2>
                            <p class="text-xs text-slate-500 mt-0.5">Operasional 08.00 - 22.00 WIB (Durasi per slot 60 menit)</p>
                        </div>

                        <!-- Legend Indicators -->
                        <div class="flex items-center gap-3 text-xs">
                            <span class="inline-flex items-center gap-1.5 font-medium text-slate-600">
                                <span class="w-3.5 h-3.5 rounded-md bg-emerald-50 border border-emerald-400"></span> Kosong
                            </span>
                            <span class="inline-flex items-center gap-1.5 font-medium text-slate-600">
                                <span class="w-3.5 h-3.5 rounded-md bg-emerald-600 border border-emerald-600"></span> Dipilih
                            </span>
                            <span class="inline-flex items-center gap-1.5 font-medium text-slate-400">
                                <span class="w-3.5 h-3.5 rounded-md bg-slate-100 border border-slate-300"></span> Terisi
                            </span>
                        </div>
                    </div>

                    <!-- Loading Spinner Overlay Indicator -->
                    <div id="slotLoading" class="hidden py-12 text-center">
                        <div class="inline-block animate-spin w-8 h-8 border-4 border-emerald-500 border-t-transparent rounded-full mb-2"></div>
                        <p class="text-xs text-slate-500 font-medium">Memeriksa ketersediaan jam secara real-time...</p>
                    </div>

                    <!-- Slots Grid -->
                    <div id="slotsGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                        @php $hasVisibleSlots = false; @endphp
                        @foreach($timeSlots as $slot)
                            @php
                                $isPastSlot = ($initialDate === $todayDate && $slot->start_time <= $currentTime);
                                if (!$isPastSlot) {
                                    $hasVisibleSlots = true;
                                }
                            @endphp
                            <label id="slot-card-{{ $slot->id }}" class="slot-card relative flex flex-col p-3 rounded-xl border border-emerald-200 bg-emerald-50/40 hover:bg-emerald-50 cursor-pointer transition-all duration-200 select-none group {{ $isPastSlot ? 'hidden' : '' }}">
                                <input type="checkbox" name="slots[]" value="{{ $slot->id }}" 
                                    data-start="{{ substr($slot->start_time, 0, 5) }}" 
                                    data-end="{{ substr($slot->end_time, 0, 5) }}"
                                    data-price="{{ $field->price_per_hour }}"
                                    class="slot-checkbox sr-only"
                                    {{ $isPastSlot ? 'disabled' : '' }}
                                    onchange="handleSlotChange(this)">

                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-xs font-black text-slate-800 slot-time-text">
                                        {{ substr($slot->start_time, 0, 5) }} - {{ substr($slot->end_time, 0, 5) }}
                                    </span>
                                    <span class="slot-badge inline-flex items-center text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700">
                                        Tersedia
                                    </span>
                                </div>
                                <div class="text-[11px] text-slate-500 font-medium slot-price-text">
                                    {{ $field->formatted_price }}
                                </div>
                            </label>
                        @endforeach

                        <!-- Empty State if all slots are past/unavailable -->
                        <div id="noSlotsAlert" class="{{ $hasVisibleSlots ? 'hidden' : '' }} col-span-full py-10 px-4 text-center bg-slate-50 border border-slate-200 rounded-xl">
                            <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-3 text-lg">
                                <i class="fa-regular fa-clock"></i>
                            </div>
                            <h3 class="text-sm font-bold text-slate-800">Semua Jam Operasional Hari Ini Sudah Lewat</h3>
                            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Tidak ada slot jam bermain yang tersisa untuk tanggal ini. Silakan pilih tanggal besok atau hari berikutnya di atas.</p>
                        </div>
                    </div>

                    @error('slots')
                        <p class="text-rose-600 text-xs mt-3 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Right Col: Booking Summary Sidebar -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs sticky top-24">
                    <h3 class="text-base font-black text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                        <i class="fa-solid fa-receipt text-emerald-600"></i> Ringkasan Reservasi
                    </h3>

                    <div class="py-4 space-y-3.5 text-xs">
                        <div class="flex justify-between items-center text-slate-600">
                            <span>Lapangan:</span>
                            <strong class="text-slate-900 font-bold">{{ $field->name }}</strong>
                        </div>
                        <div class="flex justify-between items-center text-slate-600">
                            <span>Tipe Lantai:</span>
                            <span class="font-bold text-slate-900">{{ $field->type_label }}</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-600">
                            <span>Tanggal:</span>
                            <strong id="summaryDate" class="text-slate-900 font-bold">{{ date('d F Y') }}</strong>
                        </div>
                        <div class="flex justify-between items-center text-slate-600">
                            <span>Tarif per Jam:</span>
                            <span class="text-slate-900 font-semibold">{{ $field->formatted_price }}</span>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100">
                        <span class="text-xs font-bold text-slate-700 block mb-2">Slot Jam Dipilih:</span>
                        <div id="selectedSlotsList" class="min-h-12 bg-slate-50 rounded-xl p-2.5 text-xs text-slate-500 border border-slate-100 flex flex-wrap gap-1.5 items-center">
                            <span class="text-slate-400 italic">Belum ada jam yang dipilih</span>
                        </div>
                    </div>

                    <div class="pt-4 mt-4 border-t border-slate-100 space-y-2">
                        <div class="flex justify-between text-xs text-slate-600">
                            <span>Total Durasi:</span>
                            <strong id="summaryHours" class="text-slate-900 font-bold">0 Jam</strong>
                        </div>
                        <div class="flex justify-between items-baseline pt-2">
                            <span class="text-xs font-bold text-slate-700">Total Biaya:</span>
                            <span id="summaryTotal" class="text-xl font-black text-emerald-600">Rp 0</span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="btnSubmitBooking" disabled class="w-full mt-6 py-3 px-4 bg-slate-200 text-slate-400 font-extrabold text-sm rounded-xl cursor-not-allowed transition-all flex items-center justify-center gap-2 shadow-xs">
                        <span>Lanjut ke Pembayaran</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                    <p class="text-[11px] text-slate-400 text-center mt-2.5">
                        <i class="fa-solid fa-shield-halved text-emerald-500 mr-1"></i> Transaksi Anda dilindungi sistem anti double-booking
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const pricePerHour = {{ $field->price_per_hour }};
    const fieldId = {{ $field->id }};
    const dateInput = document.getElementById('booking_date');
    const summaryDate = document.getElementById('summaryDate');
    const selectedSlotsList = document.getElementById('selectedSlotsList');
    const summaryHours = document.getElementById('summaryHours');
    const summaryTotal = document.getElementById('summaryTotal');
    const btnSubmitBooking = document.getElementById('btnSubmitBooking');
    const slotLoading = document.getElementById('slotLoading');
    const slotsGrid = document.getElementById('slotsGrid');

    // Format currency to IDR
    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    }

    // Format date string to Indonesian readable
    function formatReadableDate(dateString) {
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(dateString + 'T00:00:00').toLocaleDateString('id-ID', options);
    }

    // Fetch booked slots from API
    async function loadBookedSlots() {
        const selectedDate = dateInput.value;
        if (!selectedDate) return;

        summaryDate.textContent = formatReadableDate(selectedDate);

        // Show loading state
        slotLoading.classList.remove('hidden');
        slotsGrid.classList.add('opacity-40', 'pointer-events-none');

        try {
            const response = await fetch(`/api/check-slots?field_id=${fieldId}&date=${selectedDate}`);
            const data = await response.json();

            const bookedIds = (data.booked_slots || []).map(Number);
            const expiredIds = (data.expired_slots || []).map(Number);

            let visibleCount = 0;

            // Update slot cards in UI
            document.querySelectorAll('.slot-checkbox').forEach(cb => {
                const slotId = Number(cb.value);
                const card = document.getElementById(`slot-card-${slotId}`);
                if (!card) return;
                const badge = card.querySelector('.slot-badge');

                // Reset selection
                cb.checked = false;

                if (expiredIds.includes(slotId)) {
                    // Sembunyikan slot jam yang sudah lewat waktu sekarang
                    cb.disabled = true;
                    card.classList.add('hidden');
                } else {
                    card.classList.remove('hidden');
                    visibleCount++;

                    if (bookedIds.includes(slotId)) {
                        // Mark as Booked / Disabled
                        cb.disabled = true;
                        card.className = 'slot-card relative flex flex-col p-3 rounded-xl border border-slate-200 bg-slate-100/90 text-slate-400 cursor-not-allowed select-none opacity-60';
                        badge.className = 'slot-badge inline-flex items-center text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-200 text-slate-600';
                        badge.textContent = 'Terisi';
                        card.querySelector('.slot-time-text').classList.remove('text-white');
                        card.querySelector('.slot-time-text').classList.add('text-slate-800');
                        card.querySelector('.slot-price-text').classList.remove('text-white', 'text-emerald-100');
                        card.querySelector('.slot-price-text').classList.add('text-slate-500');
                    } else {
                        // Mark as Available
                        cb.disabled = false;
                        card.className = 'slot-card relative flex flex-col p-3 rounded-xl border border-emerald-200 bg-emerald-50/40 hover:bg-emerald-50 cursor-pointer transition-all duration-200 select-none group';
                        badge.className = 'slot-badge inline-flex items-center text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700';
                        badge.textContent = 'Tersedia';
                        card.querySelector('.slot-time-text').classList.remove('text-white');
                        card.querySelector('.slot-time-text').classList.add('text-slate-800');
                        card.querySelector('.slot-price-text').classList.remove('text-white', 'text-emerald-100');
                        card.querySelector('.slot-price-text').classList.add('text-slate-500');
                    }
                }
            });

            const noSlotsAlert = document.getElementById('noSlotsAlert');
            if (noSlotsAlert) {
                if (visibleCount === 0) {
                    noSlotsAlert.classList.remove('hidden');
                } else {
                    noSlotsAlert.classList.add('hidden');
                }
            }

            updateSummary();
        } catch (error) {
            console.error('Error fetching slots:', error);
        } finally {
            slotLoading.classList.add('hidden');
            slotsGrid.classList.remove('opacity-40', 'pointer-events-none');
        }
    }

    // Handle slot selection toggle
    function handleSlotChange(checkbox) {
        const card = document.getElementById(`slot-card-${checkbox.value}`);
        const badge = card.querySelector('.slot-badge');

        if (checkbox.checked) {
            card.classList.remove('border-emerald-200', 'bg-emerald-50/40', 'hover:bg-emerald-50');
            card.classList.add('border-emerald-600', 'bg-emerald-600', 'text-white', 'shadow-md', 'shadow-emerald-600/30');
            badge.className = 'slot-badge inline-flex items-center text-[10px] font-bold px-2 py-0.5 rounded-full bg-white text-emerald-700 shadow-xs';
            badge.textContent = 'Dipilih';
            card.querySelector('.slot-time-text').classList.remove('text-slate-800');
            card.querySelector('.slot-time-text').classList.add('text-white');
            card.querySelector('.slot-price-text').classList.remove('text-slate-500');
            card.querySelector('.slot-price-text').classList.add('text-emerald-100');
        } else {
            card.classList.remove('border-emerald-600', 'bg-emerald-600', 'text-white', 'shadow-md', 'shadow-emerald-600/30');
            card.classList.add('border-emerald-200', 'bg-emerald-50/40', 'hover:bg-emerald-50');
            badge.className = 'slot-badge inline-flex items-center text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700';
            badge.textContent = 'Tersedia';
            card.querySelector('.slot-time-text').classList.add('text-slate-800');
            card.querySelector('.slot-time-text').classList.remove('text-white');
            card.querySelector('.slot-price-text').classList.add('text-slate-500');
            card.querySelector('.slot-price-text').classList.remove('text-emerald-100');
        }

        updateSummary();
    }

    // Update summary calculation
    function updateSummary() {
        const checked = Array.from(document.querySelectorAll('.slot-checkbox:checked'));
        const count = checked.length;

        if (count === 0) {
            selectedSlotsList.innerHTML = '<span class="text-slate-400 italic">Belum ada jam yang dipilih</span>';
            summaryHours.textContent = '0 Jam';
            summaryTotal.textContent = 'Rp 0';
            btnSubmitBooking.disabled = true;
            btnSubmitBooking.className = 'w-full mt-6 py-3 px-4 bg-slate-200 text-slate-400 font-extrabold text-sm rounded-xl cursor-not-allowed transition-all flex items-center justify-center gap-2 shadow-xs';
            return;
        }

        // Sort selected slots by start time
        checked.sort((a, b) => a.dataset.start.localeCompare(b.dataset.start));

        let html = '';
        checked.forEach(cb => {
            html += `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-800 font-bold text-[11px] border border-emerald-200">
                <i class="fa-regular fa-clock text-[10px]"></i> ${cb.dataset.start}-${cb.dataset.end}
            </span>`;
        });
        selectedSlotsList.innerHTML = html;

        summaryHours.textContent = `${count} Jam`;
        const total = count * pricePerHour;
        summaryTotal.textContent = formatRupiah(total);

        btnSubmitBooking.disabled = false;
        btnSubmitBooking.className = 'w-full mt-6 py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-sm rounded-xl shadow-lg shadow-emerald-600/30 transition-all transform active:scale-[0.99] flex items-center justify-center gap-2 cursor-pointer';
    }

    // Listen to date changes
    dateInput.addEventListener('change', loadBookedSlots);

    // Initial load
    document.addEventListener('DOMContentLoaded', () => {
        loadBookedSlots();
    });
</script>
@endpush
@endsection

