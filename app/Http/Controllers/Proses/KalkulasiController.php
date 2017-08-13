<?php

namespace App\Http\Controllers\Proses;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use App\Model\DataInduk;
use Auth;
use Validator;
use Carbon\Carbon;
use Session;

class KalkulasiController extends Controller
{
    private $rules = [
      'start' => 'required',
      'end' => 'required'
    ];

    public function index()
    {
      $unker = Auth::user()->unker;

      $opd = DataInduk::orderBy('nama_unker','asc')
             ->groupBy('id_unker','nama_unker')
             ->where(function($query) use($unker) {
               if(!empty($unker)) {
                 $query->where('id_unker',$unker);
               }
             })
             ->pluck('nama_unker','id_unker');

      return view('proses.kalkulasi_form')
            ->withOpd($opd);
    }

    public function prosesKalkulasi(Request $request)
    {
      set_time_limit(0);

      $validator = Validator::make($request->all(), $this->rules);

      $validator->after(function($validator) use($request) {
        $start =  Carbon::parse($request->start);
        $end   =  Carbon::parse($request->end);
        $interval = $end->diffInDays($start);

        if($interval > 31){
          $validator->errors()->add('date_range', 'Maksimum range tanggal tidak lebih dari 31 hari!');
        }

      });

      if($validator->fails()) {
        return response()->json($validator->messages(), 422);
      }

      Session::put('progress', 0);
      for($i=0;$i<=10000;$i++) {
        usleep(1000);
        $status = round($i * 100 / 10000);
        Session::put('progress', $i);
      }

      return response()->json($status);
    }

    public function apiGetProgress()
    {
      return response()->json(array(Session::get('progress')));
    }

    public function apiListKehadiran()
    {



    }
}
