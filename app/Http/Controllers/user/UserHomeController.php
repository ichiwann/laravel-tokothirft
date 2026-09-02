<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriPakaian;
use App\Models\Pakaian;

class UserHomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = KategoriPakaian::all();

        $query = Pakaian::with('kategori');

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->where('pakaian_nama', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->where('pakaian_kategori_pakaian_id', $request->kategori);
        }

        $pakaian = $query->latest('created_at')->get();

        return view('pages.user.home', compact('categories', 'pakaian'));
    }
}
