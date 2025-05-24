<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomplainMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'komplain_id',
        'pesan',
        'pengirim',
    ];

    public function komplain()
    {
        return $this->belongsTo(\App\Models\Komplain::class);
    }
}