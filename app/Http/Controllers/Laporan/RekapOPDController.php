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
                      ->select('peg_jadwal.tanggal','peg_jadwal.event_id','peg_jadwal.ketidakhadiran_id','peg_jadwal.in','peg_jadwal.out','peg_jadwal.jam_kerja','peg_jadwal.terlambat','peg_jadwal.pulang_awal','peg_jadwal.status',
                               'ref_ijin.name')
                      ->get();
        $arr = [];$tot = [];
        $t_h = 0; $t_ht = 0; $t_hp = 0; $t_a = 0; $t_i = 0; $t_c = 0; $t_s = 0; $t_dl = 0; $t_tb = 0; $t_l = 0;
        foreach ($peg_jadwal as $value) {
          $status = '';
          $tanggal = Carbon::parse($value->tanggal);
          if($value->status == 'H') $t_h++;
          if($value->status == 'HT') $t_ht++;
          if($value->status == 'HP') $t_hp++;
          if($value->status == 'A') $t_a++;
          if($value->status == 'I') $t_i++;
          if($value->status == 'C') $t_c++;
          if($value->status == 'S') $t_s++;
          if($value->status == 'DL') $t_dl++;
          if($value->status == 'TB') $t_tb++;
          if($value->status == 'L') $t_l++;

          $arr[$tanggal->day] = $value->status;
        }
        $tot = [
          'H' => $t_h, 'HT' => $t_ht, 'HP' => $t_hp, 'A' => $t_a, 'I' => $t_i, 'C' => $t_c, 'S' => $t_s, 'DL' => $t_dl, 'TB' => $t_tb, 'L' => $t_l
        ];

        $data->push([
          'no'  => $no,
          'nama'  => $peg->nama,
          'nip' => $peg->nip,
          'jadwal'  => $arr,
          'total' => $tot
        ]);

        $no++;
      }

      $kepala =  DataInduk::where('id_unker', $opd->id_unker)->kepala()->first();

      return view('laporan.cetak_laporan_bulanan')->withOpd($opd)->withBulan($request->bulan)->withTahun($request->tahun)->withData($data)->withKepala($kepala);
    }

    public function cetak()
    {
      return view('laporan.cetak_laporan_bulanan');
    }
}
