<?php

namespace App\Http\Controllers\Proses;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\DataInduk;
use App\Model\Dispensasi;
use App\Model\PegawaiJadwal;
use App\Model\Hari;
use Yajra\Datatables\Datatables;
use Auth;
use Carbon\Carbon;
use File;

class DispensasiController extends Controller
{
  private $rules = [
    'pegawai'  => 'required',
    'tanggal'  => 'required',
    'alasan' => 'required',
    'koreksi_jam' => 'required|date_format:G:i',
    'file' => 'mimes:pdf,doc,docx,jpeg'
  ];

  public function index()
  {
    return view('proses.dispensasi_list');
  }

  public function create()
  {
      $unker = Auth::user()->unker;

      $pegawai = DataInduk::where(function($query) use($unker) {
                   if(!empty($unker)) {
                     $query->where('id_unker',$unker);
                   }
                 })
                ->pluck('nama','id');

      return view('proses.dispensasi_form')->withPegawai($pegawai);
  }

  public function edit($id)
  {
    $unker = Auth::user()->unker;

    $pegawai = DataInduk::where(function($query) use($unker) {
                 if(!empty($unker)) {
                   $query->where('id_unker',$unker);
                 }
               })
              ->pluck('nama','id');

    $data = Dispensasi::find($id);

    return view('proses.dispensasi_form')->withPegawai($pegawai)->withData($data);
  }

  public function saveCreate(Request $request)
  {

      $this->validate($request, $this->rules);     

      $peg_jadwal = PegawaiJadwal::where('peg_id', $request->pegawai)
                                  ->where('tanggal', date('Y-m-d', strtotime($request->tanggal) ))
                                  ->first();
      if(empty($peg_jadwal)){
        return back()->withInput()->withError('Gagal simpan data, Jadwal kerja pegawai belum ada!');
      }

      $file_name = '';
      if($request->hasFile('file')){
        $file_name = $request->pegawai.'_'.date('Ymd', strtotime($request->tanggal)).'.'.$request->file->extension();

        $request->file('file')->move('public/catalog/surat/dispensasi/', $file_name);
      }

      $dispensasi = Dispensasi::create([
        'peg_id'        => $request->pegawai,
        'tanggal'         => date('Y-m-d', strtotime($request->tanggal) ),
        'koreksi_jam'     => $request->koreksi_jam,
        'alasan'     => $request->alasan,
        'filename'      => $file_name
      ]);

      $this->updateKalkulasi($request);

      return redirect()->route('dispensasi.list')->with('success','Data berhasil disimpan!');
  }

  public function saveEdit(Request $request)
  {
    $this->validate($request, $this->rules);

    $dispensasi = Dispensasi::find($request->id);

    $file_name = $dispensasi->filename;
    if($request->hasFile('file')){
      $file_name = $request->pegawai.'_'.date('Ymd', strtotime($request->tanggal)).'.'.$request->file->extension();

      File::delete('public/catalog/surat/dispensasi/'.$dispensasi->filename);

      $request->file('file')->move('public/catalog/surat/dispensasi/', $file_name);
    }

    $dispensasi->update([
      'peg_id'          => $request->pegawai,
      'tanggal'         => date('Y-m-d', strtotime($request->tanggal) ),
      'koreksi_jam'     => $request->koreksi_jam,
      'alasan'          => $request->alasan,
      'filename'        => $file_name
    ]);

    $this->updateKalkulasi($request);

    return redirect()->route('dispensasi.list')->with('success','Data berhasil disimpan!');
  }

  private function updateKalkulasi($data)
  {
    $tanggal = Carbon::parse($data->tanggal);

    $peg_jadwal = PegawaiJadwal::where('tanggal', date('Y-m-d', strtotime($tanggal)) )
              ->where('peg_id', $data->pegawai)
              ->first();

    $hari_id = $tanggal->format('N');
    $hari = Hari::where('hari', $hari_id)
            ->where('jadwal_id',$peg_jadwal->jadwal_id)
            ->first();

    $jm = Carbon::parse($hari->jam_masuk);
    $toleransi_terlambat = Carbon::parse($hari->toleransi_terlambat);
    $jm = $jm->addMinutes($toleransi_terlambat->minute);
    $jp = Carbon::parse($hari->jam_pulang);
    $toleransi_pulang = Carbon::parse($hari->toleransi_pulang);
    $jp = $jp->subMinutes($toleransi_pulang->minute);
    $in = Carbon::createFromTime(0, 0, 0);
    $out = Carbon::createFromTime(0, 0, 0);
    $jam_kerja = Carbon::createFromTime(0, 0, 0);
    $status_hadir = '';

    $scan_in1 = Carbon::parse($hari->scan_in1);
    $scan_in2 = Carbon::parse($hari->scan_in2);
    $scan_out1 = Carbon::parse($hari->scan_out1);
    $scan_out2 = Carbon::parse($hari->scan_out2);
    $terlambat = Carbon::createFromTime(0, 0, 0);
    $pulang_awal = Carbon::createFromTime(0, 0, 0);

    if(!empty($data->koreksi_jam)){
      $time = Carbon::parse($data->koreksi_jam);

      if($time->gte($scan_in1) && $time->lte($scan_in2)){
        $in = $time;
        if( $time->gt($jm) ) {
          $terlambat = $time->diff($jm)->format('%H:%I:%S');
          $status_hadir = 'HT';
        }

        $peg_jadwal->update([
          'in'  => $time->toTimeString(),
          'terlambat' => $terlambat,
          'status'  => $status_hadir
        ]);
      }

      if($time->gte($scan_out1) && $time->lte($scan_out2) ){
        $out = $time;
        if($in->toTimeString() != '00:00:00' && $time->toTimeString() != '00:00:00'){
          $jam_kerja = $time->diff($in)->format('%H:%I:%S');
        }

        if($time->lt($jp)) {
          $pulang_awal = $jp->diff($time)->format('%H:%I:%S');
          $status_hadir = 'HP';
        }

        $peg_jadwal->update([
          'out'  => $time->toTimeString(),
          'pulang_awal' => $pulang_awal,
          'jam_kerja' => $jam_kerja,
          'status'  => $status_hadir
        ]);
      }

      if($status_hadir != 'HT' && $status_hadir != 'HP'){
        PegawaiJadwal::find($peg_jadwal->id)->update(['status' => 'H']);
      }

    }

  }

  public function apiListDispensasi(Request $request)
  {
    $unker = Auth::user()->unker;

    $dispensasi = Dispensasi::join('peg_data_induk','peg_data_induk.id','=','Dispensasi.peg_id')
                  ->where(function($query) use($request) {
                    if($request->has('search')) {
                      $query->where('peg_data_induk.nama', 'like', $request->search['value'].'%');
                      $query->OrWhere('peg_data_induk.nip', 'like', $request->search['value'].'%');
                      $query->OrWhere('peg_data_induk.nama_unker', 'like', $request->search['value'].'%');
                    }
                  })
                  ->select('Dispensasi.id','peg_data_induk.nip','peg_data_induk.nama','peg_data_induk.nama_unker','Dispensasi.tanggal',
                    'Dispensasi.koreksi_jam','Dispensasi.alasan');

    return Datatables::of($dispensasi)
            ->filter(function($query) use($unker) {
              if(!empty($unker)){
                $query->where('id_unker', $unker);
              }
            })
            ->editColumn('tanggal','{{ date("d-m-Y", strtotime($tanggal)) }}')
            ->make(true);
  }

  public function apiDeleteDispensasi(Request $request)
  {
    $data = $request->input('data');

    foreach ($data as $id) {
      $dispensasi = Dispensasi::find($id);
       PegawaiJadwal::where('dispensasi_id', $id)->update([
            'dispensasi_id' => 0,
            'status' => null, 
            'in'=>'00:00:00',
            'out'=>'00:00:00',
            'jam_kerja' => '00:00:00',
            'terlambat' => '00:00:00', 
            'pulang_awal' => '00:00:00',
            'scan_1' => '00:00:00', 
            'scan_2' => '00:00:00'
         ]);

      File::delete('catalog/surat/dispensasi/'.$dispensasi->filename);

      $status = $dispensasi->forceDelete();
    }

    return response()->json($status);
  }

}
