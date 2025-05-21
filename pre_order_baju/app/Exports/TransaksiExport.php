<?php

namespace App\Exports;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransaksiExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Transaksi::query();

        // Filter by search (nama/email)
        if ($this->request->filled('search')) {
            $query->where(function ($q) {
                $q->where('nama', 'like', '%' . $this->request->search . '%')
                  ->orWhere('email', 'like', '%' . $this->request->search . '%');
            });
        }

        // Filter by date range
        if ($this->request->filled('from') && $this->request->filled('to')) {
            $from = $this->request->from . ' 00:00:00';
            $to   = $this->request->to   . ' 23:59:59';
            $query->whereBetween('created_at', [$from, $to]);
        }

        // Transformasi data
        return $query->get()->map(function ($trx) {
            $items = json_decode($trx->items, true);
            $deskripsi = collect($items)->map(function ($item) {
                return "{$item['nama']} (Ukuran: {$item['ukuran']}, Jumlah: {$item['jumlah']}, Harga: Rp" .
                    number_format($item['harga'], 0, ',', '.') . ")";
            })->implode('; ');

            return [
                'Tanggal'           => $trx->created_at->format('Y-m-d H:i'),
                'Nama'              => $trx->nama,
                'Email'             => $trx->email,
                'Metode Pembayaran' => $trx->metode_pembayaran,
                'Serial Number'     => $trx->serial_number ?? '-',
                'Harga'             => $trx->total,
                'Status'            => ucfirst($trx->status),
                'Pembelian'         => $deskripsi,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama',
            'Email',
            'Metode Pembayaran',
            'Serial Number',
            'Harga',
            'Status',
            'Pembelian'
        ];
    }
}