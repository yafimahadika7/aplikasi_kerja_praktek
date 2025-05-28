<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PenjualanExport;
use Carbon\Carbon;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::query();

        // Filter status hanya 'sukses'
        $query->where('status', 'sukses');

        // Filter pencarian nama atau email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Filter rentang tanggal
        if ($request->filled('from') && $request->filled('to')) {
            $from = Carbon::parse($request->from)->startOfDay();
            $to = Carbon::parse($request->to)->endOfDay();
            $query->whereBetween('created_at', [$from, $to]);
        }

        // Ambil data dan tampilkan
        $transaksis = $query->latest()->get();

        return view('admin.penjualan.index', compact('transaksis'));
    }

    public function export(Request $request)
    {
        return Excel::download(new PenjualanExport($request), 'laporan_penjualan.xlsx');
    }
}