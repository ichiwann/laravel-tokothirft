<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Pakaian;
use App\Models\KategoriPakaian;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPakaian = Pakaian::count();
        $totalKategori = KategoriPakaian::count();
        $totalStok = Pakaian::sum('pakaian_stok');
        $latestPakaian = Pakaian::with('kategori')->latest('created_at')->take(5)->get();

        return view('pages.admin.dashboard', compact(
            'totalPakaian',
            'totalKategori',
            'totalStok',
            'latestPakaian'
        ));
    }
}
