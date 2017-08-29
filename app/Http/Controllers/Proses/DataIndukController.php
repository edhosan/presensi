<?php

namespace App\Http\Controllers\Proses;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Model\DataInduk;
use Illuminate\Support\Collection;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use App\Model\TerminalUser;
use App\Model\Terminal;
use Auth;

class DataIndukController extends Controller
{
    public function index()
    {
      return view('proses.datainduk_list');
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
        $this->validate($request, $this->ruleNonPns());
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

    public function apiGetDataInduk()
    {
      $unker = Auth::user()->unker;

      $datainduk = DataInduk::orderBy('type','asc')
                  ->orderBy('id_eselon','asc')
                  ->orderBy('id_pangkat','desc')
                  ->orderBy('tmt_pangkat','desc');

      return Datatables::of($datainduk)
        ->filter(function($query) use($unker) {
          if(!empty($unker)){
            $query->where('id_unker', $unker);
          }
        })
        ->editColumn('pangkat','{{ isset($pangkat)?$pangkat." (".$golru.")":"" }}')
        ->editColumn('nama_jabatan','{{ $nama_jabatan." ".$nama_subunit }}')
        ->editColumn('terminal',function($data){
            $terminal = $this->getTerminal($data->id_finger);
            $str = '';
            foreach ($terminal as $value) {
              $str .= $value->Name." ";
            }
          return $str;
        })
        ->make(true);
    }

    private function getTerminal($id_finger)
    {
      $terminal = Terminal::whereHas('users', function($query) use($id_finger) {
        $query->where('UserId',$id_finger);
      })->get();

      return $terminal;
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

    public function showEdit($id)
    {
      $datainduk = DataInduk::findOrFail($id);
      $type = array('pns' => 'PNS', 'nonpns' => 'Non PNS');

      return view('proses.datainduk_form')
            ->withType($type)
            ->withData($datainduk);
    }

    public function update(Request $request)
    {
      $this->validate($request, ['type' => 'required']);

      if($request->type === 'pns') {
        $this->validate($request, array_except($this->rulePNS(),['id_finger']) );
      }else{
        $this->validate($request, array_except($this->ruleNonPns(),['id_finger']) );
      }

      $datainduk = DataInduk::findOrFail($request->id);

      $datainduk->update([
          'id_finger' => $request->id_finger,
          'type'      => $request->type,
          'nip'       => $request->nip,
          'nama'      => $request->nama,
          'gelar_depan' => $request->gelar_depan,
          'gelar_belakang'  => $request->gelar_belakang,
          'id_unker'    => $request->id_unker,
          'nama_unker'  => isset($request->nama_unker)?$request->nama_unker:"",
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

      return redirect('datainduk_list')->with('success','Data berhasil disimpan!');
    }

    public function apiDeleteDataInduk(Request $request)
    {
      $data = $request->input('data');

      foreach ($data as $id) {
        $status = DataInduk::where('id',$id)->delete();
      }

      return response()->json($status);
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

    public function apiSearchPegawai(Request $request)
    {
      $unker = Auth::user()->unker;

      if($request->exists('opd')){
        $unker = $request->opd;
      }

      $term = trim($request->q);

      if(empty($term)) {
        return response()->json([]);
      }

      $dataInduk = DataInduk::orderBy('nama', 'asc')
                  ->select('id','nama','nip')
                  ->where(function($query) use($unker) {
                    if(!empty($unker)) {
                      $query->where('id_unker', $unker);
                    }
                  })
                  ->where(function($query) use($request) {
                    if($request->exists('q')) {
                      $value = "{$request->q}%";
                      $query->where('nama', 'like', $value)
                        ->orWhere('nip', 'like', $value);
                    }
                  })
                  ->paginate($request->per_page);

      return response()->json($dataInduk);
    }
}
