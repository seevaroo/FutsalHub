@extends('layouts.admin')

@section('title', 'Kelola Master Lapangan - FutsalHub')
@section('page_title', 'Data Master Lapangan')

@section('content')
<div class="space-y-6">
    <!-- Header with Action Button & Filters -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.fields.index') }}" class="flex flex-wrap items-center gap-2 flex-1 max-w-xl">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama lapangan..."
                class="px-3.5 py-2 text-xs bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 flex-1 min-w-40">

            <select name="type" class="px-3 py-2 text-xs bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-700">
                <option value="">Semua Tipe Lantai</option>
                <option value="vinyl" {{ $type === 'vinyl' ? 'selected' : '' }}>Vinyl</option>
                <option value="sintetis" {{ $type === 'sintetis' ? 'selected' : '' }}>Rumput Sintetis</option>
                <option value="interlock" {{ $type === 'interlock' ? 'selected' : '' }}>Interlock</option>
            </select>

            <select name="status" class="px-3 py-2 text-xs bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-700">
                <option value="">Semua Status</option>
                <option value="available" {{ $status === 'available' ? 'selected' : '' }}>Tersedia</option>
                <option value="maintenance" {{ $status === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
            </select>

            <button type="submit" class="px-3.5 py-2 bg-slate-900 text-white text-xs font-bold rounded-xl hover:bg-slate-800 transition-colors">
                <i class="fa-solid fa-filter mr-1"></i> Filter
            </button>

            @if($search || $type || $status)
                <a href="{{ route('admin.fields.index') }}" class="px-3 py-2 bg-slate-200 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-300 transition-colors">
                    Reset
                </a>
            @endif
        </form>

        <a href="{{ route('admin.fields.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-600/20 transition-all self-start sm:self-auto">
            <i class="fa-solid fa-plus text-xs"></i> Tambah Lapangan Baru
        </a>
    </div>

    <!-- Fields Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        @if($fields->isEmpty())
            <div class="p-12 text-center text-slate-400 text-xs">
                <i class="fa-solid fa-futbol text-3xl mb-2 block text-slate-300"></i>
                Belum ada data lapangan yang sesuai.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-[11px] uppercase font-bold text-slate-500 tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-3.5">Foto</th>
                            <th class="px-5 py-3.5">Nama Lapangan</th>
                            <th class="px-5 py-3.5">Jenis Lantai</th>
                            <th class="px-5 py-3.5">Tarif Sewa / Jam</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($fields as $field)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <div class="w-14 h-12 rounded-xl bg-slate-900 overflow-hidden flex items-center justify-center border border-slate-200">
                                        <img src="{{ $field->image ? asset('storage/' . $field->image) : asset('images/katalog.png') }}" class="w-full h-full object-cover">
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="font-bold text-slate-900 block text-sm">{{ $field->name }}</span>
                                    <span class="text-[11px] text-slate-400 line-clamp-1">{{ $field->description ?: 'Tanpa keterangan khusus.' }}</span>
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    @if($field->type === 'vinyl')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                            Vinyl
                                        </span>
                                    @elseif($field->type === 'sintetis')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Rumput Sintetis
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                            Interlock
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="font-extrabold text-slate-900 text-xs">{{ $field->formatted_price }}</span>
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    @if($field->isAvailable())
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Tersedia
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Maintenance
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap text-right space-x-1.5">
                                    <a href="{{ route('admin.fields.edit', $field->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-lg transition-colors">
                                        <i class="fa-regular fa-pen-to-square text-[11px]"></i> Edit
                                    </a>

                                    <form method="POST" action="{{ route('admin.fields.destroy', $field->id) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lapangan \'{{ $field->name }}\'?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold text-xs rounded-lg transition-colors">
                                            <i class="fa-regular fa-trash-can text-[11px]"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($fields->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $fields->withQueryString()->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection

