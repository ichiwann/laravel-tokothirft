<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriPakaian;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $query = KategoriPakaian::withCount('pakaian');

        if ($request->filled('search')) {
            $query->where('kategori_pakaian_nama', 'like', '%' . $request->search . '%');
        }

        $categories = $query->latest('created_at')->paginate(5);

        return view('pages.admin.kategori', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_pakaian_nama' => 'required|string|max:255|unique:kategori_pakaian,kategori_pakaian_nama',
        ]);

        KategoriPakaian::create([
            'kategori_pakaian_nama' => $request->kategori_pakaian_nama,
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $category = KategoriPakaian::findOrFail($id);

        $request->validate([
            'kategori_pakaian_nama' => 'required|string|max:255|unique:kategori_pakaian,kategori_pakaian_nama,' . $id . ',kategori_pakaian_id',
        ]);

        $category->update([
            'kategori_pakaian_nama' => $request->kategori_pakaian_nama,
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kategori = KategoriPakaian::findOrFail($id);

        // Cek apakah ada pakaian yang menggunakan kategori ini
        if ($kategori->pakaian()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh beberapa produk pakaian!');
        }

        $kategori->delete();
        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
