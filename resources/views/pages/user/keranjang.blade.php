@extends('layouts.user')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <h1 class="text-2xl font-black text-gray-900 border-b pb-4">Keranjang Belanja</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Daftar Barang di Keranjang -->
        <div class="lg:col-span-2 space-y-4">
            @if($cartItems->count() > 0)
                <!-- Select All Bar -->
                <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm flex items-center justify-between">
                    <label class="flex items-center gap-3 cursor-pointer text-xs font-bold text-gray-700 select-none">
                        <input type="checkbox" id="select-all" onclick="toggleSelectAll(this)" class="w-4 h-4 text-teal-600 rounded border-gray-300 focus:ring-teal-500 cursor-pointer">
                        <span>Pilih Semua ({{ $cartItems->count() }})</span>
                    </label>
                </div>
            @endif

            <form id="checkout-form" action="{{ route('pembelian.checkout') }}" method="GET" onsubmit="return validateCheckout(event)">
                <div class="space-y-4">
                    @forelse($cartItems as $item)
                        <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm flex items-center justify-between gap-4">
                            <!-- Checkbox Select Item -->
                            <div class="flex items-center pr-2">
                                <input type="checkbox" 
                                       name="selected_items[]" 
                                       value="{{ $item->keranjang_id }}" 
                                       id="checkbox-{{ $item->keranjang_id }}"
                                       data-price="{{ $item->pakaian->pakaian_harga }}"
                                       data-jumlah="{{ $item->keranjang_jumlah }}"
                                       class="item-checkbox w-4 h-4 text-teal-600 rounded border-gray-300 focus:ring-teal-500 cursor-pointer" 
                                       onchange="recalculateTotal()">
                            </div>

                            <!-- Gambar & Info Produk -->
                            <div class="flex items-center gap-4 flex-1">
                                <div class="w-20 h-20 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0 flex items-center justify-center">
                                    @if($item->pakaian->pakaian_gambar_url)
                                        <img src="{{ filter_var($item->pakaian->pakaian_gambar_url, FILTER_VALIDATE_URL) ? $item->pakaian->pakaian_gambar_url : asset('storage/' . $item->pakaian->pakaian_gambar_url) }}" 
                                             alt="{{ $item->pakaian->pakaian_nama }}" 
                                             class="w-full h-full object-cover">
                                    @else
                                        <span class="text-3xl">🧥</span>
                                    @endif
                                </div>
                                <div class="space-y-1.5">
                                    <span class="text-[10px] bg-teal-50 text-teal-700 font-bold px-2 py-0.5 rounded-full">
                                        {{ $item->pakaian->kategori->kategori_pakaian_nama ?? 'Thrift' }}
                                    </span>
                                    <h3 class="font-bold text-gray-800 text-sm">{{ $item->pakaian->pakaian_nama }}</h3>
                                    <p class="text-xs text-teal-600 font-black">
                                        Rp {{ number_format((float)$item->pakaian->pakaian_harga, 0, ',', '.') }}
                                    </p>

                                    <!-- Kontrol Jumlah Tanpa Refresh -->
                                    <div class="flex items-center gap-1 pt-1">
                                        <button type="button" 
                                                onclick="updateJumlah({{ $item->keranjang_id }}, -1)"
                                                id="btn-minus-{{ $item->keranjang_id }}"
                                                class="w-7 h-7 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-lg flex items-center justify-center text-xs transition disabled:opacity-40"
                                                {{ $item->keranjang_jumlah <= 1 ? 'disabled' : '' }}>
                                            -
                                        </button>

                                        <input type="number" 
                                               id="input-jumlah-{{ $item->keranjang_id }}" 
                                               value="{{ $item->keranjang_jumlah }}" 
                                               min="1" 
                                               onchange="updateJumlahDirect({{ $item->keranjang_id }}, this.value)"
                                               class="w-12 text-center text-xs font-bold border border-gray-200 rounded-lg py-1 focus:outline-none focus:border-teal-500">

                                        <button type="button" 
                                                onclick="updateJumlah({{ $item->keranjang_id }}, 1)"
                                                class="w-7 h-7 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-lg flex items-center justify-center text-xs transition">
                                            +
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Hapus & Total Per Item -->
                            <div class="text-right space-y-2">
                                <p class="font-black text-gray-900 text-sm" id="item-subtotal-{{ $item->keranjang_id }}">
                                    Rp {{ number_format((float)($item->pakaian->pakaian_harga * $item->keranjang_jumlah), 0, ',', '.') }}
                                </p>
                                <button type="button" 
                                        onclick="hapusItem({{ $item->keranjang_id }})" 
                                        class="text-xs text-red-500 hover:text-red-700 font-semibold transition">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center text-gray-400">
                            <span class="text-4xl block mb-2">🛒</span>
                            <p class="text-sm font-semibold mb-4">Keranjang belanjaanmu masih kosong.</p>
                            <a href="{{ route('home') }}" class="inline-block px-5 py-2 bg-teal-600 text-white font-bold text-xs rounded-xl hover:bg-teal-700 transition">
                                Mulai Belanja
                            </a>
                        </div>
                    @endforelse
                </div>
            </form>

            <!-- Form Hapus Terpisah -->
            <form id="delete-form" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        </div>

        <!-- Ringkasan Pesanan & Checkout -->
        @if($cartItems->count() > 0)
            <div class="h-fit bg-white border border-gray-100 rounded-2xl p-6 shadow-sm space-y-4">
                <h2 class="text-sm font-bold text-gray-900 border-b pb-3">Ringkasan Pesanan</h2>
                
                <div class="flex items-center justify-between text-xs text-gray-600">
                    <span>Barang Terpilih</span>
                    <span class="font-bold text-gray-800" id="grand-total-pcs">0 Pcs</span>
                </div>

                <div class="flex items-center justify-between text-xs pt-2 border-t">
                    <span class="font-bold text-gray-700">Total Harga</span>
                    <span class="font-black text-teal-700 text-base" id="grand-total-price">
                        Rp 0
                    </span>
                </div>

                <button type="button" 
                        onclick="submitCheckout()" 
                        id="btn-checkout"
                        disabled
                        class="w-full py-3 bg-teal-600 hover:bg-teal-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-bold text-xs rounded-xl shadow-md transition">
                    Lanjut ke Checkout
                </button>
            </div>
        @endif
    </div>
