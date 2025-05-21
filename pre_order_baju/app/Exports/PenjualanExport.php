<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Http\Request;

class PenjualanExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Transaksi::where('status', 'sukses');

        if ($this->request->filled('search')) {
            $query->where(function ($q) {
                $q->where('nama', 'like', '%' . $this->request->search . '%')
                  ->orWhere('email', 'like', '%' . $this->request->search . '%');
            });
        }

        if ($this->request->filled('from') && $this->request->filled('to')) {
            $from = $this->request->from . ' 00:00:00';
            $to   = $this->request->to   . ' 23:59:59';
            $query->whereBetween('created_at', [$from, $to]);
        }

        return $query->get(['created_at', 'nama', 'email', 'telepon', 'total', 'metode_pembayaran']);
    }

    public function headings(): array
    {
        return ['Tanggal', 'Nama', 'Email', 'Telepon', 'Total', 'Metode Pembayaran'];
    }
}