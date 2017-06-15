<?php

namespace App\Http\Controllers\Proses;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Jadwal;
use Auth;
use App\Model\DataInduk;
use Carbon\Carbon;
use App\Model\PegawaiJadwal;

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
        $peg_jadwal = new PegawaiJadwal();
        $peg_jadwal->tanggal = $value;
        $peg_jadwal->peg_id = $request->peg_id;
        $peg_jadwal->jadwal_id = $request->jadwal;
        $peg_jadwal->hari_id = $tanggal->format('N');
        $data[] = $peg_jadwal;
      }

      dd($data);


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
