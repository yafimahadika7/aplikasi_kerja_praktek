<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    use HasFactory;

    protected $table = 'keranjangs'; // pastikan sesuai dengan nama tabel di database

    protected $fillable = [
        'produk_id',
        'ukuran',
        'jumlah',
    ];

    // Relasi ke produk
    public function produk()
    {
        return $this->belongsTo(\App\Models\Produk::class, 'produk_id');
    }
}