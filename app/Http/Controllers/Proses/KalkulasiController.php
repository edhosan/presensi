<?php

namespace App\Http\Controllers\Proses;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use App\Model\DataInduk;
use App\Model\PegawaiJadwal;
use App\Model\AuthLog;
use App\Model\Hari;
use Auth;
use Validator;
use Carbon\Carbon;
use Session;
use DB;

class KalkulasiController extends Controller
{
    private $rules = [
      'opd' => 'required',
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
      $peg_jadwal = PegawaiJadwal::join('peg_data_induk','peg_data_induk.id','=','peg_jadwal.peg_id')
                    ->where('peg_data_induk.id_unker','=', $request->opd)
                    ->where('peg_jadwal.tanggal','>=', date('Y-m-d', strtotime($request->start)) )
                    ->where('peg_jadwal.tanggal','<=', date('Y-m-d', strtotime($request->end)) )
                    ->where(function($query) use($request) {
                      if($request->has('id_peg')) {
                        $query->where('peg_jadwal.peg_id', $request->id_peg);
                      }
                    })
                    ->select('peg_jadwal.id','peg_jadwal.peg_id','peg_jadwal.jadwal_id','peg_jadwal.ketidakhadiran_id','peg_jadwal.tanggal','peg_jadwal.event_id',
                             'peg_data_induk.id_finger')
                    ->get();

      $total = $peg_jadwal->count();
      $i = 1;
      foreach ($peg_jadwal as $jadwal) {
        if($jadwal->ketidakhadiran_id == 0 || empty($jadwal->event_id)){
          $log = AuthLog::where('UserID', $jadwal->id_finger)
                        ->whereDate('TransactionTime', $jadwal->tanggal)
                        ->get();

          $tanggal = Carbon::parse($jadwal->tanggal);
          $hari_id = $tanggal->format('N');
          $hari = Hari::where('hari', $hari_id)
                  ->where('jadwal_id',$jadwal->jadwal_id)
                  ->first();

          if($hari){
            $jm = Carbon::parse($hari->jam_masuk);
            $jp = Carbon::parse($hari->jam_pulang);

            foreach ($log as $authlog) {
              $trans = Carbon::parse($authlog->TransactionTime);
              $time = $trans->toTimeString();

              $in = '00:00:00';
              $out = '00:00:00';
              if($trans->hour < 12){
                $in = $trans->toTimeString();
              }else if($trans->hour > 14){
                $out = $trans->toTimeString();
              }

              $peg_jadwal = PegawaiJadwal::find($jadwal->id);
              $peg_jadwal->update([
                'in'  => $in,
                'out' => $out
              ]);
            }
          }




        }


        $status = round($i * 100 / $total);
        Session::put('progress', $status);
        $i++;
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
