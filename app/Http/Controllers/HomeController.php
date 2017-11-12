<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\Model\PegawaiJadwal;
use DB;
use App\Model\DataInduk;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $status = [];
      $unker = Auth::user()->unker;
      $tanggal = [
        'start' => Carbon::now()->subDays(7),
        'end'   => Carbon::now()
      ];

      if($request->has('start')){
        $tanggal['start'] = Carbon::parse($request->start);
      }
      if($request->has('end')){
        $tanggal['end'] = Carbon::parse($request->end);
      }

      $table = PegawaiJadwal::join('peg_data_induk','peg_data_induk.id','=','peg_jadwal.peg_id')
                ->where('peg_jadwal.tanggal','>=',$tanggal['start']->format('Y-m-d'))
                ->where('peg_jadwal.tanggal','<=',$tanggal['end']->format('Y-m-d'))
                ->where(function($query) use($unker){
                  if(!empty($unker)){
                    $query->where('peg_data_induk.id_unker',$unker);
                  }
                });

      $count_pegawai = DataInduk::where(function($query) use($unker){
                        if(!empty($unker)){
                          $query->where('peg_data_induk.id_unker',$unker);
                        }
                      })->count();

      $jml_hari = $tanggal['end']->diffInDays($tanggal['start']) + 1;
      $jml_hari_efektif = $jml_hari - with(clone $table)->where('peg_jadwal.status','L')->groupBy('peg_jadwal.peg_id')->count();

      if(!empty($count_pegawai)){
        $status = [
          'Hadir' => (with(clone $table)->where('peg_jadwal.status','H')->count() / ($count_pegawai * $jml_hari_efektif) ) * 100,
          'Hadir Terlambat' => (with(clone $table)->where('peg_jadwal.status','HT')->count() / ($count_pegawai * $jml_hari_efektif) ) * 100,
          'Hadir Pulang Awal' => (with(clone $table)->where('peg_jadwal.status','HP')->count() / ($count_pegawai * $jml_hari_efektif) ) * 100,
          'Ijin' => (with(clone $table)->where('peg_jadwal.status','I')->count() / ($count_pegawai * $jml_hari_efektif) ) * 100,
          'Cuti' => (with(clone $table)->where('peg_jadwal.status','C')->count() / ($count_pegawai * $jml_hari_efektif) ) * 100,
          'Sakit' => (with(clone $table)->where('peg_jadwal.status','S')->count() / ($count_pegawai * $jml_hari_efektif) ) * 100,
          'Dinas Luar' => (with(clone $table)->where('peg_jadwal.status','DL')->count() / ($count_pegawai * $jml_hari_efektif) ) * 100,
          'Tugas Belajar' => (with(clone $table)->where('peg_jadwal.status','TB')->count() / ($count_pegawai * $jml_hari_efektif) ) * 100,
          'Alpha' => (with(clone $table)->where('peg_jadwal.status','A')->count() / ($count_pegawai * $jml_hari_efektif) ) * 100
        ];
      }

      $chartjs = app()->chartjs
        ->name('piePersentase')
        ->type('pie')
        ->size(['width' => 400, 'height' => 200])
        ->labels(array_keys($status))
        ->datasets([
            [
                'backgroundColor' => ['#FF6384', '#36A2EB','#33cc33','#ff00ff','#ffff00','#ff6666','#ccccff','#993333','#666633'],
                'hoverBackgroundColor' => ['#FF6384', '#36A2EB','#33cc33','#ff00ff','#ffff00','#ff6666','#ccccff','#993333','#666633'],
                'data' => [
                  isset($status['Hadir'])?round($status['Hadir'],2):'',
                  isset($status['Hadir Terlambat'])?$status['Hadir Terlambat']:'',
                  isset($status['Hadir Pulang Awal'])?$status['Hadir Pulang Awal']:'',
                  isset($status['Ijin'])?$status['Ijin']:'',
                  isset($status['Cuti'])?$status['Cuti']:'',
                  isset($status['Sakit'])?$status['Sakit']:'',
                  isset($status['Dinas Luar'])?$status['Dinas Luar']:'',
                  isset($status['Tugas Belajar'])?$status['Tugas Belajar']:'',
                  isset($status['Alpha'])?$status['Alpha']:'',
                ]
            ]
        ])
        ->options([]);

        return view('home')
          ->with('chartjs', $chartjs)
          ->withTanggal($tanggal)
          ->withJumlahpegawai($count_pegawai)
          ->withJumlahhariefektif($jml_hari_efektif);
    }
}
