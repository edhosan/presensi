<?php

namespace App\Http\Controllers\Proses;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\DataInduk;
use Auth;
use Validator;
use App\Library\MasterPresensi;
use Carbon\Carbon;
use App\Model\Ketidakhadiran;
use App\Model\PegawaiJadwal;
use App\Model\Jadwal;

class SinkronisasiController extends Controller
{
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

      	return view('proses.sinkronisasi')
            ->withOpd($opd);
    }

    public function proses(Request $request)
    {
    	$type = $request->type_sinkronisasi;
    	switch ($type) {
    		case '1':
    			$result = $this->sinkronisasiEvent($request);
    			return response()->json($result);
    		break;
    		case '2':
    			$result = $this->sinkronisasiJumlahHariIzin($request);
    			return response()->json($result);
    		break;
    		case '3':
    			$result = $this->sinkronisasiIzin($request);
    			return response()->json($result);
    		break;
    		case '4':
    			$result = $this->sinkronisasiDispensasi($request);
    			return response()->json($result);
    		break;
   			case '5':
    			$result = $this->sinkronisasiKehadiran($request);
    			return response()->json($result);
    		break;
    		default:
    			$this->sinkronisasiIzin($request);
    			$this->sinkronisasiKehadiran($request);
    			$result = $this->sinkronisasiDispensasi($request);
    			return response()->json($result);
    		break;
    	}

    }

    private function sinkronisasiEvent($request)
    {
    	$status = false;
    	$messages = [
    		'start.required' => "Tanggal mulai harus diisi!",
    		'end.required' => "Tanggal akhir harus diisi!",
    		'start.before' => "Tanggal mulai harus kurang dari tanggal akhir!"
    	];

   	 	$validator = Validator::make($request->all(), [
 			'start' => 'required|before:end',
      		'end' => 'required'
   	 	], $messages);

   	 	if(!$validator->fails()) {
	       	$status = true;
	       	$m_presensi = new MasterPresensi();
	       	$m_presensi->sinkronisasiEventByDate(Carbon::parse($request->start), Carbon::parse($request->end));
      	}

    	return [
    		'status' => $status,
    		'validator'	 => $validator->messages()
    	];
    }

    private function sinkronisasiJumlahHariIzin($request)
    {
    	/*$user = Auth::user();
    	$unker = $user->unker;

    	if($user->hasRole('admin')){
    		$unker = $request->opd;
    	}*/
	 	set_time_limit(0);
    	$ketidakharian = Ketidakhadiran::where(function($query) use($request){
    		if($request->has('start') && $request->has('end')){
    			$query->where('start','>=',Carbon::parse($request->start))->where('start','<=',Carbon::parse($request->end));
    		}
    		if($request->has('peg')){
    			$query->whereIn('peg_id', $request->peg);
    		}
    	})->get();    

    	foreach ($ketidakharian as $item) {
    		$date = dateRange($item->start, $item->end);
    		$count_hari = 0;

	   		foreach ($date as $value) {
		       $tanggal = Carbon::parse($value);
		       $peg_jadwal = PegawaiJadwal::where('peg_id', $item->peg_id)->where('tanggal','=',$value)->first();
		       if(!empty($peg_jadwal)){
			       $jadwal = Jadwal::where('id', $peg_jadwal->jadwal_id)->first();
			       $hari_id = $tanggal->format('N');
			       $hari = $jadwal->hari()->where('hari', $hari_id)->first();
			       if(!empty($hari) && $peg_jadwal->event_id == null){
			         $count_hari = $count_hari + 1;
			       }		       	
		       }

		    }

		    $item->update(['jml_hari' => $count_hari]);
		    $hasil[] = [
		    	'izin' => $item,
		    	'jml_hari' => $count_hari
		    ];
    	}

    	return [
    		'status' => true,
    		'validator'	 => '',
    		'hasil' => $hasil
    	];
    }

    private function sinkronisasiIzin($request)
    {    	
    	$user = Auth::user();
    	if($user->hasRole('admin')){
    		$unker = $request->opd;
    	}else{
    		$unker = $user->unker;
    	}

    	$status = false;
    	$messages = [
    		'start.required' => "Tanggal mulai harus diisi!",
    		'end.required' => "Tanggal akhir harus diisi!",
    		'start.before' => "Tanggal mulai harus kurang dari tanggal akhir!"
    	];

    	$validator = Validator::make($request->all(), [
 			'start' => 'required|before:end',
      		'end' => 'required'
   	 	], $messages);

      	$validator->after(function($validator) use($request, $user) {
	        $start =  Carbon::parse($request->start);
	        $end   =  Carbon::parse($request->end);
	        $interval = $end->diffInDays($start);

	        if($interval > 31){
	          $validator->errors()->add('date_range', 'Maksimum range tanggal tidak lebih dari 31 hari!');
	        }

	        if($user->hasRole('admin')){
	        	if(!$request->has('opd')){
	        		$validator->errors()->add('OPD', 'OPD harus diisi!');
	        	}
	        }

	    });

      	$hasil = array();
	    if(!$validator->fails()) {
	       	$status = true;
	       	$m_presensi = new MasterPresensi();
	    	$hasil = $m_presensi->sinkronisasiIzin($unker, $request->start, $request->end, $request->peg);
      	}

    	return [
    		'status' => $status,
    		'validator'	 => $validator->messages(),
    		'hasil' => $hasil
    	];
    }

    private function sinkronisasiDispensasi($request)
    {   $user = Auth::user();
    	if($user->hasRole('admin')){
    		$unker = $request->opd;
    	}else{
    		$unker = $user->unker;
    	}

    	$status = false;
    	$messages = [
    		'start.required' => "Tanggal mulai harus diisi!",
    		'end.required' => "Tanggal akhir harus diisi!",
    		'start.before' => "Tanggal mulai harus kurang dari tanggal akhir!"
    	];

    	$validator = Validator::make($request->all(), [
 			'start' => 'required|before:end',
      		'end' => 'required'
   	 	], $messages);

      	$validator->after(function($validator) use($request, $user) {
	        $start =  Carbon::parse($request->start);
	        $end   =  Carbon::parse($request->end);
	        $interval = $end->diffInDays($start);

	        if($interval > 31){
	          $validator->errors()->add('date_range', 'Maksimum range tanggal tidak lebih dari 31 hari!');
	        }

	        if($user->hasRole('admin')){
	        	if(!$request->has('opd')){
	        		$validator->errors()->add('OPD', 'OPD harus diisi!');
	        	}
	        }

	    });

	    $hasil = array();
	    if(!$validator->fails()) {
	       	$status = true;
	       	$m_presensi = new MasterPresensi();
	    	$hasil = $m_presensi->sinkronisasiDispensasi($unker, $request->start, $request->end, $request->peg);
      	}

    	return [
    		'status' => $status,
    		'validator'	 => $validator->messages(),
    		'hasil' => $hasil
    	];
    }

    private function sinkronisasiKehadiran($request)
    {
    	set_time_limit(0);
    	$user = Auth::user();
    	if($user->hasRole('admin')){
    		$unker = $request->opd;
    	}else{
    		$unker = $user->unker;
    	}

    	$status = false;
    	$messages = [
    		'start.required' => "Tanggal mulai harus diisi!",
    		'end.required' => "Tanggal akhir harus diisi!",
    		'start.before' => "Tanggal mulai harus kurang dari tanggal akhir!"
    	];

    	$validator = Validator::make($request->all(), [
 			'start' => 'required|before:end',
      		'end' => 'required'
   	 	], $messages);

      	$validator->after(function($validator) use($request, $user) {
	        $start =  Carbon::parse($request->start);
	        $end   =  Carbon::parse($request->end);
	        $interval = $end->diffInDays($start);

	        if($interval > 31){
	          $validator->errors()->add('date_range', 'Maksimum range tanggal tidak lebih dari 31 hari!');
	        }

	        if($user->hasRole('admin')){
	        	if(!$request->has('opd')){
	        		$validator->errors()->add('OPD', 'OPD harus diisi!');
	        	}
	        }
	    });

	     $hasil = array();
	    if(!$validator->fails()) {
	       	$status = true;
	       	$m_presensi = new MasterPresensi();
	    	$hasil = $m_presensi->sinkronisasiKehadiran($unker, $request->start, $request->end, $request->peg);
      	}


    	return [
    		'status' => $status,
    		'validator'	 => $validator->messages(),
    		'hasil' => $hasil
    	];
    }
}
