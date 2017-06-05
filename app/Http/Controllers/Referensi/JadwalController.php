<?php

namespace App\Http\Controllers\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JadwalController extends Controller
{
    public function index()
    {

    }

    public function create()
    {
      return view('referensi.jadwal_form');
    }

    public function store()
    {

    }

    private function validateJadwal($data)
    {
      return $this->validate($data, [
                'name' => 'required'          
            ]);
    }
}
