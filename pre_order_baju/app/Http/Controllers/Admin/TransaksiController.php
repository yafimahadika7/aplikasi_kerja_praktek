<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Mail\VirtualAccountMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\VirtualAccountEmail;
use App\Mail\ResiPengirimanEmail;

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
        $statusLama = $transaksi->status;
        
        $transaksi->status = $request->status;
        $transaksi->serial_number = $request->serial_number; // ✅ ini penting
        $transaksi->save();

        if ($request->status === 'proses' && $statusLama !== 'proses' && $transaksi->serial_number) {
            try {
                Mail::to($transaksi->email)->send(new ResiPengirimanEmail($transaksi));
            } catch (\Exception $e) {
                \Log::error("Gagal kirim email resi: " . $e->getMessage());
            }
        }

        return redirect()->route('admin.transaksi.index')->with('success', 'Transaksi diperbarui.');
    }

    public function edit($id)
    {
        $transaksi = \App\Models\Transaksi::findOrFail($id);
        return view('admin.transaksi.edit', compact('transaksi'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'telepon' => 'required|string',
            'email' => 'required|email',
            'alamat' => 'required|string',
            'metode_pembayaran' => 'required|string|in:BCA,MANDIRI,BNI,BRI',
            'items' => 'required|array',
            'total' => 'required|numeric'
        ]);

        $vaNumber = '88' . rand(1000000000, 9999999999);
        $expiredAt = now()->addHours(12);

        $transaksi = Transaksi::create([
            'nama' => $validated['nama'],
            'telepon' => $validated['telepon'],
            'email' => $validated['email'],
            'alamat' => $validated['alamat'],
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'va_number' => $vaNumber,
            'expired_at' => $expiredAt,
            'total' => $validated['total'],
            'items' => json_encode($validated['items']),
        ]);

        // ✅ Kirim email
        try {
            Mail::to($validated['email'])->send(new VirtualAccountEmail($transaksi));
        } catch (\Exception $e) {
            \Log::error("Gagal kirim email: " . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'va_number' => $vaNumber,
            'expired_at' => $expiredAt->toDateTimeString()
        ]);
    }

}