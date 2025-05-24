<?php

namespace App\Http\Controllers;

use App\Models\KomplainMessage;
use Illuminate\Http\Request;

class KomplainMessageController extends Controller
{
    // Menyimpan pesan baru
    public function store(Request $request)
    {
        $request->validate([
            'pesan' => 'required|string',
            'pengirim' => 'required|in:pelanggan,admin',
        ]);

        $message = KomplainMessage::create([
            'komplain_id' => $request->route('id'),
            'pesan' => $request->pesan,
            'pengirim' => $request->pengirim
        ]);

        return response()->json($message);
    }

    // Mengambil semua pesan untuk satu komplain
    public function index($komplain_id)
    {
        $messages = KomplainMessage::where('komplain_id', $komplain_id)
            ->orderBy('created_at')
            ->get();

        return response()->json($messages);
    }
}