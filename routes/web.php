<?php

// use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\UserHomeController;
use App\Http\Controllers\user\UserPakaianController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\KategoriController;
use App\Http\Controllers\admin\PakaianController;
use App\Http\Controllers\admin\PembelianController;
use App\Http\Controllers\user\UserPembelianController;
use App\Http\Controllers\user\UserKeranjangController;
use App\Http\Controllers\user\UserMetodePembayaranController;
use App\Http\Controllers\user\UserProfileController;

Route::get('/', [UserHomeController::class, 'index'])->name('home');
Route::get('/pakaian', [UserPakaianController::class, 'index'])->name('pakaian.index');

// Route khusus User Terautentikasi (Auth)
Route::middleware(['auth'])->group(function () {

    // Manajemen Keranjang
    Route::controller(UserKeranjangController::class)->prefix('keranjang')->as('keranjang.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');

        // Route Checkout ini yang dipanggil oleh form keranjang!
        Route::get('/checkout', [UserKeranjangController::class, 'checkout'])->name('pembelian.checkout');
    });

    // Pembelian & Checkout
    Route::middleware(['auth'])->group(function () {
        Route::controller(UserPembelianController::class)->prefix('pembelian')->as('pembelian.')->group(function () {
            Route::get('/', 'checkout')->name('checkout');
            Route::post('/', 'store')->name('store');
        });
    });

    Route::get('/pembayaran/{id}', [UserPembelianController::class, 'pembayaran'])->name('pembayaran.show');

    Route::middleware(['auth'])->group(function () {
        Route::get('/riwayat-pembelian', [UserPembelianController::class, 'index'])->name('pembelian.index');
    });

    Route::controller(UserMetodePembayaranController::class)->prefix('metode-pembayaran')->as('metode.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [UserProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');
    });
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Modul Kategori Pakaian
    Route::controller(KategoriController::class)->prefix('kategori')->name('kategori.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    // Modul Data Pakaian
    Route::controller(PakaianController::class)->prefix('pakaian')->name('pakaian.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    Route::controller(PembelianController::class)->prefix('pembelian')->name('pembelian.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::put('/{id}/status', 'updateStatus')->name('updateStatus');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });
});

// 3. Setting Profil Akun
// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__ . '/auth.php';
