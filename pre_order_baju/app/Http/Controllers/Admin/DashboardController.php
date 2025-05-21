<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function adminDashboard()
    {
        return view('admin.dashboard.admin', [
            'totalProduk' => Produk::count(),
            'totalTransaksi' => Transaksi::count(),
            'totalUser' => User::count(),
            'penjualanHariIni' => Transaksi::whereDate('created_at', now()->toDateString())
                                        ->where('status', 'sukses')
                                        ->sum('total'),
            'statusCounts' => [
                'pending' => Transaksi::where('status', 'pending')->count(),
                'proses' => Transaksi::where('status', 'proses')->count(),
                'sukses' => Transaksi::where('status', 'sukses')->count(),
                'gagal' => Transaksi::where('status', 'gagal')->count(),
            ]
        ]);
    }

}
