@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.app')

@section('title', 'Edit Profil Akun - FutsalHub')
@section('page_title', 'Pengaturan Profil Akun')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Pengaturan Profil</h1>
        <p class="text-xs text-slate-500 mt-1">Perbarui nama lengkap, nomor telepon, foto profil, dan kata sandi akun Anda</p>
    </div>

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Card 1: Biodata & Foto Profil -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-xs">
            <h2 class="text-base font-extrabold text-slate-900 mb-6 flex items-center gap-2">
                <i class="fa-regular fa-id-badge text-emerald-600"></i> Informasi Data Diri
            </h2>

            <!-- Avatar Preview & Upload -->
            <div class="flex flex-col sm:flex-row items-center gap-6 pb-6 mb-6 border-b border-slate-100">
                <div class="relative group">
                    <img id="avatarPreview" src="{{ $user->avatar_url }}" alt="{{ $user->name }}" 
                        class="w-24 h-24 rounded-full object-cover border-4 border-emerald-100 shadow-md">
                    <label for="avatar" class="absolute inset-0 rounded-full bg-slate-900/40 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                        <i class="fa-solid fa-camera text-lg"></i>
                    </label>
                </div>

                <div class="text-center sm:text-left">
                    <label for="avatar" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs rounded-xl border border-emerald-200 cursor-pointer transition-colors shadow-xs">
                        <i class="fa-solid fa-upload"></i> Unggah Foto Baru
                    </label>
                    <input type="file" id="avatar" name="avatar" accept="image/*" class="sr-only" onchange="previewAvatar(this)">
                    <p class="text-[11px] text-slate-400 mt-1.5">Format: JPG, JPEG, PNG. Maksimal 2 MB.</p>
                    @error('avatar')
                        <p class="text-rose-600 text-xs mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full px-4 py-2.5 text-xs bg-slate-50 border @error('name') border-rose-300 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 font-semibold text-slate-800">
                    @error('name')
                        <p class="text-rose-600 text-[11px] mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-xs font-bold text-slate-700 mb-1">Nomor WhatsApp / HP <span class="text-rose-500">*</span></label>
                    <div class="relative flex items-center">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-emerald-600">
                            <i class="fa-brands fa-whatsapp text-sm"></i>
                        </span>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required
                            placeholder="+62 812-3456-7890"
                            class="w-full pl-10 pr-4 py-2.5 text-xs bg-slate-50 border @error('phone') border-rose-300 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 font-semibold text-slate-800">
                    </div>
                    <span class="text-[10px] text-slate-400 mt-1 block">Format standar: +62 8xx-xxxx-xxxx</span>
                    @error('phone')
                        <p class="text-rose-600 text-[11px] mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email (Read-Only) -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Alamat Email (Identitas Login)</label>
                    <input type="email" value="{{ $user->email }}" disabled
                        class="w-full px-4 py-2.5 text-xs bg-slate-100 border border-slate-200 rounded-xl text-slate-500 cursor-not-allowed">
                    <span class="text-[10px] text-slate-400 mt-0.5 block">Email digunakan sebagai pengenal akun dan tidak dapat diubah sembarangan.</span>
                </div>

                <!-- Role -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Hak Akses (Role)</label>
                    <input type="text" value="{{ ucfirst($user->role) }}" disabled
                        class="w-full px-4 py-2.5 text-xs bg-slate-100 border border-slate-200 rounded-xl text-slate-500 cursor-not-allowed font-bold capitalize">
                </div>
            </div>
        </div>

        <!-- Card 2: Keamanan & Ganti Password -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-xs">
            <h2 class="text-base font-extrabold text-slate-900 mb-2 flex items-center gap-2">
                <i class="fa-solid fa-lock text-emerald-600"></i> Ganti Kata Sandi (Opsional)
            </h2>
            <p class="text-xs text-slate-500 mb-6">Biarkan kolom password kosong jika Anda tidak ingin mengubah kata sandi Anda saat ini.</p>

            <div class="space-y-4">
                <div>
                    <label for="current_password" class="block text-xs font-bold text-slate-700 mb-1">Password Saat Ini</label>
                    <input type="password" id="current_password" name="current_password"
                        placeholder="Ketik password lama Anda"
                        class="w-full px-4 py-2.5 text-xs bg-slate-50 border @error('current_password') border-rose-300 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                    @error('current_password')
                        <p class="text-rose-600 text-[11px] mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 mb-1">Password Baru</label>
                        <input type="password" id="password" name="password"
                            placeholder="Minimal 6 karakter"
                            class="w-full px-4 py-2.5 text-xs bg-slate-50 border @error('password') border-rose-300 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                        @error('password')
                            <p class="text-rose-600 text-[11px] mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1">Konfirmasi Password Baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            placeholder="Ketik ulang password baru"
                            class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Action Bar: Hapus Akun (Kiri) & Simpan Perubahan (Kanan) -->
        <div class="flex flex-col-reverse sm:flex-row sm:items-center justify-between gap-3 pt-3">
            <div>
                @if(!$user->isAdmin())
                    <button type="button" onclick="openDeleteAccountModal()" 
                        class="w-full sm:w-auto justify-center px-4 py-2.5 bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white font-extrabold text-xs rounded-xl border border-rose-200 hover:border-rose-600 transition-all flex items-center gap-2 cursor-pointer shadow-xs">
                        <i class="fa-regular fa-trash-can"></i>
                        <span>Hapus Akun Saya</span>
                    </button>
                @endif
            </div>

            <button type="submit" class="w-full sm:w-auto justify-center px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-md shadow-emerald-600/30 transition-all flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Simpan Pembaruan Profil</span>
            </button>
        </div>
    </form>

    <!-- Card Sesi & Logout (Mudah diakses di Layar HP / Mobile) -->
    <div class="mt-6 bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-right-from-bracket text-rose-500"></i> Sesi Akun & Keluar
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">Keluar dari akun Anda di perangkat ini untuk menjaga keamanan.</p>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
            @csrf
            <button type="submit" class="w-full sm:w-auto flex items-center justify-center gap-2 px-5 py-2.5 text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-600 hover:text-white rounded-xl transition-all border border-rose-200 hover:border-rose-600 cursor-pointer shadow-xs">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Log out</span>
            </button>
        </form>
    </div>

    @if(!$user->isAdmin())
        <!-- Modal Konfirmasi Hapus Akun -->
        <div id="deleteAccountModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs hidden flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 sm:p-7 shadow-2xl border border-slate-200 animate-in fade-in zoom-in-95 duration-150">
                <div class="text-center">
                    <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-4 border border-rose-100">
                        <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
                    </div>

                    <h3 class="text-lg font-black text-slate-900 mb-2">Hapus Akun Permanen</h3>

                    <p class="text-xs text-slate-600 leading-relaxed text-justify sm:text-center">
                        Setelah akun Anda dihapus, seluruh data login Anda akan dihapus secara permanen dari sistem. Anda harus mendaftar akun baru jika ingin melakukan reservasi lapangan kembali. Seluruh catatan riwayat pemesanan yang pernah Anda lakukan akan tetap tersimpan dan diarsipkan di sistem admin.
                    </p>

                    <div class="mt-4 p-3 rounded-xl bg-rose-50/80 border border-rose-200 text-center">
                        <p class="text-[11px] font-bold text-rose-700 flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-circle-exclamation text-xs"></i>
                            <span>Tindakan ini tidak dapat dibatalkan setelah dikonfirmasi.</span>
                        </p>
                    </div>
                </div>

                <!-- 2 Pilihan Tombol: Batal & Konfirmasi -->
                <div class="mt-6 flex items-center gap-3">
                    <button type="button" onclick="closeDeleteAccountModal()" 
                        class="flex-1 py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-colors cursor-pointer text-center">
                        Batal
                    </button>

                    <form method="POST" action="{{ route('profile.destroy') }}" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                            class="w-full py-2.5 px-4 bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-xs rounded-xl shadow-md shadow-rose-600/30 transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-trash-can text-[11px]"></i>
                            <span>Konfirmasi</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarPreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function openDeleteAccountModal() {
        const modal = document.getElementById('deleteAccountModal');
        if (modal) modal.classList.remove('hidden');
    }

    function closeDeleteAccountModal() {
        const modal = document.getElementById('deleteAccountModal');
        if (modal) modal.classList.add('hidden');
    }

    // Tutup modal jika klik di luar area dialog
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('deleteAccountModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeDeleteAccountModal();
                }
            });
        }
    });
</script>
@endpush
@endsection

