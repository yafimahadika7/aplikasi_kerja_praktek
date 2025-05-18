<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::query();

        // Filter pencarian (nama, email, va_number)
        if ($search = $request->search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('va_number', 'like', "%{$search}%");
            });
        }

        // Filter tanggal
        if ($request->from && $request->to) {
            $from = Carbon::parse($request->from)->startOfDay();
            $to = Carbon::parse($request->to)->endOfDay();
            $query->whereBetween('created_at', [$from, $to]);
        }

        $transaksis = $query->latest()->get();

        return view('admin.transaksi.index', compact('transaksis'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,proses,sukses,gagal',
            'serial_number' => 'nullable|string|max:255'
        ]);

        $transaksi = \App\Models\Transaksi::findOrFail($id);
        $transaksi->status = $request->status;
        $transaksi->serial_number = $request->serial_number; // ✅ ini penting
        $transaksi->save();

        return redirect()->route('admin.transaksi.index')->with('success', 'Transaksi diperbarui.');
    }

    public function edit($id)
    {
        $transaksi = \App\Models\Transaksi::findOrFail($id);
        return view('admin.transaksi.edit', compact('transaksi'));
    }

}