<?php

namespace App\Http\Controllers\Proses;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class DataIndukController extends Controller
{
    public function index()
    {

    }

    public function showForm()
    {
      $type = array('pns' => 'PNS', 'nonpns' => 'Non PNS');


      return view('proses.datainduk_form')
            ->withType($type)
            ->withPangkat( $this->pangkat()->pluck('pangkat','id_pangkat') );
    }

    public function create(Request $request)
    {
      if($request->type === 'pns') {
        $this->validate($request, $this->rulePNS());
      }





    }

    private function rulePNS()
    {
      return array(
        'id_finger' => 'required|unique:peg_data_induk|max:8|min:8',
        'nip' => 'required',
        'nama' => 'required',
        'id_unker' => 'required|is_exists_opd',
        'subunit' => 'required'
      );
    }

    private function pangkat()
    {
      $pangkat = DB::connection('mysql2')->table('ref_pangkat')
                  ->select(DB::raw("CONCAT(pangkat,' - ',golru)as pangkat, id_pangkat") )
                  ->get();

      return $pangkat;
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
              ->select('id_jabatan','id_eselon','nama_jabatan')
              ->orderBy('nama_jabatan','asc');

      if($request->exists('unker') && !empty($request->unker)){
        $query->where(function($q) use($request){
          $value = "{$request->unker}";
          $q->where('id_unker', '=', $value);
        });
      }

      if($request->exists('phrase')){
        $query->where(function($q) use($request){
          $value = "{$request->phrase}%";
          $q->where('id_jabatan', 'like', $value)
            ->orWhere('nama_jabatan', 'like', $value);
        });
      }

      return response()->json($query->get());

    }
}
