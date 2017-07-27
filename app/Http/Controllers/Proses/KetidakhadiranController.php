<?php

namespace App\Http\Controllers\Proses;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Ketidakhadiran;
use Yajra\Datatables\Datatables;
use App\Model\RefIjin;

class KetidakhadiranController extends Controller
{
    public function index()
    {
      return view('proses.ketidakhadiran_list');
    }

    public function create()
    {
      $ijin = RefIjin::orderBy('name','asc')->pluck('name','id');

      return view('proses.ketidakhadiran_form')->withIjin($ijin);
    }
}
