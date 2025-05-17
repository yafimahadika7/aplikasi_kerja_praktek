<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; // <- ini penting agar Request dikenali
use App\Models\Produk;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::query();

        if ($request->filled('filter')) {
            $query->where('kategori', $request->filter);
        }

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $produk = $query->get();

        return view('pelanggan.produk.index', compact('produk'));
    }
}