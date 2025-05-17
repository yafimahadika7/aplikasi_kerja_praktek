<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;

class CustomController extends Controller
{
    public function index()
    {
        return view('pelanggan.custom.index');
    }
}