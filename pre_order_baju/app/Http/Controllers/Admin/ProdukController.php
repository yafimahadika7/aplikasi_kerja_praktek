<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::query();

        // Filter pencarian nama
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan kategori
        if ($request->filled('filter')) {
            $query->where('kategori', $request->filter);
        }

        $produk = $query->get();

        return view('admin.produk.index', compact('produk'));
    }

    public function create()
    {
        return view('admin.produk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric',
            'kategori' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'stock' => 'nullable|array'
        ]);

        $data = $request->only(['nama', 'deskripsi', 'harga', 'kategori']);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $path = $file->store('produk', 'public');
            $data['gambar'] = $path;
        }

        $data['stock'] = json_encode($request->stock ?? []);

        Produk::create($data);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        return view('admin.produk.edit', compact('produk'));
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $request->validate([
            'nama' => 'required|string',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric',
            'kategori' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'stock' => 'nullable|array'
        ]);

        $data = $request->only(['nama', 'deskripsi', 'harga', 'kategori']);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $path = $file->store('produk', 'public');
            $data['gambar'] = $path;
        }

        $data['stock'] = json_encode($request->stock ?? []);

        $produk->update($data);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus');
    }
}