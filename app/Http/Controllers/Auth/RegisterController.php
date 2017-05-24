<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use DB;
use Illuminate\Http\Request;
use App\Model\OPD;
use Yajra\Datatables\Datatables;

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
            'id_unker' => 'is_exists_opd'
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
      return User::create([
          'name' => $data['name'],
          'username' => $data['username'],
          'password' => bcrypt($data['password']),
          'api_token' => str_random(60),
          'unker' => $data['id_unker'],
          'nm_unker' => $data['opd'],
          'tipe' => 'admin'
      ]);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function apiGetPegawai(Request $request)
    {
      $query = DB::connection('mysql2')->table('peg_datadasar')
                  ->join('peg_stat_duk','peg_datadasar.nip','=','peg_stat_duk.nip')
                  ->join(DB::raw('(select max(id)id from peg_stat_duk group by nip) as x'), function($peg_stat_duk) {
                      $peg_stat_duk->on('x.id','=','peg_stat_duk.id');
                    })
                  ->join('ref_subunit','peg_stat_duk.id_subunit','=','ref_subunit.id_subunit')
                  ->join('ref_unker','ref_subunit.id_unker','=','ref_unker.id_unker')
                  ->whereNotIn('peg_stat_duk.id_status_pegawai',[3, 4, 5, 5, 6, 7, 8])
                  ->select('peg_datadasar.nip','peg_datadasar.nama','ref_unker.id_unker','ref_unker.nama_unker');

      $query = $query->orderBy('peg_datadasar.nip', 'asc');

      if($request->exists('unker') && !empty($request->unker)){
        $query->where(function($q) use($request){
          $value = "{$request->unker}";
          $q->where('ref_unker.id_unker', '=', $value);
        });
      }

      if($request->exists('phrase')){
        $query->where(function($q) use($request){
          $value = "{$request->phrase}%";
          $q->where('peg_datadasar.nip', 'like', $value)
            ->orWhere('peg_datadasar.nama', 'like', $value);
        });
      }

      return response()->json($query->get());
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
      $user = User::find($id);

      return view('auth.register')->withData($user);
    }

    public function apiUser()
    {
      return Datatables::of(User::query())->make(true);
    }

    public function updateUser(Request $request)
    {
       $data = $request->all();

       $status = User::where('id',$data['id'])->update([
           'name' => $data['name'],
           'username' => $data['username'],
           'password' => bcrypt($data['password']),
           'unker' => $data['id_unker'],
           'nm_unker' => $data['opd'],
           'tipe' => 'admin'
         ]);

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
