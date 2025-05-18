<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama' => 'required|string',
            'telepon' => 'required|string',
            'email' => 'required|email',
            'alamat' => 'required|string',
            'metode_pembayaran' => 'required|string|in:BCA,MANDIRI,BNI,BRI',
            'items' => 'required|array',
            'total' => 'required|numeric'
        ]);

        // Generate nomor virtual account dan waktu expired
        $vaNumber = '88' . rand(1000000000, 9999999999);
        $expiredAt = Carbon::now()->addHours(12);

        // Simpan data transaksi ke database
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

        // Kirim response sukses ke frontend
        return response()->json([
            'success' => true,
            'va_number' => $vaNumber,
            'expired_at' => $expiredAt->toDateTimeString()
        ]);
    }
}