<?php

namespace App\Http\Controllers\Laporan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Model\DataInduk;
use Carbon\Carbon;
use Validator;
use App\Model\PegawaiJadwal;

class LapKetidakhadiranController extends Controller
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

      return view('laporan.laporan_ketidakhadiran')->withOpd($opd);
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
        return redirect('laporan_ketidakhadiran')
                    ->withErrors($validator)
                    ->withInput();
      }

      $opd = DataInduk::orderBy('nama_unker','asc')
             ->where('id_unker', $request->opd)
             ->first();

      $peg_jadwal = PegawaiJadwal::join('peg_data_induk','peg_data_induk.id','=','peg_jadwal.peg_id')
                    ->join('ketidakhadiran','ketidakhadiran.id','=','peg_jadwal.ketidakhadiran_id')
                    ->join('ref_ijin','ref_ijin.id','=','ketidakhadiran.keterangan_id')
                    ->where('peg_jadwal.tanggal','>=',$start->format('Y-m-d'))
                    ->where('peg_jadwal.tanggal','<=',$end->format('Y-m-d'))
                    ->select('peg_data_induk.nip','peg_data_induk.nama','peg_jadwal.tanggal','ref_ijin.name','ketidakhadiran.keperluan')
                    ->get();

     return view('laporan.cetak_laporan_ketidakhadiran')->withOpd($opd)->withStart($request->start)->withEnd($request->end)->withData($peg_jadwal);
    }
}
