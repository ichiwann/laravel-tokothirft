@extends('layouts.user')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        <!-- Header & Filter -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b pb-6">
            <div>
                <h1 class="text-2xl font-black text-gray-900">Katalog Pakaian Thrift</h1>
                <p class="text-sm text-gray-500">Cari dan temukan pakaian favoritmu dari koleksi pilihan Malang</p>
            </div>

            <form action="{{ route('pakaian.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pakaian..."
                    class="text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 focus:outline-none focus:border-teal-500">

                <select name="kategori" onchange="this.form.submit()"
                    class="text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 focus:outline-none focus:border-teal-500">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->kategori_pakaian_id }}"
                            {{ request('kategori') == $cat->kategori_pakaian_id ? 'selected' : '' }}>
                            {{ $cat->kategori_pakaian_nama }}
                        </option>
                    @endforeach
                </select>

                <button type="submit"
                    class="px-4 py-2 bg-teal-600 text-white font-bold text-sm rounded-xl hover:bg-teal-700 transition">Cari</button>
            </form>
        </div>

        <!-- Alert Notifikasi Berhasil -->
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        <!-- Product Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            @forelse($pakaian as $item)
                <div
                    class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition overflow-hidden flex flex-col justify-between group">
                    <div>
                        <div class="h-52 bg-gray-100 relative overflow-hidden flex items-center justify-center">
                            @if ($item->pakaian_gambar_url)
                                <img src="{{ filter_var($item->pakaian_gambar_url, FILTER_VALIDATE_URL) ? $item->pakaian_gambar_url : asset('storage/' . $item->pakaian_gambar_url) }}"
                                    alt="{{ $item->pakaian_nama }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <span class="text-5xl">🧥</span>
                            @endif
                            <span
                                class="absolute top-2 left-2 px-2 py-1 bg-teal-600 text-white text-[10px] font-bold rounded">
                                Stok: {{ $item->pakaian_stok }}
                            </span>
                        </div>
                        <div class="p-4 space-y-1">
                            <p class="text-xs text-teal-600 font-semibold">
                                {{ $item->kategori->kategori_pakaian_nama ?? 'Uncategorized' }}</p>
                            <h3 class="font-bold text-gray-800 text-sm truncate" title="{{ $item->pakaian_nama }}">
                                {{ $item->pakaian_nama }}
                            </h3>
                            <p class="text-base font-black text-teal-700">
                                Rp {{ number_format((float) $item->pakaian_harga, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    
                    <!-- Tombol Tambah ke Keranjang Langsung -->
                    <div class="p-4 pt-0">
                        <form action="{{ route('keranjang.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="pakaian_id" value="{{ $item->pakaian_id }}">
                            <button type="submit"
                                class="w-full py-2 bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold rounded-xl transition shadow-sm">
                                + Keranjang
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center text-gray-400">
                    <p class="text-lg font-semibold">Pakaian tidak ditemukan.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="pt-4">
            {{ $pakaian->links() }}
        </div>

    </div>
@endsection