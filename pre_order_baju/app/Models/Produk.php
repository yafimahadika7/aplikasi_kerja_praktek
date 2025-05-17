<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    // Menentukan nama tabel eksplisit jika tidak mengikuti konvensi Laravel
    protected $table = 'produks';

    // Kolom-kolom yang boleh diisi secara massal
    protected $fillable = [
        'nama',
        'deskripsi',
        'harga',
        'kategori',
        'gambar',
        'stock'
    ];

    // Akses total stok dari field JSON
    public function getTotalStokAttribute()
    {
        $stok = json_decode($this->stock, true);
        return is_array($stok) ? array_sum($stok) : 0;
    }

    // Relasi ke tabel keranjangs
    public function keranjangs()
    {
        return $this->hasMany(Keranjang::class, 'produk_id');
    }
}
