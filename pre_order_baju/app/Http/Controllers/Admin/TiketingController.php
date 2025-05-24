<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TiketingController extends Controller
{
    public function index()
    {
        // Ambil semua komplain, urutkan dari terbaru
        $komplains = \App\Models\Komplain::orderBy('created_at', 'desc')->get();
        return view('admin.tiketing.index', compact('komplains'));
    }

    // app/Http/Controllers/Admin/TiketingController.php
    public function show($id)
    {
        $komplain = \App\Models\Komplain::findOrFail($id);
        $messages = $komplain->messages()->orderBy('created_at')->get();

        return view('admin.tiketing.show', compact('komplain', 'messages'));
    }
    public function reply(Request $request, $id)
    {
        $request->validate([
            'pesan' => 'required|string',
        ]);

        $komplain = \App\Models\Komplain::findOrFail($id);
        $komplain->messages()->create([
            'pesan' => $request->pesan,
            'pengirim' => 'admin'
        ]);

        return redirect()->back();
    }

    public function close($id)
    {
        $komplain = \App\Models\Komplain::findOrFail($id);
        $komplain->status = 'closed';
        $komplain->save();

        return redirect()->route('admin.tiketing.index')->with('success', 'Tiket berhasil ditutup.');
    }


}
