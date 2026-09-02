@extends('layouts.user')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <h1 class="text-2xl font-black text-gray-900 border-b pb-4">Konfirmasi Checkout</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Rincian Produk -->
        <div class="md:col-span-2 space-y-4">
            <h2 class="text-base font-bold text-gray-800">Ringkasan Produk</h2>
            <div class="bg-white rounded-2xl border border-gray-200 divide-y overflow-hidden shadow-sm">
                @foreach ($cartItems as $item)
                    <div class="p-4 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-14 h-14 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0 flex items-center justify-center">
                                @if ($item->pakaian->pakaian_gambar_url)
                                    <img src="{{ filter_var($item->pakaian->pakaian_gambar_url, FILTER_VALIDATE_URL) ? $item->pakaian->pakaian_gambar_url : asset('storage/' . $item->pakaian->pakaian_gambar_url) }}"
                                         alt="{{ $item->pakaian->pakaian_nama }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-2xl">🧥</span>
                                @endif
                            </div>
                            <div>
                                <h3 class="font-bold text-sm text-gray-800">{{ $item->pakaian->pakaian_nama }}</h3>
                                <p class="text-xs text-gray-500">
                                    Rp {{ number_format((float) $item->pakaian->pakaian_harga, 0, ',', '.') }} × {{ $item->keranjang_jumlah }} pcs
                                </p>
                            </div>
                        </div>
                        <p class="font-bold text-sm text-teal-700">
                            Rp {{ number_format((float) ($item->pakaian->pakaian_harga * $item->keranjang_jumlah), 0, ',', '.') }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Form Pembayaran -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm h-fit space-y-4">
            <h2 class="text-base font-bold text-gray-800 border-b pb-2">Metode Pembayaran</h2>

            <form action="{{ route('pembelian.store') }}" method="POST" class="space-y-4">
                @csrf
                <!-- Kirim Total Harga -->
                <input type="hidden" name="total_harga" value="{{ $totalHarga }}">

                <!-- TAMBAHAN WAJIB: Kirim ID Keranjang yang Dibeli -->
                @foreach ($cartItems as $item)
                    <input type="hidden" name="selected_items[]" value="{{ $item->keranjang_id }}">
                @endforeach

                @if($metodeList->isNotEmpty())
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Pilih Metode Tersimpan</label>
                        <select name="metode_pembayaran_id"
                            class="w-full text-sm bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:border-teal-500">
                            @foreach($metodeList as $m)
                                <option value="{{ $m->metode_pembayaran_id }}">
                                    {{ $m->metode_pembayaran_jenis }} - {{ $m->metode_pembayaran_nomor }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Pilih Metode Baru</label>
                        <select name="metode_jenis" required
                            class="w-full text-sm bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:border-teal-500">
                            <option value="BCA">Transfer Bank BCA</option>
                            <option value="DANA">E-Wallet DANA</option>
                            <option value="OVO">E-Wallet OVO</option>
                            <option value="COD">Bayar di Tempat (COD)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Nomor Rekening / HP (Opsional)</label>
                        <input type="text" name="metode_nomor" placeholder="08xxxxxxxxxx"
                            class="w-full text-sm bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:border-teal-500">
                    </div>
                @endif

                <div class="border-t pt-3 flex justify-between items-center text-sm">
                    <span class="font-semibold text-gray-600">Total Tagihan:</span>
                    <span class="text-lg font-black text-teal-700">Rp {{ number_format((float) $totalHarga, 0, ',', '.') }}</span>
                </div>

                <button type="submit"
                    class="w-full py-3 bg-teal-600 hover:bg-teal-700 text-white font-bold text-sm rounded-xl transition shadow-md">
                    Buat Pesanan & Lanjut Bayar
                </button>
            </form>
        </div>
    </div>
</div>
@endsection