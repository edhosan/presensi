<?php

namespace App\Http\Controllers\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Jadwal;
use Yajra\Datatables\Datatables;
use Auth;

class JadwalController extends Controller
{
    private $rules = [
      'name'  => 'required',
      'id_unker'  => 'required'
    ];

    public function index()
    {
      return view('referensi.jadwal_list');
    }

    public function create()
    {
      return view('referensi.jadwal_form');
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules);

        Jadwal::create([
          'name' => $request->name,
          'title' => $request->title,
          'start' => date('Y-m-d', strtotime($request->start) ),
          'end' => date('Y-m-d', strtotime($request->end) ),
          'id_unker' => $request->id_unker,
          'nama_unker' => $request->nama_unker,
        ]);

        return redirect('kalendar_list')->with('success','Data berhasil disimpan!');
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
         return '<a href="#edit-'.$data->id.'" class="btn btn-mini btn-info"><i class="icon-plus"></i> Hari Kerja</a>';
      })
      ->make(true);
    }
}
