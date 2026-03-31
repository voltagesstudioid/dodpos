<?php

namespace App\Http\Controllers\Minyak;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KunjunganController extends Controller
{
    public function index()
    {
        return view('minyak.kunjungan.index');
    }
}
