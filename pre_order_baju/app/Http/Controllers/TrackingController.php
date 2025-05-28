<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\TransaksiItem;

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

        $resi = $request->resi;

        // Cek dulu apakah resi terdaftar di database kita
        $terdaftar = TransaksiItem::where('nomor_resi', $resi)->exists();

        if (!$terdaftar) {
            return response()->json([
                'status' => 404,
                'message' => 'Nomor resi ini belum terdaftar dalam sistem kami.',
            ]);
        }

        // ← Kalau ingin lanjut dummy atau panggil API Binderbyte
        $dummy = [
            'summary' => ['status' => 'Pesanan telah terkirim'],
            'detail' => [
                'shipper' => 'Toko Adidaya',
                'receiver' => 'Rina Putri',
                'destination' => 'Jl. Kenangan No.12, Jakarta Selatan',
            ],
            'history' => [
                ['date' => '2025-05-28 14:34', 'desc' => 'Pesanan telah terkirim ke penerima'],
                ['date' => '2025-05-28 13:29', 'desc' => 'Kurir sedang mengantarkan paket'],
                ['date' => '2025-05-28 13:07', 'desc' => 'Kurir dalam perjalanan menjemput barang'],
            ],
        ];

        return response()->json([
            'status' => 200,
            'data' => $dummy,
        ]);
    }
    
    // public function ajax(Request $request)
    // {
    //     $request->validate([
    //         'resi' => 'required'
    //     ]);

    //     $apiKey = '765818011dc6e317fd4ad239444fe860e6a5f1960e67677f0bc7d6c8848412b0'; // Gunakan dari .env jika perlu
    //     $resi = $request->resi;

    //     $response = Http::get('https://api.binderbyte.com/v1/track', [
    //         'api_key' => $apiKey,
    //         'courier' => 'jne',
    //         'awb' => $resi
    //     ]);

    //     return response()->json($response->json());
    // }

    public function pesananDiterima(Request $request)
    {
        $request->validate([
            'no_resi' => 'required'
        ]);

        $updated = TransaksiItem::where('nomor_resi', $request->no_resi)
            ->update(['status' => 'sukses']);

        return response()->json([
            'success' => $updated > 0,
            'message' => $updated ? 'Berhasil diupdate' : 'Resi tidak ditemukan'
        ]);
    }
}