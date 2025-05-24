<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komplain extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'kontak', 'topik', 'status'];

    public function messages()
    {
        return $this->hasMany(\App\Models\KomplainMessage::class);
    }
}
