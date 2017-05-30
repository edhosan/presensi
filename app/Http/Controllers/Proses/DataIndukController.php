<?php

namespace App\Http\Controllers\Proses;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Model\DataInduk;

class DataIndukController extends Controller
{
    public function index()
    {

    }

    public function showForm()
    {
      $type = array('pns' => 'PNS', 'nonpns' => 'Non PNS');


      return view('proses.datainduk_form')
            ->withType($type);
    }

    public function create(Request $request)
    {
      $this->validate($request, ['type' => 'required']);

      if($request->type === 'pns') {
        $this->validate($request, $this->rulePNS());
      }else{
        $this->validate($request, $this->ruleNonPnsPNS());
      }

      try{
        DataInduk::create([
            'id_finger' => $request->id_finger,
            'type'      => $request->type,
            'nip'       => $request->nip,
            'nama'      => $request->nama,
            'gelar_depan' => $request->gelar_depan,
            'gelar_belakang'  => $request->gelar_belakang,
            'id_unker'    => $request->id_unker,
            'nama_unker'  => $request->nama_unker,
            'id_subunit'  => $request->subunit,
            'nama_subunit'  => $request->nama_subunit,
            'id_pangkat'  => $request->id_pangkat,
            'golru'       => $request->golru,
            'pangkat'     => $request->nama_pangkat,
            'id_jabatan'  => $request->jabatan,
            'nama_jabatan'  => $request->nama_jabatan,
            'id_eselon'   => $request->id_eselon,
            'tmt_pangkat'   => date('Y-m-d', strtotime($request->tmt_pangkat)),
        ]);
      }catch(Exception $exception) {
        $errorInfo = $exception->errorInfo;
      }


      return redirect('datainduk_list')->with('success','Data berhasil disimpan!');
    }

    private function rulePNS()
    {
      return array(
        'id_finger' => 'required|unique:peg_data_induk|max:8|min:8',
        'nip' => 'required',
        'nama' => 'required',
        'id_unker' => 'required|is_exists_opd',
        'subunit' => 'required',
        'id_pangkat' => 'required',
        'jabatan' => 'required',
        'tmt_pangkat' => 'required'
      );
    }

    private function ruleNonPns()
    {
      return array(
        'id_finger' => 'required|unique:peg_data_induk|max:8|min:8',
        'nama' => 'required',
        'id_unker' => 'required|is_exists_opd',
        'subunit' => 'required',
        'jabatan' => 'required',
      );
    }

    public function apiGetPangkat(Request $request)
    {
      $pangkat = DB::connection('mysql2')->table('ref_pangkat')
                  ->select(DB::raw("CONCAT(pangkat,' - ',golru)as pangkat, id_pangkat"),'ref_pangkat.*' );

      if($request->exists('phrase')){
        $pangkat->where(function($q) use($request){
          $value = "{$request->phrase}%";
          $q->where('golru', 'like', $value)
            ->orWhere('pangkat', 'like', $value);
        });
      }

      return response()->json($pangkat->get());
    }

    public function apiGetSubUnit(Request $request)
    {
      $query = DB::connection('mysql2')->table('ref_subunit')
              ->where('status', 1)
              ->select('id_subunit','id_unker','nama_subunit')
              ->orderBy('nama_subunit','asc');

      if($request->exists('unker') && !empty($request->unker)){
        $query->where(function($q) use($request){
          $value = "{$request->unker}";
          $q->where('id_unker', '=', $value);
        });
      }

      if($request->exists('phrase')){
        $query->where(function($q) use($request){
          $value = "{$request->phrase}%";
          $q->where('id_subunit', 'like', $value)
            ->orWhere('nama_subunit', 'like', $value);
        });
      }

      return response()->json($query->get());

    }

    public function update(Request $request)
    {

    }

    public function apiGetId()
    {
      return json_encode(array('id_finger' => mt_rand(10000000,99999999) ));
    }

    public function apiGetJabatan(Request $request)
    {
      $jabatan = DB::connection('mysql2')->table('ref_jabatan')
              ->join('ref_eselon','ref_eselon.id_eselon','=','ref_jabatan.id_eselon')
              ->select('ref_jabatan.id_jabatan','ref_jabatan.id_eselon','ref_jabatan.nama_jabatan','ref_eselon.nama')
              ->orderBy('ref_jabatan.nama_jabatan','asc');

      if($request->exists('phrase')){
        $jabatan->where(function($q) use($request){
          $value = "{$request->phrase}%";
          $q->where('id_jabatan', 'like', $value)
            ->orWhere('nama_jabatan', 'like', $value);
        });
      }

      return response()->json($jabatan->get());
    }
}
