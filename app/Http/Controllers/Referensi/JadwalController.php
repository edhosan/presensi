<?php

namespace App\Http\Controllers\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Jadwal;
use App\Model\DataInduk;
use App\Model\OPD;
use Yajra\Datatables\Datatables;
use Auth;

class JadwalController extends Controller
{
    private $rules = [
      'name'  => 'required',
    ];

    public function index()
    {
      return view('referensi.jadwal_list');
    }

    public function create()
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

      return view('referensi.jadwal_form')->withOpd($opd);
    }

    public function edit($id)
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

      $jadwal = Jadwal::find($id);

      return view('referensi.jadwal_form')->withData($jadwal)->withOpd($opd);
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules);      

        $unker = Auth::user()->unker;
        $nm_unker = Auth::user()->nm_unker;
        
        if(!empty($unker)){
          $unker = $request->opd;

          $opd = OPD::where('id_unker', $unker)->where('status', 1)->first();
          $nm_unker = $opd->nama_unker;
        }

        Jadwal::create([
          'name' => $request->name,
          'title' => $request->title,
          'start' => date('Y-m-d', strtotime($request->start) ),
          'end' => date('Y-m-d', strtotime($request->end) ),
          'id_unker' => $unker,
          'nama_unker' => $nm_unker
        ]); 

        return redirect('jadwal_list')->with('success','Data berhasil disimpan!');
    }

    public function update(Request $request)
    {
      $this->validate($request, $this->rules);

      $unker = Auth::user()->unker;
      $nm_unker = Auth::user()->nm_unker;

      if(empty($unker)){
        $unker = $request->opd;

        $opd = OPD::where('id_unker', $unker)->where('status', 1)->first();
        $nm_unker = $opd->nama_unker;
      }

      $jadwal = Jadwal::find($request->id);

      $jadwal->update([
        'name' => $request->name,
        'title' => $request->title,
        'start' => date('Y-m-d', strtotime($request->start) ),
        'end' => date('Y-m-d', strtotime($request->end) ),
        'id_unker' => $unker,
        'nama_unker' => $nm_unker
      ]);

      return redirect('jadwal_list')->with('success','Data berhasil diupdate!');
    }

    public function updateAktif($id)
    {
      $jadwal = Jadwal::find($id);
      $jadwal->update([ 'aktif' => !$jadwal->aktif ]);      

      return redirect('jadwal_list')->with('success','Data berhasil diupdate!');
    }

    public function apiJadwalList()
    {
      $unker = Auth::user()->unker;

      $jadwal = Jadwal::orderBy('name','asc');

      return Datatables::of($jadwal)
      ->filter(function($query) use($unker) {
        if(!empty($unker)){
          $query->where('id_unker', $unker);
        }
      })
      ->addColumn('action', function ($data) {
          if($data->aktif)
            $icon = "icon-ok";
          else
            $icon = "icon-ban-circle";

          $action = '<a href="'.url('hari_create').'/'.$data->id.'" class="btn btn-mini btn-info"><i class="icon-plus"></i> Hari Kerja</a>'; 
          $action .= ' <a href="'.url('updateAktif').'/'.$data->id.'" class="btn btn-mini"><i class="'.$icon.'"></i></a>';
         return $action;
      })
      ->editColumn('start','{!! Carbon\Carbon::parse($start)->format("d-m-Y") !!}')
      ->editColumn('end','{!! Carbon\Carbon::parse($end)->format("d-m-Y") !!}')
      ->make(true);
    }

    public function apiDeleteJadwal(Request $request)
    {
      $data = $request->input('data');

      foreach ($data as $id) {
        $status = Jadwal::find($id)->forceDelete();
      }

      return response()->json($status);
    }


}
