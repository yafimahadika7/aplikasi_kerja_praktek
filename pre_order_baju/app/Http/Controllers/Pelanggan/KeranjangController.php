<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Keranjang;
use App\Models\Produk;

class KeranjangController extends Controller
{
    /**
     * Tampilkan daftar isi keranjang
     */
    public function index()
    {
        $items = Keranjang::with('produk')->get();
        return view('pelanggan.keranjang.index', compact('items'));
    }

    /**
     * Simpan data produk ke keranjang (via AJAX)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'ukuran' => 'required|string|max:20',
            'jumlah' => 'required|integer|min:1',
        ]);

        Keranjang::create([
            'produk_id' => $validated['produk_id'],
            'ukuran' => $validated['ukuran'],
            'jumlah' => $validated['jumlah'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang.'
        ]);
    }

    /**
     * Hapus item dari keranjang
     */
    public function destroy($id)
    {
        \Log::info("Menghapus item dari keranjang dengan ID: $id");

        $item = Keranjang::find($id);

        if (!$item) {
            return redirect()->back()->with('error', 'Item tidak ditemukan.');
        }

        $item->delete();

        return redirect()->route('keranjang.index')->with('success', 'Item berhasil dihapus dari keranjang.');
    }
}