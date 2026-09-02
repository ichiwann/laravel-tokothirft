@extends('layouts.admin')

@section('content')
    <div x-data="{
        showDetailModal: false,
        showStatusModal: false,
        selectedData: { id: '', kode: '', pembeli: '', email: '', nohp: '', tanggal: '', total: '', status: '', items: [] },
        getImageUrl(path) {
            if (!path) return '';
            if (path.startsWith('http://') || path.startsWith('https://')) return path;
            return '/storage/' + path.replace(/^(public\/|storage\/)/, '');
        }
    }" class="space-y-6">

        <!-- Header Page -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-white tracking-wide">Data Pembelian & Transaksi</h2>
                <p class="text-xs text-cyan-400 font-medium mt-0.5">Kelola riwayat pesanan, konfirmasi pembayaran, dan status
                    pengiriman.</p>
            </div>
        </div>

        <!-- Alert Notifications -->
        @if (session('success'))
            <div class="p-4 bg-teal-900/60 border border-teal-500/50 rounded-xl text-teal-200 text-xs font-semibold">
                ✅ {{ session('success') }}
            </div>
        @endif

        <!-- Table Container -->
        <div class="bg-teal-900/20 backdrop-blur-md rounded-2xl border border-teal-800/50 shadow-xl overflow-hidden">

            <!-- Filter & Search -->
            <div
                class="p-4 border-b border-teal-800/50 bg-teal-950/40 flex flex-col sm:flex-row gap-3 justify-between items-center">
                <form action="{{ route('admin.pembelian.index') }}" method="GET"
                    class="flex flex-wrap gap-2 w-full sm:w-auto">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari ID transaksi / nama pembeli..."
                        class="bg-slate-900 border border-teal-800/60 rounded-xl px-4 py-2 text-xs text-white placeholder-teal-600 focus:outline-none focus:border-cyan-400 transition w-full sm:w-60">

                    <select name="status" onchange="this.form.submit()"
                        class="bg-slate-900 border border-teal-800/60 rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                        <option value="">Semua Status</option>
                        <option value="menunggu_konfirmasi"
                            {{ request('status') == 'menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                        <option value="dibayar" {{ request('status') == 'dibayar' ? 'selected' : '' }}>Dibayar</option>
                        <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="dikirim" {{ request('status') == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan
                        </option>
                    </select>
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead
                        class="bg-teal-950/80 text-cyan-300 uppercase tracking-wider border-b border-teal-800/50 font-bold">
                        <tr>
                            <th class="p-4">Kode Transaksi</th>
                            <th class="p-4">Pembeli</th>
                            <th class="p-4">Tanggal</th>
                            <th class="p-4">Total Harga</th>
                            <th class="p-4 text-center">Status</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-teal-800/30 text-teal-100">
                        @forelse($pembelian as $item)
                            @php
                                $st = strtolower($item->pembelian_status ?? 'menunggu_konfirmasi');
                                $details = $item->pembelianDetail ?? ($item->detailPembelian ?? collect());
                                $tanggalFormated = $item->pembelian_tanggal
                                    ? \Carbon\Carbon::parse($item->pembelian_tanggal)->format('d M Y H:i')
                                    : ($item->created_at
                                        ? $item->created_at->format('d M Y H:i')
                                        : '-');

                                $namaPembeli =
                                    $item->user->user_fullname ??
                                    ($item->user->user_username ?? ($item->pembelian_nama_pembeli ?? 'Pelanggan'));

                                $emailPembeli = $item->user->user_email ?? ($item->user->email ?? '-');

                                $noHpPembeli =
                                    $item->user->user_nohp ??
                                    ($item->user->no_hp ?? ($item->user->telepon ?? ($item->user->phone ?? '-')));
                            @endphp
                            <tr class="hover:bg-teal-800/20 transition">
                                <td class="p-4 font-mono font-bold text-cyan-400">#TRX-{{ $item->pembelian_id }}</td>
                                <td class="p-4">
                                    <div class="font-bold text-white">{{ $namaPembeli }}</div>
                                    <div class="text-[10px] text-teal-400/80">{{ $emailPembeli }}</div>
                                </td>
                                <td class="p-4 text-teal-300/80">{{ $tanggalFormated }}</td>
                                <td class="p-4 font-black text-emerald-400">Rp
                                    {{ number_format((float) ($item->pembelian_total_harga ?? 0), 0, ',', '.') }}</td>
                                <td class="p-4 text-center">
                                    @php
                                        $badge =
                                            [
                                                'menunggu_konfirmasi' =>
                                                    'bg-amber-500/20 text-amber-300 border-amber-500/30',
                                                'dibayar' => 'bg-blue-500/20 text-blue-300 border-blue-500/30',
                                                'diproses' => 'bg-indigo-500/20 text-indigo-300 border-indigo-500/30',
                                                'dikirim' => 'bg-purple-500/20 text-purple-300 border-purple-500/30',
                                                'selesai' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
                                                'dibatalkan' => 'bg-red-500/20 text-red-300 border-red-500/30',
                                            ][$st] ?? 'bg-slate-800 text-teal-300 border-teal-700/50';

                                        $statusLabel = str_replace('_', ' ', $st);
                                    @endphp
                                    <span
                                        class="px-2.5 py-1 font-bold rounded-full text-[10px] border uppercase tracking-wider {{ $badge }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <!-- Detail Button -->
                                        <button
                                            @click="selectedData = {
                                        id: '{{ $item->pembelian_id }}',
                                        kode: 'TRX-{{ $item->pembelian_id }}',
                                        pembeli: '{{ addslashes($namaPembeli) }}',
                                        email: '{{ addslashes($emailPembeli) }}',
                                        nohp: '{{ addslashes($noHpPembeli) }}',
                                        tanggal: '{{ $tanggalFormated }}',
                                        total: '{{ number_format((float) ($item->pembelian_total_harga ?? 0), 0, ',', '.') }}',
                                        status: '{{ $st }}',
                                        items: {{ json_encode($details) }}
                                    }; showDetailModal = true"
                                            class="px-2.5 py-1 bg-cyan-500/20 text-cyan-300 hover:bg-cyan-500 hover:text-slate-950 border border-cyan-500/30 font-bold rounded-lg transition text-[11px]">
                                            Detail
                                        </button>

                                        <!-- Status Button -->
                                        <button
                                            @click="selectedData = {
                                        id: '{{ $item->pembelian_id }}',
                                        status: '{{ $st }}'
                                    }; showStatusModal = true"
                                            class="px-2.5 py-1 bg-amber-500/20 text-amber-300 hover:bg-amber-500 hover:text-slate-950 border border-amber-500/30 font-bold rounded-lg transition text-[11px]">
                                            Status
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-6 text-center text-teal-400/60">Data transaksi pembelian belum
                                    ada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Kustom Dark Teal -->
            <div class="p-4 border-t border-teal-800/50">
                @if ($pembelian->hasPages())
                    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
                        <!-- Tampilan Mobile -->
                        <div class="flex justify-between flex-1 sm:hidden gap-2">
                            @if ($pembelian->onFirstPage())
                                <span
                                    class="px-3 py-1.5 text-xs font-semibold text-teal-800 bg-slate-900/40 border border-teal-900/50 rounded-xl cursor-not-allowed select-none">
                                    « Sebelumnya
                                </span>
                            @else
                                <a href="{{ $pembelian->previousPageUrl() }}"
                                    class="px-3 py-1.5 text-xs font-semibold text-cyan-300 bg-slate-900 hover:bg-teal-800/40 border border-teal-800/60 rounded-xl transition">
                                    « Sebelumnya
                                </a>
                            @endif

                            @if ($pembelian->hasMorePages())
                                <a href="{{ $pembelian->nextPageUrl() }}"
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
                                    Menampilkan <span class="font-bold text-white">{{ $pembelian->firstItem() }}</span> –
                                    <span class="font-bold text-white">{{ $pembelian->lastItem() }}</span> dari
                                    <span class="font-bold text-white">{{ $pembelian->total() }}</span> transaksi
                                </p>
                            </div>

                            <div>
                                <span
                                    class="inline-flex rounded-xl shadow-sm overflow-hidden border border-teal-800/60 bg-slate-900">
                                    <!-- Tombol Prev -->
                                    @if ($pembelian->onFirstPage())
                                        <span
                                            class="px-3 py-2 text-xs font-bold text-teal-800 bg-slate-950/40 border-r border-teal-800/60 cursor-not-allowed select-none">
                                            ‹
                                        </span>
                                    @else
                                        <a href="{{ $pembelian->previousPageUrl() }}"
                                            class="px-3 py-2 text-xs font-bold text-cyan-400 hover:bg-teal-800/50 transition border-r border-teal-800/60">
                                            ‹
                                        </a>
                                    @endif

                                    <!-- Angka Halaman -->
                                    @foreach ($pembelian->getUrlRange(1, $pembelian->lastPage()) as $page => $url)
                                        @if ($page == $pembelian->currentPage())
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
                                    @if ($pembelian->hasMorePages())
                                        <a href="{{ $pembelian->nextPageUrl() }}"
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

        <!-- MODAL DETAIL TRANSAKSI -->
        <div x-show="showDetailModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm" x-cloak>
            <div @click.away="showDetailModal = false"
                class="bg-teal-950 border border-teal-800/80 rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-2xl relative">
                <div class="flex justify-between items-center border-b border-teal-800/50 pb-3">
                    <div>
                        <h3 class="font-black text-white text-base">Detail Transaksi</h3>
                        <p class="text-xs text-cyan-400 font-mono" x-text="'#' + selectedData.kode"></p>
                    </div>
                    <button @click="showDetailModal = false" class="text-teal-400 hover:text-white font-bold">✕</button>
                </div>

                <!-- Buyer Info -->
                <div class="bg-slate-900/80 border border-teal-800/50 p-3 rounded-xl space-y-1.5 text-xs">
                    <div class="flex justify-between"><span class="text-teal-400">Pembeli:</span> <span
                            class="text-white font-bold" x-text="selectedData.pembeli"></span></div>
                    <div class="flex justify-between"><span class="text-teal-400">Email:</span> <span class="text-teal-200"
                            x-text="selectedData.email"></span></div>
                    <div class="flex justify-between"><span class="text-teal-400">No HP:</span> <span
                            class="text-cyan-300 font-mono" x-text="selectedData.nohp"></span></div>
                    <div class="flex justify-between"><span class="text-teal-400">Tanggal:</span> <span
                            class="text-teal-200" x-text="selectedData.tanggal"></span></div>
                </div>

                <!-- Items List Dengan Thumbnail Gambar -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-teal-300">Item Dipesan</label>
                    <div class="max-h-56 overflow-y-auto space-y-2 pr-1">
                        <template x-for="item in selectedData.items" :key="item.pembelian_detail_id || Math.random()">
                            <div
                                class="flex justify-between items-center bg-slate-900/60 p-2.5 rounded-xl border border-teal-800/40 text-xs gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <!-- Frame Gambar Produk -->
                                    <div
                                        class="w-12 h-12 bg-slate-800 rounded-lg overflow-hidden flex-shrink-0 border border-teal-700/50 flex items-center justify-center">
                                        <template
                                            x-if="item.pakaian && (item.pakaian.pakaian_gambar_url || item.pakaian.pakaian_gambar)">
                                            <img :src="getImageUrl(item.pakaian.pakaian_gambar_url || item.pakaian.pakaian_gambar)"
                                                :alt="item.pakaian.pakaian_nama" class="w-full h-full object-cover">
                                        </template>
                                        <template
                                            x-if="!item.pakaian || (!item.pakaian.pakaian_gambar_url && !item.pakaian.pakaian_gambar)">
                                            <span class="text-lg">👕</span>
                                        </template>
                                    </div>

                                    <!-- Detail Nama & Jumlah -->
                                    <div class="min-w-0">
                                        <p class="font-bold text-white truncate"
                                            x-text="item.pakaian ? item.pakaian.pakaian_nama : 'Produk Thrift'"></p>
                                        <p class="text-[10px] text-teal-400 mt-0.5"
                                            x-text="(item.pembelian_detail_jumlah || 1) + ' pcs'"></p>
                                    </div>
                                </div>

                                <span class="font-bold text-emerald-400 flex-shrink-0"
                                    x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(item.pembelian_detail_total_harga || 0)"></span>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Total -->
                <div class="flex justify-between items-center border-t border-teal-800/50 pt-3">
                    <span class="text-xs font-bold text-teal-300">Total Pembayaran</span>
                    <span class="text-lg font-black text-emerald-400" x-text="'Rp ' + selectedData.total"></span>
                </div>

                <button type="button" @click="showDetailModal = false"
                    class="w-full py-2.5 bg-slate-800 text-teal-300 text-xs font-bold rounded-xl hover:bg-slate-700 transition">Tutup</button>
            </div>
        </div>

        <!-- MODAL UPDATE STATUS -->
        <div x-show="showStatusModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm" x-cloak>
            <div @click.away="showStatusModal = false"
                class="bg-teal-950 border border-teal-800/80 rounded-2xl max-w-sm w-full p-6 space-y-4 shadow-2xl relative">
                <div class="flex justify-between items-center border-b border-teal-800/50 pb-3">
                    <h3 class="font-black text-white text-base">Ubah Status Transaksi</h3>
                    <button @click="showStatusModal = false" class="text-teal-400 hover:text-white font-bold">✕</button>
                </div>

                <form :action="'/admin/pembelian/' + selectedData.id + '/status'" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold text-teal-300 mb-1">Status Pembelian</label>
                        <select name="pembelian_status" x-model="selectedData.status" required
                            class="w-full bg-slate-900 border border-teal-800 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                            <option value="menunggu_konfirmasi">Menunggu Konfirmasi</option>
                            <option value="dibayar">Dibayar</option>
                            <option value="diproses">Diproses</option>
                            <option value="dikirim">Dikirim</option>
                            <option value="selesai">Selesai</option>
                            <option value="dibatalkan">Dibatalkan</option>
                        </select>
                    </div>

                    <div class="flex gap-2 pt-2">
                        <button type="button" @click="showStatusModal = false"
                            class="w-1/2 py-2.5 bg-slate-800 text-teal-300 text-xs font-bold rounded-xl hover:bg-slate-700 transition">Batal</button>
                        <button type="submit"
                            class="w-1/2 py-2.5 bg-amber-500 text-slate-950 text-xs font-black rounded-xl hover:bg-amber-400 transition">Update
                            Status</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
