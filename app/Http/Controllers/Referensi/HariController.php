<?php

namespace App\Http\Controllers\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Jadwal;
use App\Model\Hari;

class HariController extends Controller
{
    public function create($id_jadwal)
    {
      $jadwal = Jadwal::find($id_jadwal);

      $hari = new Hari();

      return view('referensi.hari_form')
        ->withJadwal($jadwal)
        ->withHari($hari->getHari());
    }

    public function store(Request $request)
    {
      $validate = $this->validate($request, [
        'hari'  => 'required|unique:hari_kerja,hari,'.$request->hari,
        'jam_masuk'  => 'required|date_format:G:i',
        'jam_pulang'  => 'required|date_format:G:i',
        'scan_in1'  => 'required|date_format:G:i',
        'scan_in2'  => 'required|date_format:G:i',
        'scan_out1'  => 'required|date_format:G:i',
        'scan_out2'  => 'required|date_format:G:i'
      ]);

      $jadwal = Jadwal::find($request->id_jadwal);

      $jadwal->hari()->create([
        'hari'  => $request->hari,
        'jam_masuk' => $request->jam_masuk,
        'jam_pulang'  => $request->jam_pulang,
        'toleransi_terlambat' => $request->toleransi_terlambat,
        'toleransi_pulang'  => $request->toleransi_pulang,
        'scan_in1'  => $request->scan_in1,
        'scan_in2'  => $request->scan_in2,
        'scan_out1'  => $request->scan_out1,
        'scan_out2'  => $request->scan_out2
      ]);

      return redirect('jadwal_list')->with('success','Data berhasil disimpan!');
    }

    public function apiGetHari(Request $request)
    {
      $id = $request->id;

      $obj_hari = new Hari();
      $hari = Jadwal::find($id)->hari()->get();

      $data = collect();
      foreach ($hari as $item) {
        $data->push([
          'id' =>$item->id,
          'id_jadwal' => $item->jadwal_id,
          'hari'  => $obj_hari->getHari()[$item->hari],
          'jam_masuk' => $item->jam_masuk,
          'jam_pulang'  => $item->jam_pulang,
          'toleransi_terlambat' => $item->toleransi_terlambat,
          'toleransi_pulang'  => $item->toleransi_pulang,
          'val_hari'  => $item->hari,
          'absensi_masuk' => $item->scan_in1.' - '.$item->scan_in2,
          'absensi_pulang' => $item->scan_out1.' - '.$item->scan_out1
        ]);
      }

      return response()->json($data);
    }

    public function edit(Request $request)
    {
      $hari = Hari::find($request->id);

      return view('referensi.hari_form')
        ->withJadwal($hari->jadwal)
        ->withHari($hari->getHari())
        ->withData($hari);

    }

    public function update(Request $request)
    {
      $hari = Hari::find($request->id);

      $validate = $this->validate($request, [
        'jam_masuk'  => 'required|date_format:G:i',
        'jam_pulang'  => 'required|date_format:G:i',
        'scan_in1'  => 'required|date_format:G:i',
        'scan_in2'  => 'required|date_format:G:i',
        'scan_out1'  => 'required|date_format:G:i',
        'scan_out2'  => 'required|date_format:G:i'
      ]);

      $hari->update([
        'jam_masuk' => $request->jam_masuk,
        'jam_pulang'  => $request->jam_pulang,
        'toleransi_terlambat' => $request->toleransi_terlambat,
        'toleransi_pulang'  => $request->toleransi_pulang,
        'scan_in1'  => $request->scan_in1,
        'scan_in2'  => $request->scan_in2,
        'scan_out1'  => $request->scan_out1,
        'scan_out2'  => $request->scan_out2
      ]);

      return redirect('jadwal_list')->with('success','Data berhasil diupdate!');
    }

    public function delete(Request $request)
    {
      $hari = Hari::find($request->id);
      $hari->delete();

      return redirect('jadwal_list')->with('success','Data berhasil dihapus!');
    }
}
