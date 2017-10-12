<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Model\DataInduk;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use DB;
use Illuminate\Http\Request;
use App\Model\OPD;
use Yajra\Datatables\Datatables;
use App\Model\Role;
use Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/user';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,id,'.$data['id'],
            'password' => 'required|string|min:6|confirmed',
            'tipe'  => 'required|array|min:1'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
      if(array_key_exists('opd', $data)){
        $opd = OPD::findOrFail($data['opd']);
      }

      $user = User::create([
          'name' => $data['name'],
          'username' => $data['username'],
          'password' => bcrypt($data['password']),
          'api_token' => str_random(60),
          'unker' =>array_key_exists('opd', $data)?$data['opd']:'',
          'nm_unker' =>array_key_exists('opd', $data)?$opd->nama_unker:''
      ]);

      foreach ($data['tipe'] as $key => $value) {
        $user->roles()->attach($value);
      }

      return $user;
    }

    public function showRegister()
    {
        $unker = Auth::user()->unker;

        $tipe = Role::pluck('display_name','id');

        $opd = DataInduk::orderBy('nama_unker','asc')
               ->groupBy('id_unker','nama_unker')
               ->where(function($query) use($unker) {
                 if(!empty($unker)) {
                   $query->where('id_unker',$unker);
                 }
               })
               ->pluck('nama_unker','id_unker');

        return view('auth.register')->with('tipe', $tipe)->withOpd($opd);;
    }

    public function changePassword()
    {
      $auth = Auth::user();

      return view('auth.passwords.change')->withData($auth);
    }

    public function updatePassword(Request $request)
    {
      $this->validate($request, array(
        'password' => 'required|string|min:6|confirmed',
        'password_confirmation' => 'required|min:3'
      ));

      $user = User::find($request->id);
      $user->update([
        'password' => bcrypt($request->password)
      ]);

      return redirect('home')->with('success','Data berhasil disimpan!');
    }

    public function apiGetPegawai(Request $request)
    {
      $unker = Auth::user()->unker;

      if($request->has('opd')){
        $unker = $request->opd;
      }

      $query = DB::connection('mysql2')->table('peg_datadasar')
                  ->join('peg_stat_duk','peg_datadasar.nip','=','peg_stat_duk.nip')
                  ->join(DB::raw('(select max(id)id from peg_stat_duk group by nip) as x'), function($peg_stat_duk) {
                      $peg_stat_duk->on('x.id','=','peg_stat_duk.id');
                    })
                  ->join('ref_subunit','peg_stat_duk.id_subunit','=','ref_subunit.id_subunit')
                  ->join('ref_unker','ref_subunit.id_unker','=','ref_unker.id_unker')
                  ->join('peg_pangkat','peg_pangkat.nip','=','peg_datadasar.nip')
                  ->join(DB::raw('(select max(id_peg_pangkat)id_peg_pangkat,max(id_pangkat)id_pangkat from peg_pangkat group by nip)y'), function($peg_pangkat) {
                      $peg_pangkat->on('y.id_peg_pangkat','=','peg_pangkat.id_peg_pangkat');
                    })
                  ->join('ref_pangkat','ref_pangkat.id_pangkat','=','peg_pangkat.id_pangkat')
                  ->join('peg_jabatan','peg_jabatan.nip','=','peg_datadasar.nip')
                  ->join(DB::raw('(select max(id_peg_jabatan)id_peg_jabatan from peg_jabatan group by nip)z'), function($peg_jabatan) {
                      $peg_jabatan->on('z.id_peg_jabatan','=','peg_jabatan.id_peg_jabatan');
                    })
                  ->join('ref_jabatan','ref_jabatan.id_jabatan','=','peg_jabatan.id_jabatan')
                  ->join('ref_eselon','ref_jabatan.id_eselon','=','ref_eselon.id_eselon')
                  ->whereNotIn('peg_stat_duk.id_status_pegawai',[3, 4, 5, 5, 6, 7, 8])
                  ->select(DB::raw('peg_datadasar.nip as id'), 'peg_datadasar.nip','peg_datadasar.nama','ref_unker.id_unker','ref_unker.nama_unker','peg_datadasar.gelar_depan',
                           'peg_datadasar.gelar_belakang','ref_subunit.id_subunit','ref_subunit.nama_subunit','y.id_pangkat','peg_jabatan.id_jabatan',
                           'ref_jabatan.nama_jabatan',DB::raw('CONCAT(ref_jabatan.nama_jabatan, " - ", ref_eselon.nama) nm_jabatan'),
                           DB::raw('DATE_FORMAT(peg_pangkat.tmt_pangkat, "%d-%m-%Y") as tmt_pangkat'),'ref_pangkat.*', 'ref_eselon.id_eselon'
                         );

      $query = $query->orderBy('ref_eselon.id_eselon', 'asc')
                     ->orderBy('ref_pangkat.id_pangkat', 'desc')
                     ->orderBy('peg_pangkat.tmt_pangkat', 'desc');

      if(!empty($unker)){
        $query->where(function($q) use($unker){
          $value = "{$unker}";
          $q->where('ref_unker.id_unker', '=', $value);
        });
      }

      $term = trim($request->q);

      if(!empty($term)){
        $query->where(function($q) use($term){
          $value = "{$term}%";
          $q->where('peg_datadasar.nip', 'like', $value)
            ->orWhere('peg_datadasar.nama', 'like', $value);
        });
      }

      return response()->json($query->paginate($request->per_page));
    }

    public function apiGetUnker(Request $request)
    {
      $opd = OPD::where('status', 1)
                ->select('id_unker','nama_unker')
                ->orderBy('nama_unker','asc');

      if($request->exists('unker') && !empty($request->unker)){
        $opd->where(function($q) use($request){
          $value = "{$request->unker}";
          $q->where('id_unker', '=', $value);
        });
      }

      if($request->exists('phrase')){
        $opd->where(function($q) use($request){
          $value = "{$request->phrase}%";
          $q->where('nama_unker', 'like', $value);
        });
      }

      return response()->json($opd->get());
    }

    public function getListUser()
    {
      return view('auth.list_user');
    }

    public function showEdit($id)
    {
      $unker = Auth::user()->unker;

      $tipe = Role::pluck('display_name','id');
      $user = User::find($id);
      $opd = DataInduk::orderBy('nama_unker','asc')
             ->groupBy('id_unker','nama_unker')
             ->where(function($query) use($unker) {
               if(!empty($unker)) {
                 $query->where('id_unker',$unker);
               }
             })
             ->pluck('nama_unker','id_unker');

      return view('auth.register')->withData($user)->withTipe($tipe)->withOpd($opd);
    }

    public function apiUser()
    {
      return Datatables::of(User::with('roles'))->make(true);
    }

    public function updateUser(Request $request)
    {
       $data = $request->all();

       if(array_key_exists('opd', $data)){
         $opd = OPD::findOrFail($data['opd']);
       }

       $arr = array(
         'name' => $data['name'],
         'username' => $data['username'],
         'password' => bcrypt($data['password']),
         'unker' =>array_key_exists('opd', $data)?$data['opd']:'',
         'nm_unker' =>array_key_exists('opd', $data)?$opd->nama_unker:''
       );

       $user = User::find($data['id']);
       $user->update($arr);

       $user->roles()->detach();

      foreach ($data['tipe'] as $key => $value) {
        $user->roles()->attach($value);
       }

        return redirect('user')->with('success','Data berhasil diupdate!');
    }

    public function apiDeleteUser(Request $request)
    {
      $data = $request->input('data');

      foreach ($data as $id) {
        $status = User::where('id',$id)->delete();
      }

      return response()->json($status);
    }
}
