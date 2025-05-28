<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransaksiItem extends Model
{
    use HasFactory;

    protected $table = 'transaksi_items';

    protected $fillable = [
        'transaksi_id',
        'nama_produk',
        'ukuran',
        'jumlah',
        'harga',
        'serial_number',
        'nomor_resi',
        'status'
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'harga' => 'integer',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
}