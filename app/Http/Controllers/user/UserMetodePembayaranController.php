<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MetodePembayaran;
use Illuminate\Support\Facades\Auth;

class UserMetodePembayaranController extends Controller
{
    public function index()
    {
        $metodeList = MetodePembayaran::where('metode_pembayaran_user_id', Auth::user()->user_id)->get();
        return view('pages.user.metode_pembayaran', compact('metodeList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'metode_pembayaran_jenis' => ['required', 'in:DANA,OVO,BCA,COD'],
            'metode_pembayaran_nomor' => ['nullable', 'string', 'max:50'],
        ]);

        MetodePembayaran::create([
            'metode_pembayaran_user_id' => Auth::user()->user_id,
            'metode_pembayaran_jenis'   => $request->metode_pembayaran_jenis,
            'metode_pembayaran_nomor'   => $request->metode_pembayaran_nomor ?? '-',
        ]);

        return redirect()->back()->with('success', 'Metode pembayaran berhasil disimpan.');
    }

    public function destroy($id)
    {
        $metode = MetodePembayaran::where('metode_pembayaran_user_id', Auth::user()->user_id)->findOrFail($id);
        $metode->delete();

        return redirect()->back()->with('success', 'Metode pembayaran berhasil dihapus.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'metode_pembayaran_jenis' => ['required', 'in:BCA,DANA,OVO'],
            'metode_pembayaran_nomor' => ['required', 'string', 'max:50'],
        ]);

        $metode = MetodePembayaran::where('metode_pembayaran_user_id', Auth::user()->user_id)->findOrFail($id);
        $metode->update([
            'metode_pembayaran_jenis' => $request->metode_pembayaran_jenis,
            'metode_pembayaran_nomor' => $request->metode_pembayaran_nomor,
        ]);

        return redirect()->back()->with('success', 'Metode pembayaran berhasil diperbarui.');
    }
}
