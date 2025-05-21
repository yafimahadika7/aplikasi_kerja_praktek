<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProdukController as AdminProdukController;
use App\Http\Controllers\Admin\TransaksiController as AdminTransaksiController;
use App\Http\Controllers\Admin\PenjualanController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Pelanggan\ProdukController as PelangganProdukController;
use App\Http\Controllers\Pelanggan\CustomController;
use App\Http\Controllers\TransaksiController;

use App\Mail\VirtualAccountEmail;
use App\Mail\ResiPengirimanEmail;
use App\Models\Transaksi;
use App\Exports\TransaksiExport;
use Maatwebsite\Excel\Facades\Excel;

// ✅ Landing Page
Route::get('/', fn() => view('welcome'));

// ✅ Redirect user sesuai role ke /admin/dashboard
Route::get('/redirect', function () {
    return redirect()->route('admin.dashboard');
})->middleware('auth');

// ✅ Dashboard tunggal (untuk semua role)
Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])
    ->middleware(['auth'])
    ->name('admin.dashboard');

// ✅ Dashboard default Laravel (jika diperlukan)
Route::get('/dashboard', fn() => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// ✅ Profil user
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ✅ User (khusus admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
});

// ✅ Produk (admin & produk)
Route::middleware(['auth', 'role:admin,produk'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('produk', AdminProdukController::class);
});

// ✅ Transaksi (admin & operation)
Route::middleware(['auth', 'role:admin,operation'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/transaksi', [AdminTransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/{id}/edit', [AdminTransaksiController::class, 'edit'])->name('transaksi.edit');
    Route::put('/transaksi/{id}', [AdminTransaksiController::class, 'update'])->name('transaksi.update');
    Route::get('/transaksi/export', fn(Request $request) => Excel::download(new TransaksiExport($request), 'daftar_transaksi.xlsx'))->name('transaksi.export');
});

// ✅ Penjualan (admin & finance)
Route::middleware(['auth', 'role:admin,finance'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::get('/penjualan/export', [PenjualanController::class, 'export'])->name('penjualan.export');
});

// ✅ Pelanggan (publik)
Route::get('/produk', [PelangganProdukController::class, 'index'])->name('produk.index');
Route::get('/custom', [CustomController::class, 'index'])->name('custom.index');
Route::get('/keranjang', fn() => view('pelanggan.keranjang.index'))->name('keranjang.index');
Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');

// ✅ Email testing
Route::get('/tes-email', function () {
    $transaksi = Transaksi::latest()->first();
    if (!$transaksi || !$transaksi->email) return "❌ Tidak ada transaksi atau email tidak ditemukan.";

    try {
        Mail::to($transaksi->email)->send(new VirtualAccountEmail($transaksi));
        return "✅ Email VA berhasil dikirim ke: " . $transaksi->email;
    } catch (\Exception $e) {
        return "❌ Gagal kirim email VA: " . $e->getMessage();
    }
});

Route::get('/tes-resi', function () {
    $transaksi = Transaksi::latest()->first();
    if (!$transaksi || !$transaksi->email) return "❌ Tidak ada transaksi atau email tidak ditemukan.";

    try {
        Mail::to($transaksi->email)->send(new ResiPengirimanEmail($transaksi));
        return "✅ Email Resi berhasil dikirim ke: " . $transaksi->email;
    } catch (\Exception $e) {
        return "❌ Gagal kirim email Resi: " . $e->getMessage();
    }
});

// ✅ Auth default
require __DIR__.'/auth.php';