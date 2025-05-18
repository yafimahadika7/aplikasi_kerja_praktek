<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    // Menentukan kolom yang bisa diisi secara massal
    protected $fillable = [
        'nama',
        'telepon',
        'email',
        'alamat',
        'metode_pembayaran',
        'va_number',
        'expired_at',
        'total',
        'items'
    ];

    // Casting untuk kolom items (json → array) dan expired_at (string → datetime)
    protected $casts = [
        'items' => 'array',
        'expired_at' => 'datetime',
    ];
}