</div>

<script>
// Format angka ke Rupiah
function formatRupiah(number) {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
}

// Rekalkulasi total barang dan total harga khusus checkbox terpilih
function recalculateTotal() {
    const checkboxes = document.querySelectorAll('.item-checkbox:checked');
    let totalPcs = 0;
    let totalPrice = 0;

    checkboxes.forEach(cb => {
        const price = parseFloat(cb.getAttribute('data-price')) || 0;
        const jumlah = parseInt(cb.getAttribute('data-jumlah')) || 0;
        
        totalPcs += jumlah;
        totalPrice += (price * jumlah);
    });

    // Update UI Ringkasan
    document.getElementById('grand-total-pcs').innerText = `${totalPcs} Pcs`;
    document.getElementById('grand-total-price').innerText = formatRupiah(totalPrice);

    // Toggle status tombol checkout
    const btnCheckout = document.getElementById('btn-checkout');
    if (btnCheckout) {
        btnCheckout.disabled = (checkboxes.length === 0);
    }

    // Update status checkbox "Pilih Semua"
    const allCheckboxes = document.querySelectorAll('.item-checkbox');
    const selectAllCb = document.getElementById('select-all');
    if (selectAllCb && allCheckboxes.length > 0) {
        selectAllCb.checked = (checkboxes.length === allCheckboxes.length);
    }
}

// Fungsi Check All
function toggleSelectAll(master) {
    const checkboxes = document.querySelectorAll('.item-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = master.checked;
    });
    recalculateTotal();
}

// Handle Tambah/Kurang Jumlah via AJAX
function updateJumlah(id, change) {
    const inputEl = document.getElementById(`input-jumlah-${id}`);
    let newJumlah = parseInt(inputEl.value) + change;
    if (newJumlah < 1) return;
    
    sendAjaxUpdate(id, newJumlah);
}

function updateJumlahDirect(id, value) {
    let newJumlah = parseInt(value);
    if (isNaN(newJumlah) || newJumlah < 1) newJumlah = 1;

    sendAjaxUpdate(id, newJumlah);
}

function sendAjaxUpdate(id, jumlah) {
    fetch(`/keranjang/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ jumlah: jumlah })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById(`input-jumlah-${id}`).value = data.jumlah;
            document.getElementById(`item-subtotal-${id}`).innerText = data.item_total;
            
            // Sync data attribute pada checkbox
            const checkbox = document.getElementById(`checkbox-${id}`);
            if (checkbox) {
                checkbox.setAttribute('data-jumlah', data.jumlah);
            }

            // Atur status tombol minus (disabled jika <= 1)
            const btnMinus = document.getElementById(`btn-minus-${id}`);
            if (btnMinus) {
                btnMinus.disabled = (data.jumlah <= 1);
            }

            // Hitung ulang total ringkasan pesanan
            recalculateTotal();
        }
    })
    .catch(error => console.error('Error:', error));
}

// Handle Form Hapus Item
function hapusItem(id) {
    if (confirm('Hapus barang ini dari keranjang?')) {
        const deleteForm = document.getElementById('delete-form');
        deleteForm.action = `/keranjang/${id}`;
        deleteForm.submit();
    }
}

// Submit Checkout Form
function submitCheckout() {
    const form = document.getElementById('checkout-form');
    const checked = document.querySelectorAll('.item-checkbox:checked');
    if (checked.length === 0) {
        alert('Pilih minimal satu barang untuk di-checkout!');
        return;
    }
    form.submit();
}
</script>
@endsection