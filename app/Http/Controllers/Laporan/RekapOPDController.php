<?php

namespace App\Http\Controllers\Laporan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Model\DataInduk;

class RekapOPDController extends Controller
{
    private $rules = [
      'opd' => 'required',
      'start' => 'required|before:end',
      'end' => 'required'
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

      return view('laporan.rpt_opd')->withOpd($opd)->withStart($request->start)->withEnd($request->end);
    }
}
