@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    
    <!-- Title Section -->
    <div>
        <h2 class="text-2xl font-black text-white tracking-wide">Ringkasan Toko</h2>
        <p class="text-xs text-cyan-400 font-medium mt-0.5">Pantau statistik stok dan barang thrift terbaru di Kota Malang.</p>
    </div>

    <!-- STATS CARDS (Tema Dark Teal & Cyan) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        
        <!-- Card Total Pakaian -->
        <div class="bg-teal-900/30 backdrop-blur-md p-5 rounded-2xl border border-teal-800/60 shadow-lg shadow-teal-950/50 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-teal-400 uppercase tracking-wider">Total Pakaian</p>
                <h3 class="text-3xl font-black text-white mt-1">{{ $totalPakaian }}</h3>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-cyan-500 text-slate-950 rounded-2xl flex items-center justify-center text-xl font-bold shadow-lg shadow-teal-500/20">
                🧥
            </div>
        </div>

        <!-- Card Total Kategori -->
        <div class="bg-teal-900/30 backdrop-blur-md p-5 rounded-2xl border border-teal-800/60 shadow-lg shadow-teal-950/50 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-cyan-400 uppercase tracking-wider">Total Kategori</p>
                <h3 class="text-3xl font-black text-white mt-1">{{ $totalKategori }}</h3>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-cyan-400 to-teal-500 text-slate-950 rounded-2xl flex items-center justify-center text-xl font-bold shadow-lg shadow-cyan-500/20">
                🏷️
            </div>
        </div>

        <!-- Card Total Stok Pakaian -->
        <div class="bg-teal-900/30 backdrop-blur-md p-5 rounded-2xl border border-teal-800/60 shadow-lg shadow-teal-950/50 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-emerald-400 uppercase tracking-wider">Total Stok Produk</p>
                <h3 class="text-3xl font-black text-white mt-1">{{ $totalStok ?? 0 }}</h3>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-teal-500 text-slate-950 rounded-2xl flex items-center justify-center text-xl font-bold shadow-lg shadow-emerald-500/20">
                📦
            </div>
        </div>

    </div>

    <!-- TABLE PRODUK TERBARU -->
    <div class="bg-teal-900/20 backdrop-blur-md rounded-2xl border border-teal-800/50 shadow-xl overflow-hidden">
        <div class="p-5 border-b border-teal-800/50 bg-teal-950/40 flex justify-between items-center">
            <div>
                <h3 class="font-bold text-white text-base">Pakaian Terbaru Ditambahkan</h3>
                <p class="text-xs text-teal-400/80">5 data barang thrift paling baru</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-teal-950/80 text-cyan-300 uppercase tracking-wider border-b border-teal-800/50 font-bold">
                    <tr>
                        <th class="p-4">Nama Pakaian</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4">Harga</th>
                        <th class="p-4">Stok</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-teal-800/30 text-teal-100">
                    @forelse($latestPakaian as $p)
                        <tr class="hover:bg-teal-800/20 transition">
                            <td class="p-4 font-bold text-white">{{ $p->pakaian_nama }}</td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 bg-teal-950 text-cyan-300 font-bold rounded-full text-[10px] border border-teal-700/50">
                                    {{ $p->kategori->kategori_pakaian_nama ?? '-' }}
                                </span>
                            </td>
                            <td class="p-4 font-black text-cyan-400">Rp {{ number_format((float)$p->pakaian_harga, 0, ',', '.') }}</td>
                            <td class="p-4 font-bold text-emerald-400">{{ $p->pakaian_stok }} pcs</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-teal-400/60">Belum ada data pakaian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection