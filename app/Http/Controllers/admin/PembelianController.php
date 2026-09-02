<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembelian;

class PembelianController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembelian::with(['user', 'pembelianDetail.pakaian']);

        // Filter status
        if ($request->filled('status')) {
            $query->where('pembelian_status', $request->status);
        }

        // Filter search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('pembelian_id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        // Sesuaikan 'user_fullname' / 'user_username' dengan nama kolom asli di tabel user
                        $u->where('user_fullname', 'like', "%{$search}%")
                            ->orWhere('user_username', 'like', "%{$search}%");
                    });
            });
        }

        $pembelian = $query->latest('pembelian_tanggal')->paginate(5);

        return view('pages.admin.pembelian', compact('pembelian'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'pembelian_status' => 'required|in:menunggu_konfirmasi,dibayar,diproses,dikirim,selesai,dibatalkan'
        ]);

        $pembelian = Pembelian::findOrFail($id);
        $pembelian->update([
            'pembelian_status' => $request->pembelian_status
        ]);

        return back()->with('success', 'Status transaksi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $pembelian = Pembelian::findOrFail($id);
        $pembelian->delete();

        return back()->with('success', 'Transaksi berhasil dihapus!');
    }
}
