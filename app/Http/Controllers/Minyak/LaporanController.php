<?php

namespace App\Http\Controllers\Minyak;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        return view('minyak.laporan.index');
    }
}
