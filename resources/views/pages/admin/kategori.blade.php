@extends('layouts.admin')

@section('content')
    <div x-data="{ showCreateModal: false, showEditModal: false, editData: { id: '', nama: '' } }" class="space-y-6">

        <!-- Title & Action Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-white tracking-wide">Kategori Pakaian</h2>
                <p class="text-xs text-cyan-400 font-medium mt-0.5">Kelola jenis dan kelompok pakaian thrift.</p>
            </div>
            <button @click="showCreateModal = true"
                class="px-4 py-2.5 bg-gradient-to-r from-teal-500 to-cyan-500 text-slate-950 font-black text-xs rounded-xl shadow-lg shadow-cyan-900/40 hover:opacity-90 transition flex items-center justify-center gap-2">
                <span>➕</span> Tambah Kategori
            </button>
        </div>

        <!-- Alert Notifications -->
        @if (session('success'))
            <div
                class="p-4 bg-teal-900/60 border border-teal-500/50 rounded-xl text-teal-200 text-xs font-semibold flex justify-between items-center">
                <span>✅ {{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div
                class="p-4 bg-red-950/60 border border-red-500/50 rounded-xl text-red-200 text-xs font-semibold flex justify-between items-center">
                <span>⚠️ {{ session('error') }}</span>
            </div>
        @endif

        <!-- Search & Table Area -->
        <div class="bg-teal-900/20 backdrop-blur-md rounded-2xl border border-teal-800/50 shadow-xl overflow-hidden">

            <!-- Filter Box -->
            <div class="p-4 border-b border-teal-800/50 bg-teal-950/40 flex justify-between items-center">
                <form action="{{ route('admin.kategori.index') }}" method="GET" class="w-full sm:w-72">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kategori..."
                        class="w-full bg-slate-900 border border-teal-800/60 rounded-xl px-4 py-2 text-xs text-white placeholder-teal-600 focus:outline-none focus:border-cyan-400 transition">
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead
                        class="bg-teal-950/80 text-cyan-300 uppercase tracking-wider border-b border-teal-800/50 font-bold">
                        <tr>
                            <th class="p-4 w-16 text-center">#</th>
                            <th class="p-4">Nama Kategori</th>
                            <th class="p-4 text-center">Jumlah Pakaian</th>
                            <th class="p-4 w-36 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-teal-800/30 text-teal-100">
                        @forelse($categories as $index => $cat)
                            <tr class="hover:bg-teal-800/20 transition">
                                <td class="p-4 text-center font-bold text-teal-400/80">
                                    {{ $categories->firstItem() + $index }}</td>
                                <td class="p-4 font-bold text-white">{{ $cat->kategori_pakaian_nama }}</td>
                                <td class="p-4 text-center">
                                    <span
                                        class="px-2.5 py-1 bg-teal-950 text-cyan-300 font-bold rounded-full text-[10px] border border-teal-700/50">
                                        {{ $cat->pakaian_count }} Pakaian
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button
                                            @click="editData = { id: '{{ $cat->kategori_pakaian_id }}', nama: '{{ addslashes($cat->kategori_pakaian_nama) }}' }; showEditModal = true"
                                            class="px-2.5 py-1.5 bg-amber-500/20 text-amber-300 hover:bg-amber-500 hover:text-slate-950 border border-amber-500/30 font-bold rounded-lg transition text-[11px]">
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.kategori.destroy', $cat->kategori_pakaian_id) }}"
                                            method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-2.5 py-1.5 bg-red-500/20 text-red-300 hover:bg-red-500 hover:text-slate-950 border border-red-500/30 font-bold rounded-lg transition text-[11px]">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-6 text-center text-teal-400/60">Data kategori tidak ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Kustom Dark Teal / Cyan -->
            <div class="p-4 border-t border-teal-800/50">
                @if ($categories->hasPages())
                    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
                        <!-- Tampilan Mobile -->
                        <div class="flex justify-between flex-1 sm:hidden gap-2">
                            @if ($categories->onFirstPage())
                                <span
                                    class="px-3 py-1.5 text-xs font-semibold text-teal-800 bg-slate-900/40 border border-teal-900/50 rounded-xl cursor-not-allowed select-none">
                                    « Sebelumnya
                                </span>
                            @else
                                <a href="{{ $categories->previousPageUrl() }}"
                                    class="px-3 py-1.5 text-xs font-semibold text-cyan-300 bg-slate-900 hover:bg-teal-800/40 border border-teal-800/60 rounded-xl transition">
                                    « Sebelumnya
                                </a>
                            @endif

                            @if ($categories->hasMorePages())
                                <a href="{{ $categories->nextPageUrl() }}"
                                    class="px-3 py-1.5 text-xs font-semibold text-cyan-300 bg-slate-900 hover:bg-teal-800/40 border border-teal-800/60 rounded-xl transition">
                                    Berikutnya »
                                </a>
                            @else
                                <span
                                    class="px-3 py-1.5 text-xs font-semibold text-teal-800 bg-slate-900/40 border border-teal-900/50 rounded-xl cursor-not-allowed select-none">
                                    Berikutnya »
                                </span>
                            @endif
                        </div>

                        <!-- Tampilan Desktop -->
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-xs text-teal-400/80">
                                    Menampilkan <span class="font-bold text-white">{{ $categories->firstItem() }}</span> –
                                    <span class="font-bold text-white">{{ $categories->lastItem() }}</span> dari
                                    <span class="font-bold text-white">{{ $categories->total() }}</span> kategori
                                </p>
                            </div>

                            <div>
                                <span
                                    class="inline-flex rounded-xl shadow-sm overflow-hidden border border-teal-800/60 bg-slate-900">
                                    <!-- Tombol Prev -->
                                    @if ($categories->onFirstPage())
                                        <span
                                            class="px-3 py-2 text-xs font-bold text-teal-800 bg-slate-950/40 border-r border-teal-800/60 cursor-not-allowed select-none">
                                            ‹
                                        </span>
                                    @else
                                        <a href="{{ $categories->previousPageUrl() }}"
                                            class="px-3 py-2 text-xs font-bold text-cyan-400 hover:bg-teal-800/50 transition border-r border-teal-800/60">
                                            ‹
                                        </a>
                                    @endif

                                    <!-- Angka Halaman -->
                                    @foreach ($categories->getUrlRange(1, $categories->lastPage()) as $page => $url)
                                        @if ($page == $categories->currentPage())
                                            <span
                                                class="px-3.5 py-2 text-xs font-black text-slate-950 bg-cyan-400 border-r border-cyan-400 select-none">
                                                {{ $page }}
                                            </span>
                                        @else
                                            <a href="{{ $url }}"
                                                class="px-3.5 py-2 text-xs font-semibold text-teal-300 hover:text-white hover:bg-teal-800/50 transition border-r border-teal-800/60">
                                                {{ $page }}
                                            </a>
                                        @endif
                                    @endforeach

                                    <!-- Tombol Next -->
                                    @if ($categories->hasMorePages())
                                        <a href="{{ $categories->nextPageUrl() }}"
                                            class="px-3 py-2 text-xs font-bold text-cyan-400 hover:bg-teal-800/50 transition">
                                            ›
                                        </a>
                                    @else
                                        <span
                                            class="px-3 py-2 text-xs font-bold text-teal-800 bg-slate-950/40 cursor-not-allowed select-none">
                                            ›
                                        </span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </nav>
                @endif
            </div>
        </div>

        <!-- MODAL TAMBAH KATEGORI -->
        <div x-show="showCreateModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm" x-cloak>
            <div @click.away="showCreateModal = false"
                class="bg-teal-950 border border-teal-800/80 rounded-2xl max-w-md w-full p-6 space-y-5 shadow-2xl relative">
                <div class="flex justify-between items-center border-b border-teal-800/50 pb-3">
                    <h3 class="font-black text-white text-base">Tambah Kategori Baru</h3>
                    <button @click="showCreateModal = false" class="text-teal-400 hover:text-white font-bold">✕</button>
                </div>

                <form action="{{ route('admin.kategori.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-teal-300 mb-1">Nama Kategori</label>
                        <input type="text" name="kategori_pakaian_nama" required
                            placeholder="Contoh: Hoodie, Jaket, Celana..."
                            class="w-full bg-slate-900 border border-teal-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400">
                    </div>
                    <div class="flex gap-2 pt-2">
                        <button type="button" @click="showCreateModal = false"
                            class="w-1/2 py-2.5 bg-slate-800 text-teal-300 text-xs font-bold rounded-xl hover:bg-slate-700 transition">Batal</button>
                        <button type="submit"
                            class="w-1/2 py-2.5 bg-gradient-to-r from-teal-500 to-cyan-500 text-slate-950 text-xs font-black rounded-xl hover:opacity-90 transition">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL EDIT KATEGORI -->
        <div x-show="showEditModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm" x-cloak>
            <div @click.away="showEditModal = false"
                class="bg-teal-950 border border-teal-800/80 rounded-2xl max-w-md w-full p-6 space-y-5 shadow-2xl relative">
                <div class="flex justify-between items-center border-b border-teal-800/50 pb-3">
                    <h3 class="font-black text-white text-base">Edit Kategori</h3>
                    <button @click="showEditModal = false" class="text-teal-400 hover:text-white font-bold">✕</button>
                </div>

                <form :action="'/admin/kategori/' + editData.id" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs font-bold text-teal-300 mb-1">Nama Kategori</label>
                        <input type="text" name="kategori_pakaian_nama" x-model="editData.nama" required
                            class="w-full bg-slate-900 border border-teal-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400">
                    </div>
                    <div class="flex gap-2 pt-2">
                        <button type="button" @click="showEditModal = false"
                            class="w-1/2 py-2.5 bg-slate-800 text-teal-300 text-xs font-bold rounded-xl hover:bg-slate-700 transition">Batal</button>
                        <button type="submit"
                            class="w-1/2 py-2.5 bg-amber-500 text-slate-950 text-xs font-black rounded-xl hover:bg-amber-400 transition">Update</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
