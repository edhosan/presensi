<?php

namespace App\Http\Controllers\Proses;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Ketidakhadiran;
use Yajra\Datatables\Datatables;

class KetidakhadiranController extends Controller
{
    public function index()
    {
      return view('proses.ketidakhadiran_list');
    }
}
