<?php

namespace App\Http\Controllers\Proses;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PegawaiJadwalController extends Controller
{
    public function index()
    {
       return view('proses.peg_jadwal_list');
    }

    public function create()
    {
      return view('proses.peg_jadwal_form');
    }
}
