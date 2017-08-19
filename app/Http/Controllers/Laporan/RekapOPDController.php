<?php

namespace App\Http\Controllers\Laporan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Model\DataInduk;
use App\Model\PegawaiJadwal;
use Carbon\Carbon;

class RekapOPDController extends Controller
{
    private $rules = [
      'opd' => 'required',
      'bulan' => 'required',
      'tahun' => 'required'
    ];

    public function index()
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

      return view('laporan.rekap_opd')->withOpd($opd);
    }

    public function viewReport(Request $request)
    {
      $this->validate($request, $this->rules);

      $opd = DataInduk::orderBy('nama_unker','asc')
             ->where('id_unker', $request->opd)
             ->first();

      $data = collect();
      $no = 1;
      $datainduk = DataInduk::orderBy('type','asc')
                  ->where('id_unker', $request->opd)
                  ->orderBy('id_eselon','asc')
                  ->orderBy('id_pangkat','desc')
                  ->orderBy('tmt_pangkat','desc')
                  ->get();

      foreach ($datainduk as $peg) {
        $peg_jadwal = PegawaiJadwal::leftJoin('ketidakhadiran','ketidakhadiran.id','=','peg_jadwal.ketidakhadiran_id')
                      ->leftJoin('ref_ijin','ref_ijin.id','=','ketidakhadiran.keterangan_id')
                      ->where('peg_jadwal.peg_id', $peg->id)
                      ->whereMonth('peg_jadwal.tanggal', $request->bulan)
                      ->whereYear('peg_jadwal.tanggal', $request->tahun)
                      ->select('peg_jadwal.tanggal','peg_jadwal.event_id','peg_jadwal.ketidakhadiran_id','peg_jadwal.in','peg_jadwal.out','peg_jadwal.jam_kerja','peg_jadwal.terlambat','peg_jadwal.pulang_awal',
                               'ref_ijin.name')
                      ->get();
        $arr = [];
        foreach ($peg_jadwal as $value) {
          $status = '';
          $tanggal = Carbon::parse($value->tanggal);
          if(!empty($value->event_id)){
            $status = 'L';
          }

          $arr[$tanggal->day] = $status;
        }

        $data->push([
          'no'  => $no,
          'nama'  => $peg->nama,
          'nip' => $peg->nip,
          'jadwal'  => $arr
        ]);

        $no++;
      }
      return view('laporan.rpt_opd')->withOpd($opd)->withBulan($request->bulan)->withTahun($request->tahun)->withData($data);
    }
}
