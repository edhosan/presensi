<?php

namespace App\Http\Controllers\Proses;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Jadwal;
use Auth;
use App\Model\DataInduk;
use Carbon\Carbon;
use App\Model\PegawaiJadwal;
use App\Model\Event;
use App\Model\Hari;
use Debugbar;
use DB;

class PegawaiJadwalController extends Controller
{
    private $rules = [
      'nama'  => 'required|exists:peg_data_induk,nama',
      'jadwal'  => 'required'
    ];

    public function index()
    {
       return view('proses.peg_jadwal_list');
    }

    public function create()
    {
      $unker = Auth::user()->unker;

      $jadwal = Jadwal::orderBy('name','asc')
                  ->where(function($query) use($unker) {
                    if(!empty($unker)) {
                      $query->where('id_unker',$unker);
                    }
                  })
                  ->pluck('name','id');

      return view('proses.peg_jadwal_form')->withJadwal($jadwal);
    }

    public function saveCreate(Request $request)
    {
      $this->validate($request, $this->rules);

      $jadwal = Jadwal::find($request->jadwal);

      $date = dateRange($jadwal->start, $jadwal->end);

      $data = [];
      foreach ($date as  $value) {
        $tanggal = Carbon::parse($value);

        PegawaiJadwal::where('peg_id', $request->id_peg)
          ->where('tanggal', $value)
          ->forceDelete();

        $peg_jadwal = new PegawaiJadwal();
        $peg_jadwal->tanggal = $value;
        $peg_jadwal->peg_id = $request->id_peg;
        $peg_jadwal->jadwal_id = $request->jadwal;
        //$peg_jadwal->hari_id = $tanggal->format('N');
        $event = Event::where('start_date', '=',$value)->orWhere('end_date','=',$value)->first();
        if(!empty($event)){
          $peg_jadwal->event_id = $event->id;
        }
        $peg_jadwal->save();
      }

      //return view('proses.peg_jadwal_detail')->withId($request->id_peg);
    }

    public function apiGetJadwalPegawai(Request $request)
    {
      $peg_jadwal = PegawaiJadwal::where('peg_id', $request->peg_id)
                    ->with('jadwal')
                    ->get();
      dd($peg_jadwal[0]->jadwal->first());
      $arr = collect();
      foreach ($peg_jadwal as  $value) {
        $tanggal = Carbon::parse($value->tanggal);
        $hari_id = $tanggal->format('N');
        $hari = Hari::where('jadwal_id', $value->jadwal_id)->where('hari', $hari_id)->first();

/*        $arr->push([
          'id' => $value->id,
          'title' => $value->jadwal->first()->title,
          'start' => $value->tanggal,
          'end' => $value->tanggal
        ]);*/
      }

      //return response()->json($arr);
    }

    public function apiGetJadwalPegawai(Request $request)
    {
      $unker = Auth::user()->unker;

      $data = PegawaiJadwal::
    }

    public function apiNameDataInduk(Request $request)
    {
      $unker = Auth::user()->unker;

      $dataInduk = DataInduk::orderBy('nama', 'asc')
                  ->select('id','nama','nip')
                  ->where(function($query) use($unker) {
                    if(!empty($unker)) {
                      $query->where('id_unker', $unker);
                    }
                  })
                  ->where(function($query) use($request) {
                    if($request->exists('phrase')) {
                      $value = "{$request->phrase}%";
                      $query->where('nama', 'like', $value)
                        ->orWhere('nip', 'like', $value);
                    }
                  })
                  ->get();

      return response()->json($dataInduk);
    }
}
