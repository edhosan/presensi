<?php

namespace App\Http\Controllers\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Jadwal;

class HariController extends Controller
{
    public function create($id_jadwal)
    {
      $jadwal = Jadwal::find($id_jadwal);

      return view('referensi.hari_form')->withJadwal($jadwal);
    }
}
