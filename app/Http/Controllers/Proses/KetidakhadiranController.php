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
use App\Model\Jadwal;
use App\Library\MasterPresensi;

class KetidakhadiranController extends Controller
{
    private $rules = [
      'pegawai'  => 'required',
      'ijin'  => 'required',
      'start' => 'required',
      'end' => 'required|after_or_equal:start',
      'keperluan' => 'required',
      'file' => 'mimes:pdf,doc,docx,jpeg'
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

      $peg_jadwal = PegawaiJadwal::where('peg_id', $request->pegawai)
                                  ->where('tanggal', date('Y-m-d', strtotime($request->start) ))
                                  ->first();
      if(empty($peg_jadwal)){
        return back()->withInput()->withError('Gagal simpan data, Jadwal kerja pegawai belum ada!');
      }

      $file_name = '';
      if($request->hasFile('file')){
        $file_name = $request->pegawai.'_'.date('Ymd', strtotime($request->start)).'.'.$request->file->extension();

        $request->file('file')->move('public/catalog/surat/tidak_hadir/', $file_name);
      }

      $jumlah_hari = $this->getJumlahHariIzin($request->pegawai, Carbon::parse($request->start), Carbon::parse($request->end));

      $ketidakhadiran = Ketidakhadiran::create([
        'peg_id'        => $request->pegawai,
        'keterangan_id' => $request->ijin,
        'start'         => date('Y-m-d', strtotime($request->start) ),
        'end'           => date('Y-m-d', strtotime($request->end) ),
        'jam_start'     => $request->jam_start,
        'jam_end'       => $request->jam_end,
        'keperluan'     => $request->keperluan,
        'filename'      => $file_name,
        'jml_hari'      => $jumlah_hari
      ]);

      //$this->updateKalkulasi($request->pegawai, $request->start, $request->end, $ketidakhadiran->id);

      return redirect()->route('ketidakhadiran.list')->with('success','Data berhasil disimpan!');
    }

    public function saveUpdate(Request $request)
    {
      $this->validate($request, $this->rules);

      $ketidakhadiran = Ketidakhadiran::find($request->id);

      $file_name = $ketidakhadiran->filename;
      if($request->hasFile('file')){
        $file_name = $request->pegawai.'_'.date('Ymd', strtotime($request->start)).'.'.$request->file->extension();

        File::delete('public/catalog/surat/tidak_hadir/'.$ketidakhadiran->filename);

        $request->file('file')->move('public/catalog/surat/tidak_hadir/', $file_name);
      }

      $jumlah_hari = $this->getJumlahHariIzin($request->pegawai, Carbon::parse($request->start), Carbon::parse($request->end));

      $ketidakhadiran->update([
        'peg_id'        => $request->pegawai,
        'keterangan_id' => $request->ijin,
        'start'         => date('Y-m-d', strtotime($request->start) ),
        'end'           => date('Y-m-d', strtotime($request->end) ),
        'jam_start'     => $request->jam_start,
        'jam_end'       => $request->jam_end,
        'keperluan'     => $request->keperluan,
        'filename'      => $file_name,
        'jml_hari'      => $jumlah_hari
      ]);

      PegawaiJadwal::where('ketidakhadiran_id', $request->id)->update([
        'ketidakhadiran_id' => '','status' => '', 'in'=>'00:00:00','out'=>'00:00:00','jam_kerja' => '00:00:00','terlambat' => '00:00:00', 'pulang_awal' => '00:00:00','scan_1' => '00:00:00', 'scan_2' => '00:00:00'
      ]);

      //$this->updateKalkulasi($request->pegawai, $request->start, $request->end, $ketidakhadiran->id);

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
                         'ketidakhadiran.keperluan',DB::raw('ref_ijin.name as status'), 'ketidakhadiran.jml_hari' );

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
           
              return $peg_ijin_list->jml_hari.' hari';
            })
            ->make(true);
    }

    public function apiDeleteKetidakhadiran(Request $request)
    {
      $data = $request->input('data');

      foreach ($data as $id) {
        $ketidakhadiran = Ketidakhadiran::find($id);
        $status = $ketidakhadiran->forceDelete();
        PegawaiJadwal::where('ketidakhadiran_id', $id)->update([
            'ketidakhadiran_id' => null,
            'status' => null, 
            'in'=>'00:00:00',
            'out'=>'00:00:00',
            'jam_kerja' => '00:00:00',
            'terlambat' => '00:00:00', 
            'pulang_awal' => '00:00:00',
            'scan_1' => '00:00:00', 
            'scan_2' => '00:00:00'
         ]);

        File::delete('catalog/surat/tidak_hadir/'.$ketidakhadiran->filename);
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

   private function getJumlahHariIzin($peg_id, $start, $end)
    {
      $date = dateRange($start, $end);
      $count_hari = 0;
      foreach ($date as $value) {
        $tanggal = Carbon::parse($value);
        $peg_jadwal = PegawaiJadwal::where('peg_id', $peg_id)->where('tanggal','=',$value)->first();
        $jadwal = Jadwal::where('id', $peg_jadwal->jadwal_id)->first();
        $hari_id = $tanggal->format('N');
        $hari = $jadwal->hari()->where('hari', $hari_id)->first();
        if(!empty($hari) && $peg_jadwal->event_id == null){
          $count_hari = $count_hari + 1;
        }
      }

      return $count_hari;     
    }
}
