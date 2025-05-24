<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Komplain;

class KomplainController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kontak' => 'required|string|max:255',
            'topik' => 'required|string',
        ]);

        $komplain = Komplain::create([
            'nama' => $validated['nama'],
            'kontak' => $validated['kontak'],
            'topik' => $validated['topik'],
            'status' => 'open',
        ]);

        // ❌ JANGAN TAMBAH PESAN DISINI — karena frontend tidak kirim "pesan" di awal

        return response()->json(['komplain' => $komplain]);
    }

    public function close($id)
    {
        $komplain = \App\Models\Komplain::findOrFail($id);
        $komplain->status = 'closed';
        $komplain->save();

        return response()->json(['message' => 'Komplain berhasil ditutup.']);
    }

    public function show($id)
    {
        $komplain = \App\Models\Komplain::findOrFail($id);
        return response()->json([
            'id' => $komplain->id,
            'status' => $komplain->status,
            'nama' => $komplain->nama,
        ]);
    }

}