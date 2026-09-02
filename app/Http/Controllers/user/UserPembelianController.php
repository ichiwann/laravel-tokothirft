<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MetodePembayaran;
use Illuminate\Support\Facades\Auth;
use App\Models\Pembelian;
use App\Models\Keranjang;
use App\Models\PembelianDetail;

class UserPembelianController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->user_id ?? Auth::id();

        // Menggunakan paginate() alih-alih get()
        $pembelianList = Pembelian::with(['details.pakaian', 'metodePembayaran'])
            ->where('pembelian_user_id', $userId)
            ->latest('pembelian_tanggal')
            ->paginate(5); // Menampilkan 5 transaksi per halaman

        return view('pages.user.riwayat_pembelian', compact('pembelianList'));
    }

    public function checkout(Request $request)
    {
        $selectedIds = $request->input('selected_items', []);

        // Simpan user ke variabel agar VS Code mengenali tipenya dengan sempurna
        $user = $request->user();

        $cartItems = Keranjang::with('pakaian')
            ->where('keranjang_user_id', $user->user_id)
            ->whereIn('keranjang_id', $selectedIds)
            ->get();

        $totalHarga = $cartItems->sum(fn($i) => $i->pakaian->pakaian_harga * $i->keranjang_jumlah);
        // Sesuaikan nama kolom dengan tabel metode_pembayaran milikmu
        $metodeList = MetodePembayaran::where('metode_pembayaran_user_id', $user->user_id)->get();

        return view('pages.user.pembelian', compact('cartItems', 'totalHarga', 'metodeList'));
    }

    public function store(Request $request)
    {
        $userId = Auth::user()->user_id;

        // 1. Ambil data item keranjang beserta data pakaian
        $cartItems = Keranjang::with('pakaian')->where('keranjang_user_id', $userId)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('keranjang.index')->with('error', 'Keranjang kosong.');
        }

        $metodePembayaranId = $request->input('metode_pembayaran_id');
        $totalHarga          = $request->input('total_harga');

        // 2. Simpan Transaksi Utama (Pembelian)
        $pembelian = Pembelian::create([
            'pembelian_user_id'              => $userId,
            'pembelian_metode_pembayaran_id' => $metodePembayaranId,
            'pembelian_tanggal'              => now(),
            'pembelian_total_harga'          => $totalHarga,
            'pembelian_status'               => 'menunggu_konfirmasi',
        ]);

        // 3. Simpan Setiap Barang Keranjang ke Tabel Detail Pembelian
        // 3. Simpan Setiap Barang Keranjang ke Tabel Detail Pembelian
        foreach ($cartItems as $item) {
            PembelianDetail::create([
                'pembelian_detail_pembelian_id' => $pembelian->pembelian_id,
                'pembelian_detail_pakaian_id'   => $item->keranjang_pakaian_id,
                'pembelian_detail_jumlah'       => $item->keranjang_jumlah,
                'pembelian_detail_total_harga'  => $item->pakaian->pakaian_harga * $item->keranjang_jumlah,
            ]);
        }

        // 4. Hapus Keranjang setelah detail tersimpan
        Keranjang::where('keranjang_user_id', $userId)->delete();

        return redirect()->route('pembayaran.show', $pembelian->pembelian_id)
            ->with('success', 'Pembelian berhasil!');
    }

    public function pembayaran($id)
    {
        $pembelian = Pembelian::with('metodePembayaran')
            ->where('pembelian_user_id', Auth::user()->user_id)
            ->where('pembelian_id', $id)
            ->firstOrFail();

        return view('pages.user.pembayaran', compact('pembelian'));
    }
}
