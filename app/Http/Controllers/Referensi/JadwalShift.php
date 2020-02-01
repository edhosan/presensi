<?php

namespace App\Http\Controllers\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Jadwal;
use App\Model\DataInduk;
use App\Model\OPD;
use Yajra\Datatables\Datatables;
use Auth;

class JadwalShift extends Controller
{
    public function index()
    {
        return view('referensi.jadwal_shift');
    }
}
