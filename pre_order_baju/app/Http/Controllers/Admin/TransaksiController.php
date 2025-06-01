<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\VirtualAccountEmail;
use App\Mail\ResiPengirimanEmail;
use Illuminate\Support\Str;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::query();

        // Filter pencarian (nama, email, va_number)
        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('va_number', 'like', "%{$search}%");
            });
        }

        // Filter tanggal
        if ($request->from && $request->to) {
            $from = Carbon::parse($request->from)->startOfDay();
            $to = Carbon::parse($request->to)->endOfDay();
            $query->whereBetween('created_at', [$from, $to]);
        }

        $transaksis = $query->latest()->get();

        return view('admin.transaksi.index', compact('transaksis'));
    }

    public function edit($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $items = DB::table('transaksi_items')->where('transaksi_id', $id)->get();
        return view('admin.transaksi.edit', compact('transaksi', 'items'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,proses,sukses,gagal,retur'
        ]);

        $transaksi = Transaksi::findOrFail($id);
        $statusLama = $transaksi->status;

        $transaksi->status = $request->status;
        $transaksi->save();

        // Kirim email jika status berubah ke 'proses'
        if ($request->status === 'proses' && $statusLama !== 'proses') {
            try {
                Mail::to($transaksi->email)->send(new ResiPengirimanEmail($transaksi));
            } catch (\Exception $e) {
                \Log::error("Gagal kirim email resi: " . $e->getMessage());
            }
        }

        return redirect()->route('admin.transaksi.index')->with('success', 'Status transaksi diperbarui.');
    }

    public function updateItem(Request $request, $itemId)
    {
        $request->validate([
            'serial_number' => 'nullable|string|max:255',
            'nomor_resi' => 'nullable|string|max:255',
            'status' => 'required|in:pending,proses,sukses,gagal,retur',
        ]);

        // Update item
        DB::table('transaksi_items')->where('id', $itemId)->update([
            'serial_number' => $request->serial_number,
            'nomor_resi' => $request->nomor_resi,
            'status' => $request->status,
            'updated_at' => now(),
        ]);

        // Ambil transaksi_id dari item
        $transaksiId = DB::table('transaksi_items')->where('id', $itemId)->value('transaksi_id');

        // Ambil semua status item untuk transaksi tersebut
        $statuses = DB::table('transaksi_items')
            ->where('transaksi_id', $transaksiId)
            ->pluck('status')
            ->toArray();

        $uniqueStatuses = array_unique($statuses);

        // Tentukan final status berdasarkan prioritas
        if (in_array('pending', $uniqueStatuses)) {
            $finalStatus = 'pending';
        } elseif (in_array('proses', $uniqueStatuses)) {
            $finalStatus = 'proses';
        } elseif (in_array('gagal', $uniqueStatuses)) {
            $finalStatus = 'gagal';
        } elseif (count($uniqueStatuses) === 1 && $uniqueStatuses[0] === 'retur') {
            $finalStatus = 'retur';
        } elseif (count($uniqueStatuses) === 1 && $uniqueStatuses[0] === 'sukses') {
            $finalStatus = 'sukses';
        } else {
            $finalStatus = 'proses'; // fallback jika kombinasi tidak dikenali
        }

        // Update status transaksi utama
        DB::table('transaksis')->where('id', $transaksiId)->update([
            'status' => $finalStatus,
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Item dan status transaksi diperbarui.');
    }

    public function kirimResi(Request $request, $id)
    {
        $request->validate([
            'nomor_resi' => 'required|string|max:100',
            'status' => 'required|in:proses,sukses,retur,gagal',
        ]);

        DB::table('transaksi_items')
            ->where('transaksi_id', $id)
            ->update([
                'nomor_resi' => $request->nomor_resi,
                'status' => $request->status,
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Resi dan status berhasil diperbarui.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'telepon' => 'required|string',
            'email' => 'required|email',
            'alamat' => 'required|string',
            'metode_pembayaran' => 'required|string|in:BCA,MANDIRI,BNI,BRI',
            'items' => 'required|array',
            'total' => 'required|numeric'
        ]);

        $vaNumber = '88' . rand(1000000000, 9999999999);
        $expiredAt = now()->addHours(12);

        $transaksi = Transaksi::create([
            'nama' => $validated['nama'],
            'telepon' => $validated['telepon'],
            'email' => $validated['email'],
            'alamat' => $validated['alamat'],
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'va_number' => $vaNumber,
            'expired_at' => $expiredAt,
            'total' => $validated['total'],
        ]);

        foreach ($validated['items'] as $item) {
            for ($i = 0; $i < $item['jumlah']; $i++) {
                DB::table('transaksi_items')->insert([
                    'transaksi_id' => $transaksi->id,
                    'nama_produk' => $item['nama_produk'],
                    'ukuran' => $item['ukuran'] ?? null,
                    'jumlah' => 1, // 1 per baris
                    'harga' => $item['harga'],
                    'serial_number' => 'SN' . strtoupper(Str::random(8)),
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        try {
            Mail::to($validated['email'])->send(new VirtualAccountEmail($transaksi));
        } catch (\Exception $e) {
            \Log::error("Gagal kirim email VA: " . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'va_number' => $vaNumber,
            'expired_at' => $expiredAt->toDateTimeString()
        ]);
    }
}