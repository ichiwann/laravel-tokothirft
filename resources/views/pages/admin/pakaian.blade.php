@extends('layouts.admin')

@section('content')
    <div x-data="{
        showCreateModal: false,
        showEditModal: false,
        createImageType: 'file',
        editImageType: 'file',
        editData: { id: '', nama: '', kategori_id: '', harga: '', stok: '', gambar_url: '' }
    }" class="space-y-6">

        <!-- Header Page -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-white tracking-wide">Data Pakaian Thrift</h2>
                <p class="text-xs text-cyan-400 font-medium mt-0.5">Kelola seluruh stok dan catalog barang pakaian.</p>
            </div>
            <button @click="showCreateModal = true"
                class="px-4 py-2.5 bg-gradient-to-r from-teal-500 to-cyan-500 text-slate-950 font-black text-xs rounded-xl shadow-lg shadow-cyan-900/40 hover:opacity-90 transition flex items-center justify-center gap-2">
                <span>➕</span> Tambah Pakaian
            </button>
        </div>

        <!-- Alert Notifications -->
        @if (session('success'))
            <div class="p-4 bg-teal-900/60 border border-teal-500/50 rounded-xl text-teal-200 text-xs font-semibold">
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- Tambahkan blok ini untuk menampilkan pesan error/gagal hapus --}}
        @if (session('error'))
            <div class="p-4 bg-red-900/60 border border-red-500/50 rounded-xl text-red-200 text-xs font-semibold">
                ❌ {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 bg-red-950/60 border border-red-500/50 rounded-xl text-red-200 text-xs font-semibold">
                ⚠️ Terdapat kesalahan input, silakan periksa kembali formulir.
            </div>
        @endif

        <!-- Table Container -->
        <div class="bg-teal-900/20 backdrop-blur-md rounded-2xl border border-teal-800/50 shadow-xl overflow-hidden">

            <!-- Filter & Search -->
            <div
                class="p-4 border-b border-teal-800/50 bg-teal-950/40 flex flex-col sm:flex-row gap-3 justify-between items-center">
                <form action="{{ route('admin.pakaian.index') }}" method="GET"
                    class="flex flex-wrap gap-2 w-full sm:w-auto">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pakaian..."
                        class="bg-slate-900 border border-teal-800/60 rounded-xl px-4 py-2 text-xs text-white placeholder-teal-600 focus:outline-none focus:border-cyan-400 transition w-full sm:w-60">

                    <select name="kategori" onchange="this.form.submit()"
                        class="bg-slate-900 border border-teal-800/60 rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->kategori_pakaian_id }}"
                                {{ request('kategori') == $cat->kategori_pakaian_id ? 'selected' : '' }}>
                                {{ $cat->kategori_pakaian_nama }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead
                        class="bg-teal-950/80 text-cyan-300 uppercase tracking-wider border-b border-teal-800/50 font-bold">
                        <tr>
                            <th class="p-4 w-16 text-center">Gambar</th>
                            <th class="p-4">Nama Pakaian</th>
                            <th class="p-4">Kategori</th>
                            <th class="p-4">Harga</th>
                            <th class="p-4 text-center">Stok</th>
                            <th class="p-4 w-36 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-teal-800/30 text-teal-100">
                        @forelse($pakaian as $item)
                            @php
                                $imgSrc = filter_var($item->pakaian_gambar_url, FILTER_VALIDATE_URL)
                                    ? $item->pakaian_gambar_url
                                    : ($item->pakaian_gambar_url
                                        ? asset('storage/' . $item->pakaian_gambar_url)
                                        : null);
                            @endphp
                            <tr class="hover:bg-teal-800/20 transition">
                                <td class="p-4 text-center">
                                    <div
                                        class="w-12 h-12 bg-slate-900 rounded-xl border border-teal-800 overflow-hidden flex items-center justify-center mx-auto">
                                        @if ($imgSrc)
                                            <img src="{{ $imgSrc }}" alt="{{ $item->pakaian_nama }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <span class="text-xl">🧥</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-4 font-bold text-white">{{ $item->pakaian_nama }}</td>
                                <td class="p-4">
                                    <span
                                        class="px-2.5 py-1 bg-teal-950 text-cyan-300 font-bold rounded-full text-[10px] border border-teal-700/50">
                                        {{ $item->kategori->kategori_pakaian_nama ?? '-' }}
                                    </span>
                                </td>
                                <td class="p-4 font-black text-cyan-400">Rp
                                    {{ number_format((float) $item->pakaian_harga, 0, ',', '.') }}</td>
                                <td class="p-4 text-center font-bold text-emerald-400">{{ $item->pakaian_stok }} pcs</td>
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button
                                            @click="editData = { 
                                        id: '{{ $item->pakaian_id }}', 
                                        nama: '{{ addslashes($item->pakaian_nama) }}', 
                                        kategori_id: '{{ $item->pakaian_kategori_pakaian_id }}', 
                                        harga: '{{ $item->pakaian_harga }}', 
                                        stok: '{{ $item->pakaian_stok }}', 
                                        gambar_url: '{{ $item->pakaian_gambar_url }}' 
                                    }; showEditModal = true"
                                            class="px-2.5 py-1.5 bg-amber-500/20 text-amber-300 hover:bg-amber-500 hover:text-slate-950 border border-amber-500/30 font-bold rounded-lg transition text-[11px]">
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.pakaian.destroy', $item->pakaian_id) }}"
                                            method="POST" onsubmit="return confirm('Yakin ingin menghapus pakaian ini?')">
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
                                <td colspan="6" class="p-6 text-center text-teal-400/60">Data pakaian tidak ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Kustom Dark Teal / Cyan -->
            <div class="p-4 border-t border-teal-800/50">
                @if ($pakaian->hasPages())
                    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
                        <!-- Tampilan Mobile -->
                        <div class="flex justify-between flex-1 sm:hidden gap-2">
                            @if ($pakaian->onFirstPage())
                                <span
                                    class="px-3 py-1.5 text-xs font-semibold text-teal-800 bg-slate-900/40 border border-teal-900/50 rounded-xl cursor-not-allowed select-none">
                                    « Sebelumnya
                                </span>
                            @else
                                <a href="{{ $pakaian->previousPageUrl() }}"
                                    class="px-3 py-1.5 text-xs font-semibold text-cyan-300 bg-slate-900 hover:bg-teal-800/40 border border-teal-800/60 rounded-xl transition">
                                    « Sebelumnya
                                </a>
                            @endif

                            @if ($pakaian->hasMorePages())
                                <a href="{{ $pakaian->nextPageUrl() }}"
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
                                    Menampilkan <span class="font-bold text-white">{{ $pakaian->firstItem() }}</span> –
                                    <span class="font-bold text-white">{{ $pakaian->lastItem() }}</span> dari
                                    <span class="font-bold text-white">{{ $pakaian->total() }}</span> pakaian
                                </p>
                            </div>

                            <div>
                                <span
                                    class="inline-flex rounded-xl shadow-sm overflow-hidden border border-teal-800/60 bg-slate-900">
                                    <!-- Tombol Prev -->
                                    @if ($pakaian->onFirstPage())
                                        <span
                                            class="px-3 py-2 text-xs font-bold text-teal-800 bg-slate-950/40 border-r border-teal-800/60 cursor-not-allowed select-none">
                                            ‹
                                        </span>
                                    @else
                                        <a href="{{ $pakaian->previousPageUrl() }}"
                                            class="px-3 py-2 text-xs font-bold text-cyan-400 hover:bg-teal-800/50 transition border-r border-teal-800/60">
                                            ‹
                                        </a>
                                    @endif

                                    <!-- Angka Halaman -->
                                    @foreach ($pakaian->getUrlRange(1, $pakaian->lastPage()) as $page => $url)
                                        @if ($page == $pakaian->currentPage())
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
                                    @if ($pakaian->hasMorePages())
                                        <a href="{{ $pakaian->nextPageUrl() }}"
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

        <!-- MODAL TAMBAH PAKAIAN -->
        <div x-show="showCreateModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm" x-cloak>
            <div @click.away="showCreateModal = false"
                class="bg-teal-950 border border-teal-800/80 rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-2xl relative">
                <div class="flex justify-between items-center border-b border-teal-800/50 pb-3">
                    <h3 class="font-black text-white text-base">Tambah Pakaian Baru</h3>
                    <button @click="showCreateModal = false" class="text-teal-400 hover:text-white font-bold">✕</button>
                </div>

                <form action="{{ route('admin.pakaian.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-teal-300 mb-1">Nama Pakaian</label>
                        <input type="text" name="pakaian_nama" required placeholder="Contoh: Jaket Vintage Nike Blue"
                            class="w-full bg-slate-900 border border-teal-800 rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-teal-300 mb-1">Kategori</label>
                            <select name="pakaian_kategori_pakaian_id" required
                                class="w-full bg-slate-900 border border-teal-800 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->kategori_pakaian_id }}">{{ $cat->kategori_pakaian_nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-teal-300 mb-1">Stok Pakaian</label>
                            <input type="number" name="pakaian_stok" required min="0" placeholder="1"
                                class="w-full bg-slate-900 border border-teal-800 rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-teal-300 mb-1">Harga (Rp)</label>
                        <input type="number" name="pakaian_harga" required min="0" placeholder="150000"
                            class="w-full bg-slate-900 border border-teal-800 rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                    </div>

                    <!-- DUA PILIHAN GAMBAR -->
                    <div class="space-y-2 pt-1">
                        <label class="block text-xs font-bold text-teal-300">Sumber Gambar</label>
                        <div class="flex gap-2">
                            <button type="button" @click="createImageType = 'file'"
                                :class="createImageType === 'file' ? 'bg-teal-600 text-white font-bold' :
                                    'bg-slate-900 text-teal-400 hover:bg-slate-800'"
                                class="px-3 py-1.5 rounded-lg text-xs transition">
                                📁 Upload File
                            </button>
                            <button type="button" @click="createImageType = 'url'"
                                :class="createImageType === 'url' ? 'bg-teal-600 text-white font-bold' :
                                    'bg-slate-900 text-teal-400 hover:bg-slate-800'"
                                class="px-3 py-1.5 rounded-lg text-xs transition">
                                🔗 Link URL
                            </button>
                        </div>

                        <!-- Input File -->
                        <div x-show="createImageType === 'file'">
                            <input type="file" name="pakaian_gambar" accept="image/*"
                                class="w-full bg-slate-900 border border-teal-800 rounded-xl px-3 py-1.5 text-xs text-teal-300 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-teal-800 file:text-white hover:file:bg-teal-700">
                        </div>

                        <!-- Input URL -->
                        <div x-show="createImageType === 'url'">
                            <input type="url" name="pakaian_gambar_url_input"
                                placeholder="https://example.com/gambar.jpg"
                                class="w-full bg-slate-900 border border-teal-800 rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                        </div>
                    </div>

                    <div class="flex gap-2 pt-3">
                        <button type="button" @click="showCreateModal = false"
                            class="w-1/2 py-2.5 bg-slate-800 text-teal-300 text-xs font-bold rounded-xl hover:bg-slate-700 transition">Batal</button>
                        <button type="submit"
                            class="w-1/2 py-2.5 bg-gradient-to-r from-teal-500 to-cyan-500 text-slate-950 text-xs font-black rounded-xl hover:opacity-90 transition">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL EDIT PAKAIAN -->
        <div x-show="showEditModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm" x-cloak>
            <div @click.away="showEditModal = false"
                class="bg-teal-950 border border-teal-800/80 rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-2xl relative">
                <div class="flex justify-between items-center border-b border-teal-800/50 pb-3">
                    <h3 class="font-black text-white text-base">Edit Pakaian</h3>
                    <button @click="showEditModal = false" class="text-teal-400 hover:text-white font-bold">✕</button>
                </div>

                <form :action="'/admin/pakaian/' + editData.id" method="POST" enctype="multipart/form-data"
                    class="space-y-3">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold text-teal-300 mb-1">Nama Pakaian</label>
                        <input type="text" name="pakaian_nama" x-model="editData.nama" required
                            class="w-full bg-slate-900 border border-teal-800 rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-teal-300 mb-1">Kategori</label>
                            <select name="pakaian_kategori_pakaian_id" x-model="editData.kategori_id" required
                                class="w-full bg-slate-900 border border-teal-800 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->kategori_pakaian_id }}">{{ $cat->kategori_pakaian_nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-teal-300 mb-1">Stok Pakaian</label>
                            <input type="number" name="pakaian_stok" x-model="editData.stok" required min="0"
                                class="w-full bg-slate-900 border border-teal-800 rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-teal-300 mb-1">Harga (Rp)</label>
                        <input type="number" name="pakaian_harga" x-model="editData.harga" required min="0"
                            class="w-full bg-slate-900 border border-teal-800 rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                    </div>

                    <!-- DUA PILIHAN GAMBAR UNTUK EDIT -->
                    <div class="space-y-2 pt-1">
                        <label class="block text-xs font-bold text-teal-300">Ganti Gambar (Opsional)</label>
                        <div class="flex gap-2">
                            <button type="button" @click="editImageType = 'file'"
                                :class="editImageType === 'file' ? 'bg-teal-600 text-white font-bold' :
                                    'bg-slate-900 text-teal-400 hover:bg-slate-800'"
                                class="px-3 py-1.5 rounded-lg text-xs transition">
                                📁 Upload File
                            </button>
                            <button type="button" @click="editImageType = 'url'"
                                :class="editImageType === 'url' ? 'bg-teal-600 text-white font-bold' :
                                    'bg-slate-900 text-teal-400 hover:bg-slate-800'"
                                class="px-3 py-1.5 rounded-lg text-xs transition">
                                🔗 Link URL
                            </button>
                        </div>

                        <!-- Input File -->
                        <div x-show="editImageType === 'file'">
                            <input type="file" name="pakaian_gambar" accept="image/*"
                                class="w-full bg-slate-900 border border-teal-800 rounded-xl px-3 py-1.5 text-xs text-teal-300 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-teal-800 file:text-white hover:file:bg-teal-700">
                        </div>

                        <!-- Input URL -->
                        <div x-show="editImageType === 'url'">
                            <input type="url" name="pakaian_gambar_url_input"
                                placeholder="https://example.com/gambar.jpg"
                                class="w-full bg-slate-900 border border-teal-800 rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                        </div>
                    </div>

                    <div class="flex gap-2 pt-3">
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
