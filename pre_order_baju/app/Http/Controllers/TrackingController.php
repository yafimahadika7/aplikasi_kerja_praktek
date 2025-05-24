<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function show($resi)
    {
        $apiKey = '765818011dc6e317fd4ad239444fe860e6a5f1960e67677f0bc7d6c8848412b0';
        $response = Http::get('https://api.binderbyte.com/v1/track', [
            'api_key' => $apiKey,
            'courier' => 'jne',
            'awb' => $resi
        ]);

        if ($response->successful()) {
            $data = $response->json();
            if ($data['status'] === 200) {
                return view('tracking.show', ['data' => $data['data']]);
            } else {
                return view('tracking.show', ['error' => $data['message']]);
            }
        } else {
            return view('tracking.show', ['error' => 'Gagal menghubungi server Binderbyte.']);
        }
    }

    public function ajax(Request $request)
    {
        $request->validate([
            'resi' => 'required'
        ]);

        $apiKey = '765818011dc6e317fd4ad239444fe860e6a5f1960e67677f0bc7d6c8848412b0'; // Gunakan dari .env jika perlu
        $resi = $request->resi;

        $response = Http::get('https://api.binderbyte.com/v1/track', [
            'api_key' => $apiKey,
            'courier' => 'jne',
            'awb' => $resi
        ]);

        return response()->json($response->json());
    }
}