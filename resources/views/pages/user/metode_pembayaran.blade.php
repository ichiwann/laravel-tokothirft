@extends('layouts.user')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <h1 class="text-2xl font-black text-gray-900 border-b pb-4">Kelola Metode Pembayaran</h1>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Form Tambah -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4 h-fit">
            <h2 class="text-base font-bold text-gray-800 border-b pb-2">Tambah Baru</h2>
            <form action="{{ route('metode.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Jenis Pembayaran</label>
                    <select name="metode_pembayaran_jenis" required
                        class="w-full text-sm bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:border-teal-500">
                        <option value="BCA">Bank BCA</option>
                        <option value="DANA">DANA</option>
                        <option value="OVO">OVO</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nomor Rekening / HP</label>
                    <input type="text" name="metode_pembayaran_nomor" placeholder="08xxxxxxxxxx" required
                        class="w-full text-sm bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:border-teal-500">
                </div>

                <button type="submit"
                    class="w-full py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs rounded-xl transition">
                    + Simpan Metode
                </button>
            </form>
        </div>

        <!-- Daftar Metode Pembayaran User -->
        <div class="md:col-span-2 space-y-4">
            <h2 class="text-base font-bold text-gray-800">Metode Pembayaran Tersimpan</h2>
            <div class="bg-white rounded-2xl border border-gray-200 divide-y overflow-hidden shadow-sm">
                @forelse ($metodeList as $item)
                    <div x-data="{ editOpen: false }" class="p-4 space-y-3">
                        <!-- Tampilan Data -->
                        <div class="flex items-center justify-between" x-show="!editOpen">
                            <div>
                                <span class="px-2 py-0.5 bg-teal-100 text-teal-800 text-[10px] font-bold rounded-full">
                                    {{ $item->metode_pembayaran_jenis }}
                                </span>
                                <p class="font-bold text-sm text-gray-800 mt-1">
                                    {{ $item->metode_pembayaran_nomor }}
                                </p>
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="button" @click="editOpen = true" class="text-xs text-teal-600 hover:underline font-semibold">
                                    Edit
                                </button>
                                <form action="{{ route('metode.destroy', $item->metode_pembayaran_id) }}" method="POST" onsubmit="return confirm('Hapus metode ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:underline font-semibold">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Form Edit Inline -->
                        <form x-show="editOpen" action="{{ route('metode.update', $item->metode_pembayaran_id) }}" method="POST" class="space-y-3 bg-gray-50 p-3 rounded-xl border border-gray-200" x-cloak>
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-600 mb-1">Jenis Pembayaran</label>
                                    <select name="metode_pembayaran_jenis" required
                                        class="w-full text-xs bg-white border border-gray-200 rounded-lg px-2.5 py-1.5 focus:outline-none focus:border-teal-500">
                                        <option value="BCA" {{ $item->metode_pembayaran_jenis == 'BCA' ? 'selected' : '' }}>Bank BCA</option>
                                        <option value="DANA" {{ $item->metode_pembayaran_jenis == 'DANA' ? 'selected' : '' }}>DANA</option>
                                        <option value="OVO" {{ $item->metode_pembayaran_jenis == 'OVO' ? 'selected' : '' }}>OVO</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-600 mb-1">Nomor Rekening / HP</label>
                                    <input type="text" name="metode_pembayaran_nomor" value="{{ $item->metode_pembayaran_nomor }}" required
                                        class="w-full text-xs bg-white border border-gray-200 rounded-lg px-2.5 py-1.5 focus:outline-none focus:border-teal-500">
                                </div>
                            </div>

                            <div class="flex justify-end gap-2 pt-1">
                                <button type="button" @click="editOpen = false" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-semibold rounded-lg transition">
                                    Batal
                                </button>
                                <button type="submit" class="px-3 py-1 bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold rounded-lg transition">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-400 text-xs">
                        Belum ada metode pembayaran tersimpan.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection