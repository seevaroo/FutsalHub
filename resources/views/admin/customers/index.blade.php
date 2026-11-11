@extends('layouts.admin')

@section('title', 'Data Customer - FutsalHub')
@section('page_title', 'Data Member Customer')

@section('content')
<div class="space-y-6">
    <!-- Top Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <!-- Card 1: Active Customers -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Customer Aktif</span>
                <span class="text-xl sm:text-2xl font-black text-slate-900 mt-1 block">{{ $activeCount }} Member</span>
                <span class="text-[11px] text-emerald-600 font-semibold mt-1 inline-flex items-center gap-1">
                    <i class="fa-solid fa-circle-check"></i> Siap melakukan reservasi
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        <!-- Card 2: New Customers This Month -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Pendaftaran Bulan Ini</span>
                <span class="text-xl sm:text-2xl font-black text-slate-900 mt-1 block">{{ $newThisMonth }} Member Baru</span>
                <span class="text-[11px] text-blue-600 font-semibold mt-1 inline-flex items-center gap-1">
                    <i class="fa-solid fa-user-plus"></i> Bergabung {{ now()->format('F Y') }}
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-user-check"></i>
            </div>
        </div>

        <!-- Card 3: Trashed Customers -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Akun Dinonaktifkan</span>
                <span class="text-xl sm:text-2xl font-black text-rose-600 mt-1 block">{{ $trashedCount }} Dihapus</span>
                <span class="text-[11px] text-slate-500 font-medium mt-1 inline-block">
                    Dilarang login ke sistem
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-user-slash"></i>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <!-- Status Tabs (Aktif / Nonaktif) -->
        <div class="flex items-center gap-2 bg-slate-200/70 p-1.5 rounded-2xl self-start">
            <a href="{{ route('admin.customers.index', array_filter(['search' => $search])) }}" 
                class="px-4 py-2 text-xs font-bold rounded-xl transition-all {{ $status === 'active' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-user-check mr-1.5 text-emerald-600"></i> Aktif ({{ $activeCount }})
            </a>
            <a href="{{ route('admin.customers.index', array_filter(['status' => 'trashed', 'search' => $search])) }}" 
                class="px-4 py-2 text-xs font-bold rounded-xl transition-all {{ $status === 'trashed' ? 'bg-white text-rose-700 shadow-xs' : 'text-slate-600 hover:text-rose-600' }}">
                <i class="fa-solid fa-user-slash mr-1.5 text-rose-500"></i> Dihapus / Nonaktif ({{ $trashedCount }})
            </a>
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('admin.customers.index') }}" class="flex items-center gap-2 w-full sm:w-auto">
            @if($status === 'trashed')
                <input type="hidden" name="status" value="trashed">
            @endif
            <div class="relative flex-1 sm:w-72">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, email, no HP..."
                    class="w-full pl-9 pr-4 py-2.5 text-xs bg-white border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-800 font-semibold shadow-xs">
            </div>
            <button type="submit" class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-xs transition-colors shrink-0 cursor-pointer">
                Cari
            </button>
            @if(!empty($search))
                <a href="{{ route('admin.customers.index', array_filter(['status' => $status !== 'active' ? $status : null])) }}" 
                    class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-xl transition-colors shrink-0" title="Reset pencarian">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Customers Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        @if($customers->isEmpty())
            <div class="p-12 text-center text-slate-400 text-xs">
                <i class="fa-solid fa-users-slash text-4xl mb-3 block text-slate-300"></i>
                <h3 class="text-sm font-bold text-slate-700">Tidak ada data customer yang cocok</h3>
                <p class="text-xs text-slate-500 mt-1">
                    {{ !empty($search) ? 'Tidak ditemukan member dengan kata kunci pencarian tersebut.' : 'Belum ada data member customer pada kategori ini.' }}
                </p>
                @if(!empty($search))
                    <a href="{{ route('admin.customers.index', array_filter(['status' => $status !== 'active' ? $status : null])) }}" class="mt-4 inline-block px-4 py-2 bg-emerald-600 text-white text-xs font-bold rounded-xl shadow-xs hover:bg-emerald-700 transition-colors">
                        Tampilkan Semua
                    </a>
                @endif
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-[11px] uppercase font-bold text-slate-500 tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-3.5">Customer</th>
                            <th class="px-5 py-3.5">Kontak WhatsApp</th>
                            <th class="px-5 py-3.5">Tgl Bergabung</th>
                            <th class="px-5 py-3.5 text-center">Riwayat Booking</th>
                            <th class="px-5 py-3.5">Status Akun</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($customers as $customer)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <!-- Profile Avatar & Name -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $customer->avatar_url }}" alt="{{ $customer->name }}" class="w-10 h-10 rounded-full object-cover border border-emerald-500/30 shadow-2xs shrink-0">
                                        <div>
                                            <span class="font-bold text-slate-900 block text-xs sm:text-sm">{{ $customer->name }}</span>
                                            <span class="text-[11px] text-slate-500 flex items-center gap-1">
                                                <i class="fa-regular fa-envelope text-[10px] text-slate-400"></i>
                                                <span>{{ $customer->email }}</span>
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <!-- WhatsApp Contact & Direct Chat -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    @if($customer->whatsapp_url)
                                        <div class="flex items-center gap-2">
                                            <a href="{{ $customer->whatsapp_url }}" 
                                               target="_blank" 
                                               rel="noopener noreferrer" 
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold text-xs shadow-xs shadow-emerald-600/25 transition-all cursor-pointer group"
                                               title="Klik untuk langsung chat WhatsApp ke {{ $customer->name }}">
                                                <i class="fa-brands fa-whatsapp text-sm group-hover:scale-110 transition-transform"></i>
                                                <span>Chat WhatsApp</span>
                                            </a>
                                            <span class="font-mono text-xs font-bold text-slate-700 select-all">
                                                {{ $customer->formatted_phone }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-slate-400 italic text-[11px]">- Belum ada nomor -</span>
                                    @endif
                                </td>

                                <!-- Joined Date -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="text-slate-800 font-bold block">{{ $customer->created_at ? $customer->created_at->format('d M Y') : '-' }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $customer->created_at ? $customer->created_at->diffForHumans() : '' }}</span>
                                </td>

                                <!-- Bookings Count -->
                                <td class="px-5 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold {{ $customer->bookings_count > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                                        <i class="fa-solid fa-receipt text-[10px]"></i>
                                        <span>{{ $customer->bookings_count }} Pesanan</span>
                                    </span>
                                </td>

                                <!-- Status Badge -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    @if($customer->trashed())
                                        @if($customer->isDeletedByUser())
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-300">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span> Dihapus User
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Dihapus Admin
                                            </span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                        </span>
                                    @endif
                                </td>

                                <!-- Action Buttons -->
                                <td class="px-5 py-4 whitespace-nowrap text-right">
                                    @if($customer->trashed())
                                        @if($customer->isDeletedByUser())
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl bg-slate-100 border border-slate-200 text-slate-500 text-xs font-semibold" title="Akun ini dihapus sendiri oleh pengguna dan tidak dapat dipulihkan">
                                                <i class="fa-solid fa-lock text-[10px] text-slate-400"></i>
                                                <span>Tidak dapat dipulihkan</span>
                                            </span>
                                        @else
                                            <!-- Restore Button -->
                                            <form method="POST" action="{{ route('admin.customers.restore', $customer->id) }}" class="inline-block" onsubmit="return confirm('Aktifkan kembali akun customer {{ $customer->name }}?')">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition-colors cursor-pointer" title="Pulihkan Akun">
                                                    <i class="fa-solid fa-rotate-left text-[11px]"></i>
                                                    <span>Pulihkan</span>
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <!-- Delete Button Trigger Modal -->
                                        <button type="button" 
                                            onclick="openDeleteCustomerModal('{{ $customer->id }}', '{{ addslashes($customer->name) }}', '{{ $customer->email }}', '{{ $customer->bookings_count }}')"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white font-bold text-xs rounded-xl border border-rose-200 hover:border-rose-600 transition-colors cursor-pointer" 
                                            title="Hapus / Nonaktifkan Akun Customer">
                                            <i class="fa-solid fa-trash-can text-[11px]"></i>
                                            <span>Hapus Akun</span>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($customers->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $customers->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

<!-- Modal Konfirmasi Hapus Akun Customer -->
<div id="deleteCustomerModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl border border-slate-200 animate-in fade-in zoom-in-95 duration-150">
        <div class="text-center">
            <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-4 border border-rose-100 shadow-xs">
                <i class="fa-solid fa-user-xmark text-2xl"></i>
            </div>

            <h3 class="text-lg font-black text-slate-900 mb-1">Hapus Akun Customer?</h3>
            <p class="text-xs text-slate-500 mb-4">
                Anda akan menonaktifkan akun member <strong id="modalCustomerName" class="text-slate-800"></strong> (<span id="modalCustomerEmail" class="font-mono text-slate-600"></span>).
            </p>

            <div class="p-3.5 bg-amber-50 rounded-2xl border border-amber-200 text-left text-xs text-amber-900 mb-6">
                <div class="flex items-start gap-2.5">
                    <i class="fa-solid fa-triangle-exclamation text-amber-600 mt-0.5 shrink-0 text-sm"></i>
                    <div class="leading-relaxed">
                        <p class="font-bold text-amber-950">Dampak Penghapusan Akun:</p>
                        <ul class="list-disc list-inside mt-1 space-y-1 text-[11px] text-amber-800">
                            <li>Customer <strong>tidak akan bisa login</strong> lagi ke website.</li>
                            <li>Ketika customer mencoba login, sistem otomatis menampilkan notifikasi <em>"Akun Anda telah dihapus oleh administrator"</em>.</li>
                            <li>Seluruh riwayat transaksi sewa lapangan tetap diarsipkan untuk laporan admin.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" onclick="closeDeleteCustomerModal()" 
                    class="flex-1 py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-colors cursor-pointer">
                    Batal
                </button>

                <form id="deleteCustomerForm" method="POST" action="" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                        class="w-full py-2.5 px-4 bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-xs rounded-xl shadow-md shadow-rose-600/30 transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-trash-can text-xs"></i>
                        <span>Ya, Hapus Akun</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openDeleteCustomerModal(customerId, name, email, bookingCount) {
        document.getElementById('modalCustomerName').textContent = name;
        document.getElementById('modalCustomerEmail').textContent = email;
        document.getElementById('deleteCustomerForm').action = `{{ url('admin/customers') }}/${customerId}`;
        document.getElementById('deleteCustomerModal').classList.remove('hidden');
    }

    function closeDeleteCustomerModal() {
        document.getElementById('deleteCustomerModal').classList.add('hidden');
    }

    // Close on backdrop click
    document.getElementById('deleteCustomerModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteCustomerModal();
        }
    });
</script>
@endpush
@endsection

