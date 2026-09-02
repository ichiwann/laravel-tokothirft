@extends('layouts.user')

@section('content')
    <!-- HERO SECTION -->
    <section class="relative bg-teal-900 text-white py-16 sm:py-24 overflow-hidden">
        <!-- Background Image + Dark Overlay -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/hero-bg.jpg') }}" 
                 alt="Hero Background" 
                 class="w-full h-full object-cover opacity-40"
                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1558769132-cb1aea458c5e?q=80&w=1000&auto=format&fit=crop';">
            <div class="absolute inset-0 bg-gradient-to-r from-teal-950 via-teal-900/80 to-transparent"></div>
        </div>

        <!-- Content (Di atas Background) -->
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="max-w-xl space-y-4">
                <span class="inline-block px-3 py-1 bg-teal-800/80 text-teal-100 text-xs font-semibold rounded-full uppercase tracking-wider backdrop-blur-sm border border-teal-500/30">
                    Thrift Shop Kota Malang
                </span>
                <h1 class="text-3xl sm:text-5xl font-black leading-tight drop-shadow-md">
                    Temukan Pakaian Vintage & Authentic Pilihanmu!
                </h1>
                <p class="text-teal-100 text-sm sm:text-base">
                    Koleksi fashion thrift curated kualitas terbaik di Malang. Unik, terjangkau, dan siap melengkapi gaya harianmu.
                </p>
                <div class="pt-2">
                    <a href="#katalog"
                        class="inline-block px-6 py-3 bg-white text-teal-700 font-bold text-sm rounded-full shadow-lg hover:bg-teal-50 transition transform hover:-translate-y-0.5">
                        Jelajahi Produk
                    </a>
                </div>
            </div>

            <!-- Card Kanan (Glassmorphism) -->
            <div class="w-full md:w-1/2 flex justify-center">
                <div class="w-64 h-64 sm:w-80 sm:h-80 bg-white/10 rounded-3xl backdrop-blur-md border border-white/20 flex flex-col items-center justify-center p-6 text-center shadow-2xl space-y-3">
                    <span class="text-6xl">🧥👕🧢</span>
                    <p class="text-xs font-bold text-white tracking-wide">100% Curated Quality</p>
                    <p class="text-[10px] text-teal-100">Pakaian bersih, wangi & siap pakai</p>
                </div>
            </div>
        </div>
    </section>

    <!-- KATEGORI DINAMIS -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold text-gray-900">Kategori Pakaian</h2>
            @if (request('kategori'))
                <a href="{{ route('home') }}" class="text-xs font-semibold text-teal-600 hover:underline">Reset Filter</a>
            @endif
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @forelse($categories as $kat)
                <a href="{{ route('home', ['kategori' => $kat->kategori_pakaian_id]) }}"
                    class="p-4 rounded-xl border transition text-center group {{ request('kategori') == $kat->kategori_pakaian_id ? 'border-teal-500 bg-teal-100/70' : 'border-teal-100 bg-teal-50/50 hover:bg-teal-100/50' }}">
                    <p class="text-sm font-semibold text-gray-700 group-hover:text-teal-700 mt-1">
                        {{ $kat->kategori_pakaian_nama }}
                    </p>
                </a>
            @empty
                <p class="text-xs text-gray-400 col-span-4">Belum ada kategori tersedia.</p>
            @endforelse
        </div>
    </section>

    <!-- KATALOG PRODUK DINAMIS -->
    <section id="katalog" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-black text-gray-900">Katalog Thrift Terbaru</h2>
                <p class="text-xs text-gray-500">Stok terbatas, buruan checkout!</p>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            @forelse($pakaian as $item)
                <div
                    class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition overflow-hidden flex flex-col justify-between group">
                    <div>
                        <div class="h-48 bg-gray-100 relative overflow-hidden flex items-center justify-center">
                            @if ($item->pakaian_gambar_url)
                                <img src="{{ filter_var($item->pakaian_gambar_url, FILTER_VALIDATE_URL) ? $item->pakaian_gambar_url : asset('storage/' . $item->pakaian_gambar_url) }}"
                                    alt="{{ $item->pakaian_nama }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <span class="text-4xl">🧥</span>
                            @endif
                            <span
                                class="absolute top-2 left-2 px-2 py-1 bg-teal-600 text-white text-[10px] font-bold rounded">
                                Stok: {{ $item->pakaian_stok }}
                            </span>
                        </div>
                        <div class="p-4 space-y-2">
                            <p class="text-xs text-teal-600 font-semibold">
                                {{ $item->kategori->kategori_pakaian_nama ?? 'Uncategorized' }}
                            </p>
                            <h3 class="font-bold text-gray-800 text-sm truncate" title="{{ $item->pakaian_nama }}">
                                {{ $item->pakaian_nama }}
                            </h3>
                            <p class="text-base font-black text-teal-700">
                                Rp {{ number_format((float) $item->pakaian_harga, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    <div class="p-4 pt-0">
                        <form action="{{ route('keranjang.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="pakaian_id" value="{{ $item->pakaian_id }}">
                            <button type="submit"
                                class="w-full py-2 bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold rounded-xl transition">
                                + Keranjang
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center text-gray-400">
                    <p class="text-base font-semibold">Belum ada produk pakaian yang tersedia.</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection
