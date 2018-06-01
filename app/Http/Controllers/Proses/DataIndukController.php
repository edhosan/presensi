<?php

namespace App\Http\Controllers\Proses;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Model\DataInduk;
use App\Model\OPD;
use App\Model\SubUnit;
use Illuminate\Support\Collection;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use App\Model\TerminalUser;
use App\Model\Terminal;
use App\Model\Eselon;
use App\Model\Jabatan;
use Auth;

class DataIndukController extends Controller
{
    public function index()
    {
      return view('proses.datainduk_list');
    }

    public function showForm()
    {
      $unker = Auth::user()->unker;

      $type = array('pns' => 'PNS', 'nonpns' => 'Non PNS');

      $opd = OPD::orderBy('nama_unker','asc')
             ->where('status', 1)
             ->where(function($query) use($unker) {
               if(!empty($unker)) {
                 $query->where('id_unker',$unker);
               }
             })
             ->pluck('nama_unker','id_unker');

      $subunit = OPD::with('subUnit')->where('status', 1)->get();

      $pangkat = DB::connection('mysql2')->table('ref_pangkat')
                  ->select(DB::raw("CONCAT(pangkat,' - ',golru)as pangkat, id_pangkat") )
                  ->pluck('pangkat', 'id_pangkat');

      $eselon = Eselon::orderBy('id_eselon','asc')->pluck('nama', 'id_eselon');

      $jabatan = Eselon::with('jabatan')->orderBy('id_eselon','asc')->get();

      return view('proses.datainduk_form')
            ->withType($type)
            ->withOpd($opd)
            ->withSubunit($subunit)
            ->withPangkat($pangkat)
            ->withEselon($eselon)
            ->withJabatan([]);
    }

    public function create(Request $request)
    {
      if($request->type === 'pns') {
        $this->validate($request, $this->rulePNS());
      }else{
        $this->validate($request, $this->ruleNonPns());
      }

      $opd = OPD::find($request->opd);
      $subunit = SubUnit::find($request->sub_unit);
      $pangkat = DB::connection('mysql2')->table('ref_pangkat')
                ->where('id_pangkat', $request->pangkat)
                ->first();
      $jabatan = Jabatan::with('eselon')->find($request->jabatan);

      try{
        DataInduk::create([
            'id_finger' => $request->id_finger,
            'type'      => $request->type,
            'nip'       => $request->nip,
            'nama'      => $request->nama,
            'gelar_depan' => $request->gelar_depan,
            'gelar_belakang'  => $request->gelar_belakang,
            'id_unker'    => str_pad($opd->id_unker, 8,"0", STR_PAD_LEFT) ,
            'nama_unker'  => $opd->nama_unker,
            'id_subunit'  => isset($subunit->id_subunit)?$subunit->id_subunit:null,
            'nama_subunit'  => isset($subunit->nama_subunit)?$subunit->nama_subunit:null,
            'id_pangkat'  => isset($pangkat)?$pangkat->id_pangkat:'',
            'golru'       => isset($pangkat)?$pangkat->golru:'',
            'pangkat'     => isset($pangkat)?$pangkat->pangkat:'',
            'id_jabatan'  => $jabatan->id_jabatan,
            'nama_jabatan'  => $jabatan->nama_jabatan,
            'id_eselon'   => $jabatan->eselon->id_eselon,
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
        'type' => 'required|in:pns,nonpns',
        'id_finger' => 'required|unique:peg_data_induk|max:8|min:8',
        'nip' => 'required',
        'nama' => 'required',
        'opd' => 'required',
        'pangkat' => 'required',
        'eselon' => 'required',
        'jabatan' => 'required',
        'tmt_pangkat' => 'required'
      );
    }

    private function ruleNonPns()
    {
      return array(
        'type' => 'required',
        'id_finger' => 'required|unique:peg_data_induk|max:8|min:8',
        'nama' => 'required',
        'opd' => 'required',
        'jabatan' => 'required',
      );
    }

    public function apiGetDataInduk(Request $request)
    {
      $unker = Auth::user()->unker;

      $datainduk = DataInduk::orderBy('type','asc')
                  ->where(function($query) use($request) {
                    if($request->has('search')) {
                      $query->where('nama', 'like', $request->search['value'].'%');
                      $query->OrWhere('nip', 'like', $request->search['value'].'%');
                      $query->OrWhere('id_finger', 'like', $request->search['value'].'%');
                      $query->OrWhere('nama_unker', 'like', $request->search['value'].'%');
                    }
                  })
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
      $subunit = OPD::with('subUnit')
                ->where(function($query) use($request) {
                  if($request->has('opd')){
                    $query->where('id_unker', $request->opd);
                  }
                })
                ->where('status', 1)
                ->get();

      return response()->json($subunit);

    }

    public function showEdit($id)
    {
      $unker = Auth::user()->unker;

      $opd = OPD::orderBy('nama_unker','asc')
             ->where('status', 1)
             ->where(function($query) use($unker) {
               if(!empty($unker)) {
                 $query->where('id_unker',$unker);
               }
             })
             ->pluck('nama_unker','id_unker');

      $datainduk = DataInduk::findOrFail($id);

      $type = array('pns' => 'PNS', 'nonpns' => 'Non PNS');

      $subunit = OPD::with('subUnit')->where('status', 1)->get();

      $pangkat = DB::connection('mysql2')->table('ref_pangkat')
                  ->select(DB::raw("CONCAT(pangkat,' - ',golru)as pangkat, id_pangkat") )
                  ->pluck('pangkat', 'id_pangkat');

      $eselon = Eselon::orderBy('id_eselon','asc')->pluck('nama', 'id_eselon');

      $jabatan = Jabatan::where('id_jabatan', $datainduk->id_jabatan)->pluck('nama_jabatan','id_jabatan');

      return view('proses.datainduk_form')
            ->withType($type)
            ->withOpd($opd)
            ->withSubunit($subunit)
            ->withPangkat($pangkat)
            ->withEselon($eselon)
            ->withJabatan($jabatan)
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

      $opd = OPD::find($request->opd);
      $subunit = SubUnit::find($request->sub_unit);
      $pangkat = DB::connection('mysql2')->table('ref_pangkat')
                ->where('id_pangkat', $request->pangkat)
                ->first();
      $jabatan = Jabatan::with('eselon')->find($request->jabatan);

      $datainduk->update([
        'id_finger' => $request->id_finger,
        'type'      => $request->type,
        'nip'       => $request->nip,
        'nama'      => $request->nama,
        'gelar_depan' => $request->gelar_depan,
        'gelar_belakang'  => $request->gelar_belakang,
        'id_unker'    => str_pad($opd->id_unker, 8,"0", STR_PAD_LEFT) ,
        'nama_unker'  => $opd->nama_unker,
        'id_subunit'  => isset($subunit->id_subunit)?$subunit->id_subunit:null,
        'nama_subunit'  => isset($subunit->nama_subunit)?$subunit->nama_subunit:null,
        'id_pangkat'  => isset($pangkat)?$pangkat->id_pangkat:null,
        'golru'       => isset($pangkat)?$pangkat->golru:null,
        'pangkat'     => isset($pangkat)?$pangkat->pangkat:null,
        'id_jabatan'  => $jabatan->id_jabatan,
        'nama_jabatan'  => $jabatan->nama_jabatan,
        'id_eselon'   => $jabatan->eselon->id_eselon,
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
      $term = trim($request->q);

      $jabatan = Jabatan::join('ref_eselon','ref_eselon.id_eselon','=','ref_jabatan.id_eselon')
                ->where(function($query) use($request) {
                  if($request->has('eselon')){
                    $query->where('ref_jabatan.id_eselon', $request->eselon);
                  }
                })
                ->select(DB::raw('ref_jabatan.id_jabatan as id'), 'ref_jabatan.nama_jabatan','ref_eselon.nama');

      if(!empty($term)){
        $jabatan->where(function($q) use($term){
          $value = "{$term}%";
          $q->where('ref_jabatan.nama_jabatan', 'like', $value);
        });
      }

      return response()->json($jabatan->paginate($request->per_page));
    }

    public function apiSearchPegawai(Request $request)
    {
      $unker = Auth::user()->unker;

      if($request->has('opd')){
        $unker = $request->opd;
      }

      $term = trim($request->q);

      if(empty($term)) {
        return response()->json([]);
      }

      $dataInduk = DataInduk::orderBy('nama', 'asc')
                  ->select('id','nama','nip','id_unker')
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
