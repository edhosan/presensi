<?php

namespace App\Http\Controllers\Proses;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Ketidakhadiran;
use Yajra\Datatables\Datatables;
use App\Model\RefIjin;
use App\Model\DataInduk;
use Auth;
use DB;
use Carbon\Carbon;

class KetidakhadiranController extends Controller
{
    private $rules = [
      'nama'  => 'required|exists:peg_data_induk,nama',
      'ijin'  => 'required',
      'start' => 'required',
      'end' => 'required',
      'keperluan' => 'required'
    ];

    public function index()
    {
      return view('proses.ketidakhadiran_list');
    }

    public function create()
    {
      $ijin = RefIjin::orderBy('name','asc')->pluck('name','id');

      return view('proses.ketidakhadiran_form')->withIjin($ijin);
    }

    public function edit($id)
    {
      $ijin = RefIjin::orderBy('name','asc')->pluck('name','id');

      $data = Ketidakhadiran::with('pegawai')->where('id',$id)->first();

      return view('proses.ketidakhadiran_form')->withIjin($ijin)->withData($data);
    }

    public function saveCreate(Request $request)
    {
      $this->validate($request, $this->rules);

      Ketidakhadiran::create([
        'peg_id'        => $request->id_peg,
        'keterangan_id' => $request->ijin,
        'start'         => date('Y-m-d', strtotime($request->start) ),
        'end'           => date('Y-m-d', strtotime($request->end) ),
        'jam_start'     => $request->jam_start,
        'jam_end'       => $request->jam_end,
        'keperluan'     => $request->keperluan
      ]);

      return redirect()->route('ketidakhadiran.list')->with('success','Data berhasil disimpan!');
    }

    public function saveUpdate(Request $request)
    {
      $this->validate($request, $this->rules);

      $ketidakhadiran = Ketidakhadiran::find($request->id);

      $ketidakhadiran->update([
        'peg_id'        => $request->id_peg,
        'keterangan_id' => $request->ijin,
        'start'         => date('Y-m-d', strtotime($request->start) ),
        'end'           => date('Y-m-d', strtotime($request->end) ),
        'jam_start'     => $request->jam_start,
        'jam_end'       => $request->jam_end,
        'keperluan'     => $request->keperluan
      ]);

      return redirect()->route('ketidakhadiran.list')->with('success','Data berhasil disimpan!');
    }

    public function apiListKetidakhadiran()
    {
      $unker = Auth::user()->unker;

      $peg_ijin_list = DataInduk::join('ketidakhadiran','ketidakhadiran.peg_id','=','peg_data_induk.id')
                       ->join('ref_ijin','ketidakhadiran.keterangan_id','=','ref_ijin.id')
                       ->select(DB::raw('peg_data_induk.id as peg_id'),'peg_data_induk.id_finger','peg_data_induk.nip','peg_data_induk.nama',
                         'peg_data_induk.gelar_depan','peg_data_induk.gelar_belakang','peg_data_induk.id_unker','peg_data_induk.nama_unker',
                         'peg_data_induk.nama_subunit','peg_data_induk.nama_jabatan','peg_data_induk.golru','peg_data_induk.pangkat',
                         'ketidakhadiran.start','ketidakhadiran.end','ketidakhadiran.jam_start','ketidakhadiran.jam_end','ketidakhadiran.id',
                         'ketidakhadiran.keperluan',DB::raw('ref_ijin.name as status') );

      return Datatables::of($peg_ijin_list)
            ->filter(function($query) use($unker) {
              if(!empty($unker)){
                $query->where('id_unker', $unker);
              }
            })
            ->editColumn('nama_jabatan','{{ $nama_jabatan." ".$nama_subunit }}')
            ->editColumn('pangkat','{{ isset($pangkat)?$pangkat." (".$golru.")":"" }}')
            ->editColumn('tanggal','{{ date("Y-m-d", strtotime($start))." s/d ".date("Y-m-d", strtotime($end)) }}')
            ->editColumn('jumlah', function($peg_ijin_list){
              $start = Carbon::parse($peg_ijin_list->start);
              $end =  Carbon::parse($peg_ijin_list->end);
              $interval = $end->diffInDays($start);

              $time1 = Carbon::parse($peg_ijin_list->jam_start);
              $time2 =  Carbon::parse($peg_ijin_list->jam_end);
              $interval_time = $time2->diffInHours($time1);

              if($interval > 0) {
                return $interval.' hari';
              }else{
                return $interval_time.' jam';
              }
            })
            ->make(true);
    }

    public function apiDeleteKetidakhadiran(Request $request)
    {
      $data = $request->input('data');

      foreach ($data as $id) {
        $status = Ketidakhadiran::find($id)->forceDelete();
      }

      return response()->json($status);
    }
}
