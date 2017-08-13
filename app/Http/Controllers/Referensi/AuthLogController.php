<?php

namespace App\Http\Controllers\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use App\Model\AuthLog;
use App\Model\DataInduk;

class AuthLogController extends Controller
{
    public function index()
    {
      return view('referensi.authlog_list');
    }

    public function apiAuthLogList()
    {
      $data = AuthLog::with('terminal')
              ->where('AuthResult',0);

      return Datatables::of($data)
      ->make(true);
    }

    public function apiDeleteAuthLog(Request $request)
    {
      $data = $request->input('data');

      foreach ($data as $id) {
        $status = AuthLog::where('IndexKey',$id)->delete();
      }

      return response()->json($status);
    }
}
