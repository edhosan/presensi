<?php

namespace App\Http\Controllers\Proses;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Model\AuthLog;
use App\Model\DataInduk;
use Validator;
use Carbon\Carbon;
use DB;

class RiwayatAbsensiController extends Controller
{
    public function index()
    {
   		$unker = Auth::user()->unker;

    	return view('proses.riwayat');
    }

    public function getLogFinger(Request $request)
    {
    	$status = false;
    	$result = null;

    	$messages = [
    		'tanggal.required' => "Tanggal harus diisi!",
    		'peg.required' => "Nama/Nip Pegawai harus diisi!"
    	];

   	 	$validator = Validator::make($request->all(), [
 			'tanggal' => 'required',
      		'peg' => 'required'
   	 	], $messages);

   	 	if(!$validator->fails()) {
	       	$status = true;
	       	$tanggal = Carbon::parse($request->tanggal);

	       	$data_induk = DataInduk::find($request->peg);

	       	if($data_induk){
	       		$query = "SELECT Convert(varchar, TransactionTime, 105) as tanggal, Convert(varchar, TransactionTime, 108) as jam FROM NGAC_AUTHLOG WHERE UserId = :id_finger AND cast(TransactionTime as date) = :tanggal";
	       		$authlog = DB::connection('sqlsrv')->select($query, [
	       					'id_finger' => $data_induk->id_finger,
	       					'tanggal' => $tanggal
	       				]);
	       		$result = $authlog;
	       	}
      	}

    	return [
    		'status' => $status,
    		'validator'	 => $validator->messages(),
    		'data'	=> $result
    	];
    }


}
