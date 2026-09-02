@extends('layouts.user')

@section('content')
<div class="max-w-xl mx-auto px-4 py-8 space-y-6">
    <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm space-y-6 text-center">
        <div class="w-12 h-12 bg-teal-100 text-teal-600 rounded-full flex items-center justify-center mx-auto text-xl font-bold">
            💳
        </div>
        <div>
            <h1 class="text-xl font-black text-gray-900">Instruksi Pembayaran</h1>
            <p class="text-xs text-gray-500 mt-1">Selesaikan pembayaran Anda untuk memproses pesanan.</p>
        </div>

        <!-- Total Tagihan -->
        <div class="bg-teal-50 border border-teal-100 p-4 rounded-xl">
            <span class="text-xs text-teal-700 font-semibold block">Total Tagihan</span>
            <span class="text-2xl font-black text-teal-800">
                Rp {{ number_format((float)$pembelian->pembelian_total_harga, 0, ',', '.') }}
            </span>
        </div>

        <!-- Detail Metode Pembayaran -->
        <div class="bg-gray-50 p-4 rounded-xl text-left space-y-2 text-xs border border-gray-100">
            <p class="font-bold text-gray-700">Transfer Ke / Metode:</p>
            <div class="flex justify-between items-center border-t pt-2">
                <span class="text-gray-500">Tujuan:</span>
                <span class="font-mono font-bold text-gray-900 text-sm">
                    {{ $pembelian->metodePembayaran->metode_pembayaran_jenis ?? 'BCA' }} 
                    @if(isset($pembelian->metodePembayaran->metode_pembayaran_nomor))
                        - {{ $pembelian->metodePembayaran->metode_pembayaran_nomor }}
                    @endif
                </span>
            </div>
            <p class="text-[11px] text-gray-400">a.n. Thrift Malang Official</p>
        </div>

        <!-- Tombol Aksi Pembayaran -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
            <!-- Tombol 1: Belum Bayar / Bayar Nanti -->
            <a href="{{ route('pembelian.index') }}" 
               class="w-full py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-xl transition text-center flex items-center justify-center">
                Belum Bayar (Bayar Nanti)
            </a>

            <!-- Tombol 2: Saya Sudah Bayar -->
            <a href="{{ route('pembelian.index') }}" 
               class="w-full py-3 bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs rounded-xl transition text-center shadow-md flex items-center justify-center">
                Saya Sudah Bayar
            </a>
        </div>
    </div>
</div>
@endsection