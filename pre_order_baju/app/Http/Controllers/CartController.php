<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Produk;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'ukuran' => 'required|string|max:10',
            'jumlah' => 'required|integer|min:1',
        ]);

        CartItem::create([
            'user_id' => Auth::id(),
            'produk_id' => $request->produk_id,
            'ukuran' => $request->ukuran,
            'jumlah' => $request->jumlah,
        ]);

        return response()->json(['success' => true, 'message' => 'Produk ditambahkan ke keranjang']);
    }

    public function index()
    {
        $items = CartItem::with('produk')->where('user_id', Auth::id())->get();
        return view('keranjang.index', compact('items'));
    }

    public function destroy($id)
    {
        $item = CartItem::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $item->delete();

        return redirect()->back()->with('success', 'Item berhasil dihapus dari keranjang');
    }
}