@extends('layouts.admin')

@section('title', 'Edit Lapangan - ' . $field->name . ' - FutsalHub')
@section('page_title', 'Edit Data Lapangan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-4">
        <a href="{{ route('admin.fields.index') }}" class="text-xs font-bold text-slate-500 hover:text-emerald-600 flex items-center gap-1.5 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Lapangan
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-xs">
        <h2 class="text-base font-extrabold text-slate-900 mb-1">Edit: {{ $field->name }}</h2>
        <p class="text-xs text-slate-500 mb-6">Perbarui detail tarif, tipe lantai, atau status operasional lapangan</p>

        <form method="POST" action="{{ route('admin.fields.update', $field->id) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Field Name -->
            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 mb-1">Nama Lapangan <span class="text-rose-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $field->name) }}" required
                    class="w-full px-4 py-2.5 text-xs bg-slate-50 border @error('name') border-rose-300 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 font-semibold text-slate-800">
                @error('name')
                    <p class="text-rose-600 text-[11px] mt-1 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Floor Type & Price -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="type" class="block text-xs font-bold text-slate-700 mb-1">Tipe Lantai <span class="text-rose-500">*</span></label>
                    <select id="type" name="type" required
                        class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border @error('type') border-rose-300 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 font-semibold text-slate-800">
                        <option value="vinyl" {{ old('type', $field->type) === 'vinyl' ? 'selected' : '' }}>Vinyl (Standar FIFA)</option>
                        <option value="sintetis" {{ old('type', $field->type) === 'sintetis' ? 'selected' : '' }}>Rumput Sintetis</option>
                        <option value="interlock" {{ old('type', $field->type) === 'interlock' ? 'selected' : '' }}>Interlock Polimer</option>
                    </select>
                    @error('type')
                        <p class="text-rose-600 text-[11px] mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price_per_hour" class="block text-xs font-bold text-slate-700 mb-1">Tarif Sewa per Jam (Rp) <span class="text-rose-500">*</span></label>
                    <input type="number" id="price_per_hour" name="price_per_hour" value="{{ old('price_per_hour', (int) $field->price_per_hour) }}" step="1000" min="10000" required
                        class="w-full px-4 py-2.5 text-xs bg-slate-50 border @error('price_per_hour') border-rose-300 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 font-semibold text-slate-800">
                    @error('price_per_hour')
                        <p class="text-rose-600 text-[11px] mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-xs font-bold text-slate-700 mb-1">Status Operasional Lapangan <span class="text-rose-500">*</span></label>
                <select id="status" name="status" required
                    class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border @error('status') border-rose-300 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 font-semibold text-slate-800">
                    <option value="available" {{ old('status', $field->status) === 'available' ? 'selected' : '' }}>Tersedia (Dapat dipesan pelanggan)</option>
                    <option value="maintenance" {{ old('status', $field->status) === 'maintenance' ? 'selected' : '' }}>Maintenance (Dalam perbaikan / ditutup)</option>
                </select>
                @error('status')
                    <p class="text-rose-600 text-[11px] mt-1 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-xs font-bold text-slate-700 mb-1">Deskripsi & Fasilitas Lapangan</label>
                <textarea id="description" name="description" rows="3"
                    class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-800">{{ old('description', $field->description) }}</textarea>
                @error('description')
                    <p class="text-rose-600 text-[11px] mt-1 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Current & New Photo -->
            <div>
                <label for="image" class="block text-xs font-bold text-slate-700 mb-1">Foto Lapangan</label>
                
                @if($field->image)
                    <div class="mb-3 flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <img src="{{ asset('storage/' . $field->image) }}" class="w-16 h-12 object-cover rounded-lg border border-slate-200">
                        <div class="text-xs">
                            <span class="font-bold text-slate-700 block">Foto Saat Ini</span>
                            <span class="text-slate-400 text-[10px]">Unggah foto baru di bawah jika ingin menggantinya</span>
                        </div>
                    </div>
                @endif

                <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 text-center hover:border-emerald-400 transition-colors bg-slate-50">
                    <input type="file" id="image" name="image" accept="image/*" class="sr-only" onchange="previewFieldImage(this)">
                    <label for="image" class="cursor-pointer">
                        <i class="fa-solid fa-cloud-arrow-up text-2xl text-slate-400 mb-1 block"></i>
                        <span class="text-xs font-bold text-slate-700 block">Ganti Foto Lapangan</span>
                        <span class="text-[10px] text-slate-400 block mt-0.5">JPG, PNG, JPEG, WEBP (Maksimal 2MB)</span>
                    </label>

                    <div id="imagePreviewBox" class="hidden mt-3">
                        <img id="imagePreview" class="h-32 mx-auto rounded-lg object-cover border border-slate-200 shadow-xs">
                        <p id="imageFileName" class="text-[10px] text-slate-500 mt-1"></p>
                    </div>
                </div>
                @error('image')
                    <p class="text-rose-600 text-[11px] mt-1 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.fields.index') }}" class="px-4 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-600/20 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-save text-xs"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function previewFieldImage(input) {
        const box = document.getElementById('imagePreviewBox');
        const img = document.getElementById('imagePreview');
        const name = document.getElementById('imageFileName');

        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                img.src = e.target.result;
                name.textContent = file.name;
                box.classList.remove('hidden');
            }

            reader.readAsDataURL(file);
        }
    }
</script>
@endpush
@endsection

