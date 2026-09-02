@extends('layouts.user')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8 space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Pengaturan Akun</h2>
            <p class="text-xs text-gray-500 font-medium mt-1">Kelola data profil dan informasi personal kamu.</p>
        </div>
        <div>
            <span class="inline-flex items-center px-3 py-1 text-xs font-bold rounded-full bg-teal-50 text-teal-700 border border-teal-200/60">
                Role: {{ Auth::user()->user_level }}
            </span>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs font-semibold flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Form Edit Profile -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Preview & Upload Foto Profil -->
            <div class="flex flex-col sm:flex-row items-center gap-6 pb-6 border-b border-gray-100">
                <div class="relative">
                    @if($user->user_profil_url && $user->user_profil_url !== 'url_placeholder_profil')
                        <img src="{{ filter_var($user->user_profil_url, FILTER_VALIDATE_URL) ? $user->user_profil_url : asset('storage/' . $user->user_profil_url) }}" 
                             alt="Foto Profil" 
                             class="w-24 h-24 rounded-full object-cover border-4 border-teal-50 shadow-md">
                    @else
                        <!-- Inisial jika belum ada foto -->
                        <div class="w-24 h-24 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center font-black text-2xl border-4 border-teal-50 shadow-md">
                            {{ strtoupper(substr($user->user_fullname ?? $user->user_username, 0, 2)) }}
                        </div>
                    @endif
                </div>

                <div class="flex-1 w-full">
                    <label class="block text-xs font-bold text-gray-700 mb-2">Unggah Foto Profil Baru</label>
                    <input type="file" name="user_profil_url" accept="image/*"
                        class="block w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 cursor-pointer border border-gray-200 rounded-xl p-1 bg-gray-50 transition">
                    <p class="text-[10px] text-gray-400 mt-1.5">Format yang didukung: JPG, PNG, WEBP. Maksimal 2MB.</p>
                    @error('user_profil_url') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="user_fullname" value="{{ old('user_fullname', $user->user_fullname) }}" required
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-xs text-gray-800 focus:bg-white focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition">
                    @error('user_fullname') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Username -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-2">Username</label>
                    <input type="text" name="user_username" value="{{ old('user_username', $user->user_username) }}" required
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-xs text-gray-800 focus:bg-white focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition">
                    @error('user_username') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-2">Email</label>
                    <input type="email" name="user_email" value="{{ old('user_email', $user->user_email) }}" required
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-xs text-gray-800 focus:bg-white focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition">
                    @error('user_email') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Nomor HP -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-2">Nomor HP / WhatsApp</label>
                    <input type="text" name="user_nohp" value="{{ old('user_nohp', $user->user_nohp) }}" required
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-xs text-gray-800 focus:bg-white focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition">
                    @error('user_nohp') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Alamat -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-2">Alamat Lengkap</label>
                <textarea name="user_alamat" rows="3" required
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl p-4 text-xs text-gray-800 focus:bg-white focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition">{{ old('user_alamat', $user->user_alamat) }}</textarea>
                @error('user_alamat') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Button Submit -->
            <div class="flex justify-end pt-4 border-t border-gray-100">
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-teal-600 to-cyan-600 hover:from-teal-700 hover:to-cyan-700 text-white font-bold text-xs rounded-full shadow-sm transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection