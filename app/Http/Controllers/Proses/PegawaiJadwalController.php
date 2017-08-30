<?php

namespace App\Http\Controllers\Proses;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Jadwal;
use Auth;
use App\Model\DataInduk;
use Carbon\Carbon;
use App\Model\PegawaiJadwal;
use Yajra\Datatables\Datatables;
use App\Model\Event;
use App\Model\Hari;
use Debugbar;

class PegawaiJadwalController extends Controller
{
    private $rules = [
      'pegawai'  => 'required',
      'jadwal'  => 'required'
    ];

    public function index()
    {
       return view('proses.peg_jadwal_list');
    }

    public function detail($id)
    {
      return view('proses.peg_jadwal_detail')->withId($id);
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

      return view('proses.peg_jadwal_form')->withJadwal($jadwal)->withPegawai([]);
    }

    public function edit($peg_id, $jadwal_id)
    {
      $unker = Auth::user()->unker;

      $pegawai = DataInduk::find($peg_id);

      $peg_jadwal = Jadwal::find($jadwal_id);

      $jadwal = Jadwal::orderBy('name','asc')
                  ->where(function($query) use($unker) {
                    if(!empty($unker)) {
                      $query->where('id_unker',$unker);
                    }
                  })
                  ->pluck('name','id');

      return view('proses.peg_jadwal_form')
            ->withJadwal($jadwal)
            ->withData(array(
              'peg_jadwal'  => $peg_jadwal,
              'pegawai'     => $pegawai
            ));
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

      return redirect('peg_jadwal_list')->with('success','Data berhasil disimpan!');
    }

    public function deleteJadwal($jadwal_id)
    {
      $peg_jadwal = PegawaiJadwal::where('jadwal_id', $jadwal_id)->forceDelete();

      return redirect('peg_jadwal_list')->with('success','Data berhasil disimpan!');
    }

    public function apiJadwalDetail(Request $request)
    {
      $peg_jadwal = PegawaiJadwal::join('jadwal_kerja','jadwal_kerja.id','=','peg_jadwal.jadwal_id')
                    ->select('jadwal_kerja.*')
                    ->where('peg_id',$request->id)
                    ->groupBy('peg_jadwal.jadwal_id')
                    ->get();

      return response()->json($peg_jadwal);
    }

    public function apiGetJadwalPegawaiDetail(Request $request)
    {
      $arr = collect();

      $jadwal = PegawaiJadwal::join('jadwal_kerja','jadwal_kerja.id','=','peg_jadwal.jadwal_id')
                ->select('jadwal_kerja.id','name','title','start','end')
                ->where('peg_jadwal.peg_id', $request->peg_id)
                ->groupBy('peg_jadwal.jadwal_id')
                ->get();

      foreach ($jadwal as $value) {
        $arr->push([
          'id' => $value->id,
          'title' => $value->name,
          'start' => $value->start,
          'end' => $value->end,
          'color' => '#bf80ff'
        ]);
      }

      return response()->json($arr);
    }

    public function apiGetPegawaiHariKerja(Request $request)
    {
      $peg_jadwal = PegawaiJadwal::join('jadwal_kerja','jadwal_kerja.id','=','peg_jadwal.jadwal_id')
                    ->leftJoin('event','event.id','=','peg_jadwal.event_id')
                    ->select('peg_jadwal.id','peg_jadwal.tanggal','peg_jadwal.peg_id','peg_jadwal.jadwal_id',
                      'jadwal_kerja.name','jadwal_kerja.title','jadwal_kerja.start','jadwal_kerja.end',
                      'event.title as event')
                    ->where('peg_id', $request->peg_id)
                    ->get();

      $arr = collect();
      $hari_arr = collect();
      foreach ($peg_jadwal as  $value) {
        $tanggal = Carbon::parse($value->tanggal);
        $hari_id = $tanggal->format('N');
        $hari = Hari::where('hari', $hari_id)
                ->where('jadwal_id',$value->jadwal_id)
                ->first();
        $hari_arr->push($hari);

        if(!isset($value->event)){
          if(isset($hari)){
            $arr->push([
              'title' => 'Jam Kerja: '.$hari->jam_masuk.' - '.$hari->jam_pulang,
              'start' => $value->tanggal
            ]);
          }
        }
      }

      return response()->json($arr);
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

    public function apiGetJadwalPegawai()
    {
      $unker = Auth::user()->unker;

      $datainduk = DataInduk::orderBy('type','asc')
                  ->orderBy('id_eselon','asc')
                  ->orderBy('id_pangkat','desc')
                  ->orderBy('tmt_pangkat','desc');

      return Datatables::of($datainduk)
        ->filter(function($query) use($unker) {
          if(!empty($unker)){
            $query->where('id_unker', $unker);
          }
        })
        ->editColumn('pangkat','{{ isset($pangkat)?$pangkat." (".$golru.")":"" }}')
        ->editColumn('nama_jabatan','{{ $nama_jabatan." ".$nama_subunit }}')
        ->addColumn('action', function ($data) {
           return '<a href="'.url('peg_jadwal_detail').'/'.$data->id.'"><i class="icon-eye-open"></i></a>';
        })
        ->make(true);
    }

    public function apiDeleteAll(Request $request)
    {
      $data = $request->input('data');

      foreach ($data as $value) {
        $peg_jadwal = PegawaiJadwal::where('peg_id', $value)->forceDelete();
      }

      return response()->json($peg_jadwal);
    }
}
