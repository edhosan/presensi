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
    'file' => 'required|mimes:pdf'
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

  public function saveCreate(Request $request)
  {
      $this->validate($request, $this->rules);

      $file_name = '';
      if($request->hasFile('file')){
        $file_name = $request->pegawai.'_'.date('Ymd', strtotime($request->tanggal)).'.'.$request->file->extension();

        $request->file('file')->move('catalog/surat/dispensasi/', $file_name);
      }

      $dispensasi = Dispensasi::create([
        'peg_id'        => $request->pegawai,
        'tanggal'         => date('Y-m-d', strtotime($request->tanggal) ),
        'koreksi_jam_masuk'     => $request->koreksi_jam_masuk,
        'koreksi_jam_pulang'       => $request->koreksi_jam_pulang,
        'alasan'     => $request->alasan,
        'filename'      => $file_name
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
    $in = Carbon::parse($data->koreksi_jam_masuk);
    $out = Carbon::parse($data->koreksi_jam_pulang);
    $jam_kerja = Carbon::createFromTime(0, 0, 0);
    $status_hadir = '';

    $scan_in1 = Carbon::parse($hari->scan_in1);
    $scan_in2 = Carbon::parse($hari->scan_in2);
    $scan_out1 = Carbon::parse($hari->scan_out1);
    $scan_out2 = Carbon::parse($hari->scan_out2);
    $terlambat = Carbon::createFromTime(0, 0, 0);
    $pulang_awal = Carbon::createFromTime(0, 0, 0);

    if(!empty($data->koreksi_jam_masuk)){
      $out = Carbon::parse($peg_jadwal->out);

      if( $in->gt($jm) ) {
        $terlambat = $in->diff($jm)->format('%H:%I:%S');
        $status_hadir = 'HT';
      }

      if($in->toTimeString() != '00:00:00' && $out->toTimeString() != '00:00:00'){
        $jam_kerja = $out->diff($in)->format('%H:%I:%S');
      }

      $peg_jadwal->update([
        'in'  => $in->toTimeString(),
        'terlambat' => $terlambat,
        'status'  => $status_hadir,
        'jam_kerja' => $jam_kerja,
      ]);
    }

    if(!empty($data->koreksi_jam_pulang)){
      $in = Carbon::parse($peg_jadwal->in);

      if($in->toTimeString() != '00:00:00' && $out->toTimeString() != '00:00:00'){
        $jam_kerja = $out->diff($in)->format('%H:%I:%S');
      }

      if($out->lt($jp)) {
        $pulang_awal = $jp->diff($out)->format('%H:%I:%S');
        $status_hadir = 'HP';
      }

      $peg_jadwal->update([
        'out'  => $out->toTimeString(),
        'pulang_awal' => $pulang_awal,
        'jam_kerja' => $jam_kerja,
        'status'  => $status_hadir
      ]);
    }

    if($status_hadir != 'HT' && $status_hadir != 'HP'){
      $peg_jadwal->update(['status' => 'H']);
    }

  }
}
