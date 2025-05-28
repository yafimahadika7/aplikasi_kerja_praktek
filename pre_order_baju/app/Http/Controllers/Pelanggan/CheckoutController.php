<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use App\Models\Keranjang;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Proses checkout dan simpan transaksi + item
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'telepon' => 'required|string|max:20',
            'email' => 'required|email',
            'alamat' => 'required|string',
            'metode_pembayaran' => 'required|string',
        ]);

        // Ambil isi keranjang
        $keranjangItems = Keranjang::with('produk')->get();

        if ($keranjangItems->isEmpty()) {
            return back()->with('error', 'Keranjang masih kosong.');
        }

        // Siapkan array item
        $itemsArray = $keranjangItems->map(function ($item) {
            return [
                'nama' => $item->produk->nama,
                'ukuran' => $item->ukuran,
                'jumlah' => $item->jumlah,
                'harga' => $item->produk->harga,
            ];
        })->toArray();

        // Hitung total
        $total = collect($itemsArray)->sum(function ($item) {
            return $item['harga'] * $item['jumlah'];
        });

        // Simpan transaksi utama
        $transaksi = Transaksi::create([
            'nama' => $request->nama,
            'telepon' => $request->telepon,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'metode_pembayaran' => $request->metode_pembayaran,
            'va_number' => 'VA' . rand(10000000, 99999999),
            'expired_at' => now()->addHours(12),
            'total' => $total,
            'items' => $itemsArray,
        ]);

        // Simpan item satu per satu ke transaksi_items
        foreach ($itemsArray as $item) {
            $transaksi->items()->create([
                'nama' => $item['nama'],
                'ukuran' => $item['ukuran'],
                'jumlah' => $item['jumlah'],
                'harga' => $item['harga'],
                'serial_number' => strtoupper(Str::random(10)),
                'status' => 'pending',
            ]);
        }

        // Hapus isi keranjang
        Keranjang::truncate();

        return redirect()->route('keranjang.index')->with('success', 'Checkout berhasil! Transaksi telah diproses.');
    }
}
