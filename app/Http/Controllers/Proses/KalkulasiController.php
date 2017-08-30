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
      'start' => 'required|before:end',
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

      $peg_jadwal = PegawaiJadwal::join('peg_data_induk','peg_data_induk.id','=','peg_jadwal.peg_id')
                    ->leftJoin('ketidakhadiran','ketidakhadiran.id','=','peg_jadwal.ketidakhadiran_id')
                    ->leftJoin('ref_ijin','ref_ijin.id','=','ketidakhadiran.keterangan_id')
                    ->where('peg_data_induk.id_unker','=', $request->opd)
                    ->where('peg_jadwal.tanggal','>=', date('Y-m-d', strtotime($request->start)) )
                    ->where('peg_jadwal.tanggal','<=', date('Y-m-d', strtotime($request->end)) )
                    ->where(function($query) use($request) {
                      if($request->has('peg')) {
                        $query->whereIn('peg_jadwal.peg_id', $request->peg);
                      }
                    })
                    ->select('peg_jadwal.id','peg_jadwal.peg_id','peg_jadwal.jadwal_id','peg_jadwal.ketidakhadiran_id','peg_jadwal.tanggal','peg_jadwal.event_id',
                             'peg_data_induk.id_finger',
                             'ref_ijin.symbol')
                    ->get();

      $total = $peg_jadwal->count();

      $validator = Validator::make($request->all(), $this->rules);

      $validator->after(function($validator) use($request, $total) {
        $start =  Carbon::parse($request->start);
        $end   =  Carbon::parse($request->end);
        $interval = $end->diffInDays($start);

        if($interval > 31){
          $validator->errors()->add('date_range', 'Maksimum range tanggal tidak lebih dari 31 hari!');
        }

        if($total <= 0){
          $validator->errors()->add('nama', 'Jadwal kerja pegawai belum ditetapkan!');
        }

      });

      if($validator->fails()) {
        Session::put('progress', -1);
        return response()->json($validator->messages(), 422);
      }

      $i = 1;
      Session::put('progress', 0);
      PegawaiJadwal::join('peg_data_induk','peg_data_induk.id','=','peg_jadwal.peg_id')
                    ->where('peg_data_induk.id_unker','=', $request->opd)
                    ->where('peg_jadwal.tanggal','>=', date('Y-m-d', strtotime($request->start)) )
                    ->where('peg_jadwal.tanggal','<=', date('Y-m-d', strtotime($request->end)) )
                    ->where(function($query) use($request) {
                      if($request->has('peg')) {
                        $query->whereIn('peg_jadwal.peg_id', $request->peg);
                      }
                    })
                    ->update([
                      'peg_jadwal.ketidakhadiran_id' => 0,
                      'peg_jadwal.in'  => '00:00:00',
                      'peg_jadwal.out' => '00:00:00',
                      'peg_jadwal.terlambat' => 0,
                      'peg_jadwal.pulang_awal' => 0,
                      'peg_jadwal.jam_kerja' => '00:00:00',
                      'status'  => ''
                    ]);

      foreach ($peg_jadwal as $jadwal) {
        $log = AuthLog::where('UserID', $jadwal->id_finger)
                      ->whereDate('TransactionTime', $jadwal->tanggal)
                      ->get();

        $tanggal = Carbon::parse($jadwal->tanggal);
        $hari_id = $tanggal->format('N');
        $hari = Hari::where('hari', $hari_id)
                ->where('jadwal_id',$jadwal->jadwal_id)
                ->first();

        if(!empty($jadwal->event_id)){
          PegawaiJadwal::where('id',$jadwal->id)->update(['status' => 'L']);
        }

        if($hari){
          $jm = Carbon::parse($hari->jam_masuk);
          $toleransi_terlambat = Carbon::parse($hari->toleransi_terlambat);
          $jm = $jm->addMinutes($toleransi_terlambat->minute);
          $jp = Carbon::parse($hari->jam_pulang);
          $toleransi_pulang = Carbon::parse($hari->toleransi_pulang);
          $jp = $jp->subMinutes($toleransi_pulang->minute);
          $in = Carbon::createFromTime(0, 0, 0);
          $out = Carbon::createFromTime(0, 0, 0);
          $jam_kerja = Carbon::createFromTime(0, 0, 0);
          $status_hadir = '';

          foreach ($log as $authlog) {
            $date = Carbon::parse($authlog->TransactionTime);
            $time = Carbon::parse($date->toTimeString());
            $scan_in1 = Carbon::parse($hari->scan_in1);
            $scan_in2 = Carbon::parse($hari->scan_in2);
            $scan_out1 = Carbon::parse($hari->scan_out1);
            $scan_out2 = Carbon::parse($hari->scan_out2);
            $terlambat = Carbon::createFromTime(0, 0, 0);
            $pulang_awal = Carbon::createFromTime(0, 0, 0);
            $peg_jadwal = PegawaiJadwal::find($jadwal->id);

            if($time->gte($scan_in1) && $time->lte($scan_in2)){
              $in = $time;
              if( $time->gt($jm) ) {
                $terlambat = $time->diff($jm)->format('%H:%I:%S');
                $status_hadir = 'HT';
              }

              $peg_jadwal->update([
                'in'  => $time->toTimeString(),
                'terlambat' => $terlambat,
                'status'  => $status_hadir
              ]);
            }

            if($time->gte($scan_out1) && $time->lte($scan_out2) ){
              $out = $time;
              if($in->toTimeString() != '00:00:00' && $time->toTimeString() != '00:00:00'){
                $jam_kerja = $time->diff($in)->format('%H:%I:%S');
              }

              if($time->lt($jp)) {
                $pulang_awal = $jp->diff($time)->format('%H:%I:%S');
                $status_hadir = 'HP';
              }

              $peg_jadwal->update([
                'out'  => $time->toTimeString(),
                'pulang_awal' => $pulang_awal,
                'jam_kerja' => $jam_kerja,
                'status'  => $status_hadir
              ]);
            }
          }

          if($in->toTimeString() != '00:00:00' && $out->toTimeString() != "00:00:00"){
            if($status_hadir != 'HT' && $status_hadir != 'HP'){
              PegawaiJadwal::find($jadwal->id)->update(['status' => 'H']);
            }
          }

          if($in->toTimeString() == '00:00:00' || $out->toTimeString() == '00:00:00'){
              PegawaiJadwal::find($jadwal->id)->update(['status' => 'A']);
          }

          if($jadwal->ketidakhadiran_id != 0) {
            PegawaiJadwal::where('id',$jadwal->id)->update(['ketidakhadiran_id' => $jadwal->ketidakhadiran_id, 'status' => $jadwal->symbol]);
          }
        }
        else{
          PegawaiJadwal::find($jadwal->id)->update(['status' => 'L']);
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
