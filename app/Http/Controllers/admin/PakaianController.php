<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriPakaian;
use App\Models\Pakaian;
use Illuminate\Support\Facades\Storage;

class PakaianController extends Controller
{
    public function index(Request $request)
    {
        $categories = KategoriPakaian::all();
        $query = Pakaian::with('kategori');

        if ($request->filled('search')) {
            $query->where('pakaian_nama', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('pakaian_kategori_pakaian_id', $request->kategori);
        }

        $pakaian = $query->latest('created_at')->paginate(5);

        return view('pages.admin.pakaian', compact('pakaian', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pakaian_nama' => 'required|string|max:255',
            'pakaian_kategori_pakaian_id' => 'required|exists:kategori_pakaian,kategori_pakaian_id',
            'pakaian_harga' => 'required|numeric|min:0',
            'pakaian_stok' => 'required|integer|min:0',
            'pakaian_gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'pakaian_gambar_url_input' => 'nullable|url|max:1000',
        ]);

        $gambarFinal = null;

        // Prioritas 1: Jika upload file
        if ($request->hasFile('pakaian_gambar')) {
            $gambarFinal = $request->file('pakaian_gambar')->store('pakaian', 'public');
        }
        // Prioritas 2: Jika isi URL gambar
        elseif ($request->filled('pakaian_gambar_url_input')) {
            $gambarFinal = $request->pakaian_gambar_url_input;
        }

        Pakaian::create([
            'pakaian_nama' => $request->pakaian_nama,
            'pakaian_kategori_pakaian_id' => $request->pakaian_kategori_pakaian_id,
            'pakaian_harga' => $request->pakaian_harga,
            'pakaian_stok' => $request->pakaian_stok,
            'pakaian_gambar_url' => $gambarFinal,
        ]);

        return redirect()->back()->with('success', 'Data pakaian berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $item = Pakaian::findOrFail($id);

        $request->validate([
            'pakaian_nama' => 'required|string|max:255',
            'pakaian_kategori_pakaian_id' => 'required|exists:kategori_pakaian,kategori_pakaian_id',
            'pakaian_harga' => 'required|numeric|min:0',
            'pakaian_stok' => 'required|integer|min:0',
            'pakaian_gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'pakaian_gambar_url_input' => 'nullable|url|max:1000',
        ]);

        $data = [
            'pakaian_nama' => $request->pakaian_nama,
            'pakaian_kategori_pakaian_id' => $request->pakaian_kategori_pakaian_id,
            'pakaian_harga' => $request->pakaian_harga,
            'pakaian_stok' => $request->pakaian_stok,
        ];

        // Jika upload file baru
        if ($request->hasFile('pakaian_gambar')) {
            $this->deleteLocalImage($item->pakaian_gambar_url);
            $data['pakaian_gambar_url'] = $request->file('pakaian_gambar')->store('pakaian', 'public');
        }
        // Jika memasukkan URL baru
        elseif ($request->filled('pakaian_gambar_url_input')) {
            $this->deleteLocalImage($item->pakaian_gambar_url);
            $data['pakaian_gambar_url'] = $request->pakaian_gambar_url_input;
        }

        $item->update($data);

        return redirect()->back()->with('success', 'Data pakaian berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pakaian = Pakaian::findOrFail($id);

        // Cek apakah pakaian sudah pernah dipesan di tabel detail pembelian
        if ($pakaian->pembelianDetail()->exists()) {
            return back()->with('error', 'Pakaian tidak dapat dihapus karena sudah memiliki riwayat transaksi!');
        }

        $pakaian->delete();
        return back()->with('success', 'Data pakaian berhasil dihapus.');
    }

    // Helper untuk hapus file lokal jika bukan URL eksternal
    private function deleteLocalImage($path)
    {
        if ($path && !filter_var($path, FILTER_VALIDATE_URL) && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
