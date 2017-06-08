<?php

namespace App\Http\Controllers\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Jadwal;
use App\Model\Hari;

class HariController extends Controller
{
    private $rules = [
      'hari'  => 'required',
      'jam_masuk'  => 'required|date_format:G:i',
      'jam_pulang'  => 'required|date_format:G:i'
    ];

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
      $this->validate($request, $this->rules);

      $jadwal = Jadwal::find($request->id_jadwal);

      $jadwal->hari()->create([
        'hari'  => $request->hari,
        'jam_masuk' => $request->jam_masuk,
        'jam_pulang'  => $request->jam_pulang,
        'toleransi_terlambat' => $request->toleransi_terlambat,
        'toleransi_pulang'  => $request->toleransi_pulang
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
          'val_hari'  => $item->hari
        ]);
      }

      return response()->json($data);
    }
}
