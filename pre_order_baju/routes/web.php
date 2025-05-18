<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProdukController as AdminProdukController;
use App\Http\Controllers\Admin\TransaksiController as AdminTransaksiController;
use App\Http\Controllers\Pelanggan\ProdukController as PelangganProdukController;
use App\Http\Controllers\Pelanggan\CustomController;
use App\Http\Controllers\Pelanggan\KeranjangController;
use App\Http\Controllers\TransaksiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing page pelanggan (tanpa login)
Route::get('/', function () {
    return view('welcome');
});

// Redirect setelah login sesuai role
Route::get('/redirect', function () {
    $user = Auth::user();
    if (!$user) return redirect('/');

    switch ($user->role) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'produk':
            return redirect()->route('admin.produk.index');
        case 'operation':
            return redirect()->route('admin.transaksi.index');
        case 'finance':
            return redirect('/admin/penjualan'); // pastikan ini ada view-nya
        default:
            return redirect('/dashboard');
    }
})->middleware('auth');

// Dashboard user biasa
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Dashboard admin
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth'])->name('admin.dashboard');

// Halaman profil user (semua yang login bisa akses)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin section (khusus user dengan akses admin)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('produk', AdminProdukController::class);

    // Manajemen Transaksi (khusus admin/operation)
    Route::get('/transaksi', [AdminTransaksiController::class, 'index'])->name('transaksi.index');
    Route::put('/transaksi/{id}', [AdminTransaksiController::class, 'update'])->name('transaksi.update');
});

// Halaman publik (pelanggan)
Route::get('/produk', [PelangganProdukController::class, 'index'])->name('produk.index');
Route::get('/custom', [CustomController::class, 'index'])->name('custom.index');

// Keranjang (tanpa login, pakai localStorage)
Route::get('/keranjang', function () {
    return view('pelanggan.keranjang.index');
})->name('keranjang.index');

// Transaksi (tanpa login)
Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');

// Auth (Laravel Breeze)
require __DIR__.'/auth.php';