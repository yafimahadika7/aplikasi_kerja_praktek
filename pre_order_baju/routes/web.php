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
use App\Http\Controllers\ChatController;
use App\Http\Controllers\KomplainController;
use App\Http\Controllers\KomplainMessageController;
use App\Http\Controllers\Admin\TiketingController;
use App\Http\Controllers\Admin\TransaksiItemController;
use App\Http\Controllers\TrackingController;
use App\Mail\VirtualAccountEmail;
use App\Mail\ResiPengirimanEmail;
use App\Models\Transaksi;
use App\Exports\TransaksiExport;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/', fn() => view('company'))->name('beranda');

// ✅ Landing Page
Route::get('/welcome', fn() => view('welcome'));

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

Route::post('/kirim-chat', [ChatController::class, 'store'])->name('chat.store');
Route::post('/komplain', [KomplainController::class, 'store'])->name('komplain.store');
Route::get('/komplain/{id}/messages', [KomplainMessageController::class, 'index']);
Route::post('/komplain/{id}/messages', [KomplainMessageController::class, 'store']);
Route::post('/komplain/{id}/close', [KomplainController::class, 'close']);
Route::get('/komplain/{id}', [KomplainController::class, 'show']);

Route::middleware(['auth', 'role:admin,operation'])->prefix('admin')->name('admin.')->group(function() {
    Route::get('/tiketing', [TiketingController::class, 'index'])->name('tiketing.index');
    Route::get('/tiketing/{id}', [TiketingController::class, 'show'])->name('tiketing.show');
    Route::post('/tiketing/{id}/reply', [TiketingController::class, 'reply'])->name('tiketing.reply');
    Route::post('/tiketing/{id}/close', [TiketingController::class, 'close'])->name('tiketing.close');
});

Route::get('/tracking/{resi}', [App\Http\Controllers\TrackingController::class, 'show'])->name('tracking.show');
Route::post('/tracking-resi', [App\Http\Controllers\TrackingController::class, 'ajax'])->name('tracking.ajax');
Route::post('/checkout', [\App\Http\Controllers\Pelanggan\CheckoutController::class, 'store'])->name('checkout.store');
Route::post('/admin/transaksi/item/{id}/update', [AdminTransaksiController::class, 'updateItem'])->name('admin.transaksi.item.update');
Route::post('/admin/transaksi/{id}/kirim-resi', [AdminTransaksiController::class, 'kirimResi'])->name('admin.transaksi.kirimResi');
Route::post('/pesanan-diterima', [TrackingController::class, 'pesananDiterima'])->name('pesanan.diterima');
// ✅ Auth default
require __DIR__.'/auth.php';