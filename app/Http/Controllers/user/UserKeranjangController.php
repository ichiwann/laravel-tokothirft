<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Keranjang;
use Illuminate\Support\Facades\Auth;

class UserKeranjangController extends Controller
{
    public function index()
    {
        $cartItems = Keranjang::with('pakaian')
            ->where('keranjang_user_id', Auth::user()->user_id)
            ->latest()
            ->get();

        return view('pages.user.keranjang', compact('cartItems'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $request->validate([
            'pakaian_id' => ['required', 'exists:pakaian,pakaian_id'],
            'jumlah'     => ['nullable', 'integer', 'min:1'],
        ]);

        $jumlah = $request->input('jumlah', 1);
        $userId = Auth::user()->user_id;

        $existingCart = Keranjang::where('keranjang_user_id', $userId)
            ->where('keranjang_pakaian_id', $request->pakaian_id)
            ->first();

        if ($existingCart) {
            $existingCart->increment('keranjang_jumlah', $jumlah);
        } else {
            Keranjang::create([
                'keranjang_user_id'    => $userId,
                'keranjang_pakaian_id' => $request->pakaian_id,
                'keranjang_jumlah'     => $jumlah,
            ]);
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jumlah' => ['required', 'integer', 'min:1'],
        ]);

        $cartItem = Keranjang::with('pakaian')
            ->where('keranjang_id', $id)
            ->where('keranjang_user_id', Auth::user()->user_id)
            ->firstOrFail();

        $cartItem->update([
            'keranjang_jumlah' => $request->jumlah,
        ]);

        // Hitung ulang total semua barang di keranjang
        $cartItems = Keranjang::with('pakaian')
            ->where('keranjang_user_id', Auth::user()->user_id)
            ->get();

        $itemSubtotal = $cartItem->pakaian->pakaian_harga * $cartItem->keranjang_jumlah;
        $grandTotal = $cartItems->sum(fn($i) => $i->pakaian->pakaian_harga * $i->keranjang_jumlah);
        $totalPcs = $cartItems->sum('keranjang_jumlah');

        return response()->json([
            'success'      => true,
            'item_total'   => 'Rp ' . number_format((float)$itemSubtotal, 0, ',', '.'),
            'grand_total'  => 'Rp ' . number_format((float)$grandTotal, 0, ',', '.'),
            'total_pcs'    => $totalPcs . ' Pcs',
            'jumlah'       => $cartItem->keranjang_jumlah
        ]);
    }

    public function destroy($id)
    {
        Keranjang::where('keranjang_id', $id)
            ->where('keranjang_user_id', Auth::user()->user_id)
            ->delete();

        return redirect()->back()->with('success', 'Item berhasil dihapus.');
    }

    public function checkout(Request $request)
    {
        // 1. Tangkap array ID dari checkbox yang dicentang
        $selectedIds = $request->input('selected_items', []);

        // Validasi jika tidak ada yang dicentang
        if (empty($selectedIds)) {
            return redirect()->back()->with('error', 'Pilih minimal satu barang yang ingin dibeli.');
        }

        // 2. KUNCI UTAMA: Gunakan whereIn() agar HANYA mengambil barang yang dicentang
        $cartItems = Keranjang::with('pakaian')
            ->where('keranjang_user_id', Auth::user()->user_id)
            ->whereIn('keranjang_id', $selectedIds) // <-- Filter wajib
            ->get();

        // Hitung total harga barang terpilih saja
        $grandTotal = $cartItems->sum(function ($item) {
            return $item->pakaian->pakaian_harga * $item->keranjang_jumlah;
        });

        return view('pages.user.pembelian', compact('cartItems', 'grandTotal', 'selectedIds'));
    }
}
