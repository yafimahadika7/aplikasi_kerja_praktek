<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PenjualanExport;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::where('status', 'sukses');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('from') && $request->filled('to')) {
            $from = $request->from . ' 00:00:00';
            $to   = $request->to   . ' 23:59:59';
            $query->whereBetween('created_at', [$from, $to]);
        }

        $transaksis = $query->latest()->get();

        return view('admin.penjualan.index', compact('transaksis'));
    }

    public function export(Request $request)
    {
        return Excel::download(new PenjualanExport($request), 'laporan_penjualan.xlsx');
    }
}