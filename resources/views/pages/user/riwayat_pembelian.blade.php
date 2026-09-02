@extends('layouts.user')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        <div class="flex items-center justify-between border-b pb-4">
            <h1 class="text-2xl font-black text-gray-900">Riwayat Pembelian</h1>
            <!-- Menggunakan total() untuk menampilkan keseluruhan total transaksi -->
            <span class="text-xs text-gray-500 font-medium">Total: {{ $pembelianList->total() }} Transaksi</span>
        </div>

        @if (session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-4">
            @forelse ($pembelianList as $item)
                @php
                    $status = strtolower($item->pembelian_status);
                    $details = $item->pembelianDetail ?? ($item->details ?? collect());
                    $totalBayar = $item->pembelian_total_harga;
                @endphp

                <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm space-y-4 transition hover:shadow-md">
                    <!-- Header Card Transaksi -->
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-gray-100 pb-3">
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold text-gray-500">
                                {{ \Carbon\Carbon::parse($item->pembelian_tanggal)->format('d M Y, H:i') }}
                            </span>
                            <span class="text-xs font-mono font-semibold text-gray-400">
                                #TRX-{{ $item->pembelian_id }}
                            </span>
                        </div>

                        <!-- Badge Status -->
                        @if ($status == 'menunggu_konfirmasi')
                            <span
                                class="px-3 py-1 bg-amber-50 border border-amber-200 text-amber-700 text-xs font-bold rounded-full flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                Menunggu Konfirmasi
                            </span>
                        @elseif ($status == 'dibayar')
                            <span
                                class="px-3 py-1 bg-blue-50 border border-blue-200 text-blue-700 text-xs font-bold rounded-full">
                                💳 Pembayaran Diterima
                            </span>
                        @elseif ($status == 'diproses')
                            <span
                                class="px-3 py-1 bg-indigo-50 border border-indigo-200 text-indigo-700 text-xs font-bold rounded-full">
                                📦 Pesanan Diproses
                            </span>
                        @elseif ($status == 'dikirim')
                            <span
                                class="px-3 py-1 bg-purple-50 border border-purple-200 text-purple-700 text-xs font-bold rounded-full">
                                🚚 Dalam Pengiriman
                            </span>
                        @elseif ($status == 'selesai')
                            <span
                                class="px-3 py-1 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold rounded-full">
                                ✅ Selesai
                            </span>
                        @else
                            <span
                                class="px-3 py-1 bg-red-50 border border-red-200 text-red-700 text-xs font-bold rounded-full">
                                ❌ Dibatalkan
                            </span>
                        @endif
                    </div>

                    <!-- Detail Barang -->
                    <div class="space-y-3">
                        @foreach ($details as $detail)
                            @php
                                $pakaian = $detail->pakaian;
                                $namaProduk = $pakaian->pakaian_nama ?? 'Produk Thrift';
                                $fotoProduk = $pakaian->pakaian_gambar ?? ($pakaian->pakaian_gambar_url ?? null);

                                $urlGambar = null;
                                if (!empty($fotoProduk)) {
                                    if (\Illuminate\Support\Str::startsWith($fotoProduk, ['http://', 'https://'])) {
                                        $urlGambar = $fotoProduk;
                                    } else {
                                        $cleanPath = str_replace(['public/', 'storage/'], '', $fotoProduk);
                                        $urlGambar = asset('storage/' . ltrim($cleanPath, '/'));
                                    }
                                }

                                $jumlahBarang =
                                    $detail->detail_pembelian_jumlah ?? ($detail->pembelian_detail_jumlah ?? 1);
                                $subtotalDetail =
                                    $detail->detail_pembelian_subtotal ??
                                    ($detail->pembelian_detail_total_harga ?? $pakaian->pakaian_harga * $jumlahBarang);
                                $hargaSatuan =
                                    $pakaian->pakaian_harga ??
                                    ($jumlahBarang > 0 ? $subtotalDetail / $jumlahBarang : 0);
                            @endphp

                            <div class="flex items-center gap-4 border-b border-gray-50 pb-3 last:border-0 last:pb-0">
                                <div
                                    class="w-16 h-16 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0 border border-gray-200 flex items-center justify-center">
                                    @if ($urlGambar)
                                        <img src="{{ $urlGambar }}" alt="{{ $namaProduk }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <span class="text-xl">👕</span>
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-bold text-gray-800 truncate">{{ $namaProduk }}</h3>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        {{ $jumlahBarang }} barang x Rp {{ number_format($hargaSatuan, 0, ',', '.') }}
                                    </p>
                                </div>

                                <div class="text-right">
                                    <span class="text-xs font-bold text-gray-700">
                                        Rp {{ number_format($subtotalDetail, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Footer Transaksi -->
                    <div class="pt-3 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="text-xs text-teal-700 font-semibold">
                                Metode: {{ $item->metodePembayaran->metode_pembayaran_jenis ?? 'Transfer Bank' }}
                            </p>
                            @if ($status == 'menunggu_konfirmasi')
                                <p class="text-[11px] text-gray-400 italic mt-0.5">
                                    Admin sedang memverifikasi pembayaran Anda.
                                </p>
                            @endif
                        </div>

                        <div class="flex items-center gap-4 text-right">
                            <div>
                                <span class="block text-[10px] font-semibold text-gray-400 uppercase">Total Bayar</span>
                                <span class="text-base font-black text-teal-600">
                                    Rp {{ number_format($totalBayar, 0, ',', '.') }}
                                </span>
                            </div>

                            @if ($status == 'menunggu_konfirmasi')
                                <a href="{{ route('pembayaran.show', $item->pembelian_id) }}"
                                    class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold rounded-xl transition shadow-sm flex items-center gap-1.5">
                                    💳 Bayar Sekarang
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center space-y-3 shadow-sm">
                    <div class="text-4xl">🛍️</div>
                    <h3 class="text-base font-bold text-gray-800">Belum ada riwayat pembelian</h3>
                    <p class="text-xs text-gray-500">Anda belum melakukan transaksi apapun saat ini.</p>
                    <a href="{{ route('pakaian.index') }}"
                        class="inline-block px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold rounded-full transition">
                        Mulai Belanja
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Tombol / Navigasi Pagination -->
        <div class="pt-4">
            <!-- Tombol / Navigasi Pagination Kustom -->
            @if ($pembelianList->hasPages())
                <div class="pt-6 border-t border-gray-200">
                    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
                        <!-- Tampilan Mobile -->
                        <div class="flex justify-between flex-1 sm:hidden gap-2">
                            @if ($pembelianList->onFirstPage())
                                <span
                                    class="px-4 py-2 text-xs font-semibold text-gray-400 bg-gray-100 rounded-xl cursor-not-allowed">
                                    « Sebelumnya
                                </span>
                            @else
                                <a href="{{ $pembelianList->previousPageUrl() }}"
                                    class="px-4 py-2 text-xs font-semibold text-teal-700 bg-teal-50 hover:bg-teal-100 border border-teal-200 rounded-xl transition">
                                    « Sebelumnya
                                </a>
                            @endif

                            @if ($pembelianList->hasMorePages())
                                <a href="{{ $pembelianList->nextPageUrl() }}"
                                    class="px-4 py-2 text-xs font-semibold text-teal-700 bg-teal-50 hover:bg-teal-100 border border-teal-200 rounded-xl transition">
                                    Berikutnya »
                                </a>
                            @else
                                <span
                                    class="px-4 py-2 text-xs font-semibold text-gray-400 bg-gray-100 rounded-xl cursor-not-allowed">
                                    Berikutnya »
                                </span>
                            @endif
                        </div>

                        <!-- Tampilan Desktop -->
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-xs text-gray-500">
                                    Menampilkan <span
                                        class="font-bold text-gray-800">{{ $pembelianList->firstItem() }}</span> –
                                    <span class="font-bold text-gray-800">{{ $pembelianList->lastItem() }}</span> dari
                                    <span class="font-bold text-gray-800">{{ $pembelianList->total() }}</span> transaksi
                                </p>
                            </div>

                            <div>
                                <span
                                    class="inline-flex rounded-xl shadow-sm overflow-hidden border border-gray-200 bg-white">
                                    <!-- Tombol Prev -->
                                    @if ($pembelianList->onFirstPage())
                                        <span
                                            class="px-3 py-2 text-xs font-bold text-gray-300 bg-gray-50 cursor-not-allowed select-none">
                                            ‹
                                        </span>
                                    @else
                                        <a href="{{ $pembelianList->previousPageUrl() }}"
                                            class="px-3 py-2 text-xs font-bold text-teal-600 hover:bg-teal-50 transition border-r border-gray-200">
                                            ‹
                                        </a>
                                    @endif

                                    <!-- Angka Halaman -->
                                    @foreach ($pembelianList->getUrlRange(1, $pembelianList->lastPage()) as $page => $url)
                                        @if ($page == $pembelianList->currentPage())
                                            <span
                                                class="px-3.5 py-2 text-xs font-black text-white bg-teal-600 border-r border-teal-600 select-none">
                                                {{ $page }}
                                            </span>
                                        @else
                                            <a href="{{ $url }}"
                                                class="px-3.5 py-2 text-xs font-semibold text-gray-600 hover:text-teal-600 hover:bg-teal-50 transition border-r border-gray-200">
                                                {{ $page }}
                                            </a>
                                        @endif
                                    @endforeach

                                    <!-- Tombol Next -->
                                    @if ($pembelianList->hasMorePages())
                                        <a href="{{ $pembelianList->nextPageUrl() }}"
                                            class="px-3 py-2 text-xs font-bold text-teal-600 hover:bg-teal-50 transition">
                                            ›
                                        </a>
                                    @else
                                        <span
                                            class="px-3 py-2 text-xs font-bold text-gray-300 bg-gray-50 cursor-not-allowed select-none">
                                            ›
                                        </span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </nav>
                </div>
            @endif
        </div>
    </div>
@endsection
