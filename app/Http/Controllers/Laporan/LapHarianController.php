<?php

namespace App\Http\Controllers\Laporan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Model\DataInduk;
use App\Model\PegawaiJadwal;
use Validator;
use Carbon\Carbon;

class LapHarianController extends Controller
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

      return view('laporan.laporan_harian')->withOpd($opd);
    }

    public function cetak(Request $request)
    {
      $start =  Carbon::parse($request->start);
      $end   =  Carbon::parse($request->end);
      $interval = $end->diffInDays($start);

      $validator = Validator::make($request->all(), $this->rules);
      $validator->after(function($validator) use($interval) {
        if($interval > 31){
          $validator->errors()->add('start', 'Maksimum range tanggal tidak lebih dari 31 hari!');
        }
      });

      if($validator->fails()) {
        return redirect('laporan_harian')
                    ->withErrors($validator)
                    ->withInput();
      }

      $opd = DataInduk::orderBy('nama_unker','asc')
             ->where('id_unker', $request->opd)
             ->first();

      $data = [];
      for($i=0;$i<=$interval;$i++){
        $peg_jadwal = PegawaiJadwal::join('peg_data_induk','peg_data_induk.id','=','peg_jadwal.peg_id')
                      ->join('jadwal_kerja','jadwal_kerja.id','=','peg_jadwal.jadwal_id')
                      ->join('hari_kerja', function($join) use($start) {
                          $join->on('hari_kerja.jadwal_id','=','jadwal_kerja.id')
                               ->where('hari_kerja.hari','=', $start->format('N'));
                      })
                      ->leftJoin('event','event.id','=','peg_jadwal.event_id')
                      ->leftJoin('ketidakhadiran','ketidakhadiran.id','=','peg_jadwal.ketidakhadiran_id')
                      ->leftJoin('ref_ijin','ref_ijin.id','=','ketidakhadiran.keterangan_id')
                      ->where('peg_data_induk.id_unker', $request->opd)
                      ->where('peg_jadwal.tanggal','=', $start->format('Y-m-d'))
                      ->where(function($query) use($request) {
                        if($request->has('peg')) {
                          $query->whereIn('peg_jadwal.peg_id', $request->peg);
                        }
                      })
                      ->orderBy('peg_data_induk.type','asc')
                      ->orderBy('peg_data_induk.id_eselon','asc')
                      ->orderBy('peg_data_induk.id_pangkat','desc')
                      ->orderBy('peg_data_induk.tmt_pangkat','desc')
                      ->select('peg_jadwal.id','peg_data_induk.nama','peg_data_induk.nip','hari_kerja.jam_masuk','hari_kerja.jam_pulang',
                      'peg_jadwal.in','peg_jadwal.out','peg_jadwal.terlambat','peg_jadwal.pulang_awal','peg_jadwal.event_id','event.title',
                      'ref_ijin.name','peg_jadwal.status')
                      ->get();

        $event =  PegawaiJadwal::join('peg_data_induk','peg_data_induk.id','=','peg_jadwal.peg_id')
                  ->leftJoin('event','event.id','=','peg_jadwal.event_id')
                  ->where('peg_data_induk.id_unker', $request->opd)
                  ->where('peg_jadwal.tanggal','=', $start->format('Y-m-d'))
                  ->where(function($query) use($request) {
                    if($request->has('peg')) {
                      $query->whereIn('peg_jadwal.peg_id', $request->peg);
                    }
                  })
                  ->select('peg_jadwal.event_id','event.title')
                  ->first();


        $data[$start->day] = [
          'jadwal' => $peg_jadwal,
          'event' => $event
        ];
        $start->addDay();
      }

      $kepala =  DataInduk::where('id_unker', $opd->id_unker)->kepala()->first();


      return view('laporan.cetak_laporan_harian')->withOpd($opd)->withStart($request->start)->withEnd($request->end)
              ->withData($data)->withKepala($kepala);
    }
}
