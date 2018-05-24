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
      $unker = Auth::user()->unker;

      $jadwal = Jadwal::aktif()->orderBy('name','asc')
            ->where(function($query) use($unker) {
              if(!empty($unker)) {
                $query->where('id_unker',$unker)->orwhereNull('id_unker');
              }
            })
            ->pluck('name','id');

       return view('proses.peg_jadwal_split')->withJadwal($jadwal);
    }

    public function detail($id)
    {
      return view('proses.peg_jadwal_detail')->withId($id);
    }

    public function edit($peg_id, $jadwal_id)
    {
      $unker = Auth::user()->unker;

      $pegawai = DataInduk::find($peg_id);

      $peg_jadwal = Jadwal::find($jadwal_id);

      $jadwal = Jadwal::orderBy('name','asc')
                  ->where(function($query) use($unker) {
                    if(!empty($unker)) {
                      $query->where('id_unker',$unker)->orwhereNull('id_unker');
                    }
                  })
                  ->pluck('name','id');

      return view('proses.peg_jadwal_form')
            ->withJadwal($jadwal)
            ->withPegawai($pegawai)
            ->withData(array(
              'peg_jadwal'  => $peg_jadwal,
              'pegawai'     => $pegawai
            ));
    }

    public function deleteJadwal($peg_id, $jadwal_id)
    {
      $peg_jadwal = PegawaiJadwal::where('peg_id',$peg_id)->where('jadwal_id', $jadwal_id)->forceDelete();

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

    public function apiGetJadwalPegawai(Request $request)
    {
      $unker = Auth::user()->unker;

     $datainduk = DataInduk::orderBy('type','asc')
                  ->where(function($query) use($request) {
                    if($request->has('search')) {
                      $query->where('nama', 'like', $request->search['value'].'%');
                      $query->OrWhere('nip', 'like', $request->search['value'].'%');
                      $query->OrWhere('nama_unker', 'like', $request->search['value'].'%');
                    }
                  })
                  ->join('peg_jadwal','peg_jadwal.peg_id','=','peg_data_induk.id')
                  ->where('peg_jadwal.jadwal_id','=',$request->input('jadwal_id'))
                  ->select('peg_data_induk.id','peg_data_induk.nip','peg_data_induk.nama','peg_data_induk.nama_unker')
                  ->groupBy('peg_data_induk.id')
                  ->orderBy('id_eselon','asc')
                  ->orderBy('id_pangkat','desc')
                  ->orderBy('tmt_pangkat','desc');

      return Datatables::of($datainduk)
        ->filter(function($query) use($unker) {
          if(!empty($unker)){
            $query->where('id_unker', $unker);
          }
        })
        ->addColumn('action', function ($data) {
          $action = ' <a href="'.url('peg_jadwal_detail').'/'.$data->id.'" class="btn btn-mini"><i class="icon-calendar"></i></a>';
         return $action;
        })     
        ->make(true);
    }

    public function apiAddJadwal(Request $request)
    {
      $jadwal = Jadwal::where('id', $request->jadwal_id)->first();
      $date = dateRange($jadwal->start, $jadwal->end);

      PegawaiJadwal::whereIn('peg_id', $request->peg_id)->where('tanggal','>=',$jadwal->start)->where('tanggal','<=',$jadwal->end)->forceDelete();

      $data = [];
      foreach ($date as  $value) {
        $tanggal = Carbon::parse($value);
        $event = Event::where('start_date', '=',$value)->orWhere('end_date','=',$value)->first();

        foreach ($request->peg_id as $p) {
          $data[] = [
            'tanggal' => $tanggal,
            'peg_id'  => $p,
            'jadwal_id' => $request->jadwal_id,
            'event_id' => isset($event)?$event->id:null
          ];       
        }      
      }
      PegawaiJadwal::insert($data);

      return response()->json(true);
    }

    public function apiDeleteJadwal(Request $request)
    {
      $jadwal = Jadwal::where('id', $request->jadwal_id)->first();
      PegawaiJadwal::whereIn('peg_id', $request->peg_id)->where('tanggal','>=',$jadwal->start)->where('tanggal','<=',$jadwal->end)->forceDelete();

      return response()->json(true);
    }

    public function apiHariKerja(Request $request)
    {
      $jadwal = Jadwal::where('id', $request->jadwal_id)->first();
      $hari_kerja = $jadwal->hari()->orderBy('hari')->get();

      $obj_hari = new Hari();
      $data = collect();
      foreach ($hari_kerja as $item) {
       $data->push([
              'id' =>$item->id,
              'id_jadwal' => $item->jadwal_id,
              'hari'  => $obj_hari->getHari()[$item->hari],
              'jam_masuk' => date('H:i', strtotime($item->jam_masuk)),
              'jam_pulang'  => date('H:i', strtotime($item->jam_pulang)),
              'toleransi_terlambat' => date('H:i', strtotime($item->toleransi_terlambat)),
              'toleransi_pulang'  => date('H:i', strtotime($item->toleransi_pulang)),
              'val_hari'  => $item->hari,
              'absensi_masuk' => date('H:i', strtotime($item->scan_in1)).' - '. date("H:i", strtotime($item->scan_in2)),
              'absensi_pulang' => date('H:i', strtotime($item->scan_out1)).' - '.date('H:i', strtotime($item->scan_out2)),
              'absensi_siang_1' => date('H:i', strtotime($item->absensi_siang_out_1)).' - '.date('H:i', strtotime($item->absensi_siang_out_2)),
              'absensi_siang_2' => date('H:i', strtotime($item->absensi_siang_in_1)).' - '.date('H:i', strtotime($item->absensi_siang_in_2)) 
            ]);
      }

      return view('partial.hari_kerja')->withHari($data);    
    }
}
