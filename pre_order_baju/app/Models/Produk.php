<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $fillable = [
        'nama', 'deskripsi', 'harga', 'kategori', 'gambar', 'stock'
    ];

    public function getTotalStokAttribute()
    {
        $stok = json_decode($this->stock, true);
        return is_array($stok) ? array_sum($stok) : 0;
    }
}
