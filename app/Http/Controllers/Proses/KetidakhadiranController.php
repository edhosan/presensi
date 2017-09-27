<?php

namespace App\Http\Controllers\Proses;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Ketidakhadiran;
use Yajra\Datatables\Datatables;
use App\Model\RefIjin;
use App\Model\DataInduk;
use App\Model\PegawaiJadwal;
use Auth;
use DB;
use Carbon\Carbon;
use File;

class KetidakhadiranController extends Controller
{
    private $rules = [
    /*  'opd' => 'required',*/
      'pegawai'  => 'required',
      'ijin'  => 'required',
      'start' => 'required|before:end',
      'end' => 'required',
      'keperluan' => 'required',
      'file' => 'mimes:pdf'
    ];

    public function index()
    {
      return view('proses.ketidakhadiran_list');
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

      $ijin = RefIjin::orderBy('name','asc')->pluck('name','id');

      return view('proses.ketidakhadiran_form')->withIjin($ijin)->withOpd($opd)->withPegawai([]);;
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

      $ijin = RefIjin::orderBy('name','asc')->pluck('name','id');

      $data = Ketidakhadiran::with('pegawai')->where('id',$id)->first();

      $pegawai = DataInduk::where('id_unker', $data->pegawai->id_unker)->pluck('nama','id');

      return view('proses.ketidakhadiran_form')->withIjin($ijin)->withData($data)->withOpd($opd)->withPegawai($pegawai);
    }

    public function saveCreate(Request $request)
    {
      $this->validate($request, $this->rules);

      $file_name = '';
      if($request->hasFile('file')){
        $file_name = $request->pegawai.'_'.date('Ymd', strtotime($request->start)).'.'.$request->file->extension();

        $request->file('file')->move('catalog/surat/tidak_hadir/', $file_name);
      }

      $ketidakhadiran = Ketidakhadiran::create([
        'peg_id'        => $request->pegawai,
        'keterangan_id' => $request->ijin,
        'start'         => date('Y-m-d', strtotime($request->start) ),
        'end'           => date('Y-m-d', strtotime($request->end) ),
        'jam_start'     => $request->jam_start,
        'jam_end'       => $request->jam_end,
        'keperluan'     => $request->keperluan,
        'filename'      => $file_name
      ]);

      $this->updateKalkulasi($request->pegawai, $request->start, $request->end, $ketidakhadiran->id);

      return redirect()->route('ketidakhadiran.list')->with('success','Data berhasil disimpan!');
    }

    public function saveUpdate(Request $request)
    {
      $this->validate($request, $this->rules);

      $ketidakhadiran = Ketidakhadiran::find($request->id);

      $file_name = '';
      if($request->hasFile('file')){
        $file_name = $request->pegawai.'_'.date('Ymd', strtotime($request->start)).'.'.$request->file->extension();

        File::delete('catalog/surat/'.$ketidakhadiran->filename);

        $request->file('file')->move('catalog/surat/tidak_hadir/', $file_name);
      }

      $ketidakhadiran->update([
        'peg_id'        => $request->pegawai,
        'keterangan_id' => $request->ijin,
        'start'         => date('Y-m-d', strtotime($request->start) ),
        'end'           => date('Y-m-d', strtotime($request->end) ),
        'jam_start'     => $request->jam_start,
        'jam_end'       => $request->jam_end,
        'keperluan'     => $request->keperluan,
        'filename'      => $file_name
      ]);

      $this->updateKalkulasi($request->pegawai, $request->start, $request->end, $ketidakhadiran->id);

      return redirect()->route('ketidakhadiran.list')->with('success','Data berhasil disimpan!');
    }

    public function apiListKetidakhadiran(Request $request)
    {
      $unker = Auth::user()->unker;

      $peg_ijin_list = DataInduk::join('ketidakhadiran','ketidakhadiran.peg_id','=','peg_data_induk.id')
                       ->join('ref_ijin','ketidakhadiran.keterangan_id','=','ref_ijin.id')
                       ->where(function($query) use($request) {
                         if($request->has('search')) {
                           $query->where('peg_data_induk.nama', 'like', $request->search['value'].'%');
                           $query->OrWhere('peg_data_induk.nip', 'like', $request->search['value'].'%');
                         }
                       })
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
            ->editColumn('tanggal','{{ date("d-m-Y", strtotime($start))." s/d ".date("d-m-Y", strtotime($end)) }}')
            ->editColumn('jumlah', function($peg_ijin_list){
              $start = Carbon::parse($peg_ijin_list->start);
              $end =  Carbon::parse($peg_ijin_list->end);
              $interval = $end->diffInDays($start) + 1;

              $time1 = Carbon::parse($peg_ijin_list->jam_start);
              $time2 =  Carbon::parse($peg_ijin_list->jam_end);
              $interval_time = $time2->diffInHours($time1);

              if($interval > 1) {
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
        $ketidakhadiran = Ketidakhadiran::find($id);

        File::delete('catalog/surat/'.$ketidakhadiran->filename);

        $status = $ketidakhadiran->forceDelete();
      }

      return response()->json($status);
    }

    private function updateKalkulasi($peg_id, $start, $end, $ketidakhadiran_id)
    {
      PegawaiJadwal::where('ketidakhadiran_id', $ketidakhadiran_id)
                    ->update(['ketidakhadiran_id' => 0]);

      PegawaiJadwal::where('peg_id', $peg_id)
                    ->where('tanggal','>=', date('Y-m-d', strtotime($start) ))
                    ->where('tanggal','<=', date('Y-m-d', strtotime($end) ))
                    ->update(['ketidakhadiran_id' => $ketidakhadiran_id]);

    }
}
