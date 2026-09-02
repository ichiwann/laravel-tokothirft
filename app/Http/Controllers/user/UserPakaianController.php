<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pakaian;
use App\Models\KategoriPakaian;

class UserPakaianController extends Controller
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

        return view('pages.user.pakaian', compact('pakaian', 'categories'));
    }
}
