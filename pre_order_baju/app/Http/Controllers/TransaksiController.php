<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'telepon' => 'required|string',
            'email' => 'required|email',
            'alamat' => 'required|string',
            'metode_pembayaran' => 'required|string|in:BCA,MANDIRI,BNI,BRI',
            'items' => 'required|array'
        ]);

        $vaNumber = '88' . rand(1000000000, 9999999999);
        $expiredAt = Carbon::now()->addHours(12);

        // Hitung total semua item
        $total = 0;
        foreach ($validated['items'] as $item) {
            $total += $item['harga'] * $item['jumlah'];
        }

        // Simpan transaksi utama
        $transaksi = Transaksi::create([
            'nama' => $validated['nama'],
            'telepon' => $validated['telepon'],
            'email' => $validated['email'],
            'alamat' => $validated['alamat'],
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'va_number' => $vaNumber,
            'expired_at' => $expiredAt,
            'total' => $total,
        ]);

        // Simpan item satu per satu
        foreach ($validated['items'] as $item) {
            for ($i = 0; $i < $item['jumlah']; $i++) {
                TransaksiItem::create([
                    'transaksi_id' => $transaksi->id,
                    'nama_produk' => $item['nama_produk'],
                    'ukuran' => $item['ukuran'],
                    'jumlah' => 1, // karena dipecah per item
                    'harga' => $item['harga'],
                    'serial_number' => 'SN-' . strtoupper(Str::random(10)), // SN unik
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'va_number' => $vaNumber,
            'expired_at' => $expiredAt->toDateTimeString()
        ]);
    }
}