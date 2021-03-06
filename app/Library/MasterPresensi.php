<?php 
namespace App\Library;

use App\Model\PegawaiJadwal;
use App\Model\Event;
use Carbon\Carbon;
use App\Model\Ketidakhadiran;
use App\Model\Dispensasi;
use App\Model\Jadwal;
use DB;
use App\Model\AuthLog;

class MasterPresensi{
	public function sinkronisasiEvent($start, $end, $event_id)
	{
		$date = dateRange($start, $end);
	 	foreach ($date as $value) {	 		
	 		PegawaiJadwal::where('tanggal', $value)->update([
	 			'event_id'  => $event_id,
	 			'status'	=> 'L'
	 		]);
	 	}
	}

	public function sinkronisasiEventByDate($start, $end)
	{
		$event = Event::where('start_date','>=',$start)->where('end_date','<=',$end)->get();
		foreach ($event as $item) {
			$this->sinkronisasiEvent(Carbon::parse($item->start_date), Carbon::parse($item->end_date), $item->id);
		}
	}

	public function sinkronisasiEventByPegawai($start, $end, $peg)
	{
		$event = Event::where('start_date','>=',$start)->where('end_date','<=',$end)->get();
		foreach ($event as $item) {
			PegawaiJadwal::whereIn('peg_id', $peg)->where('tanggal','>=',Carbon::parse($item->start_date))->where('tanggal','<=',Carbon::parse($item->end_date))
			->update([
				'event_id'  => $item->id,
	 			'status'	=> 'L'
			]);
		}
	}

	public function sinkronisasiIzin($unker, $start, $end, $peg)
	{
		$ketidakhadiran = Ketidakhadiran::join('peg_data_induk','peg_data_induk.id','=','ketidakhadiran.peg_id')
						  ->join('ref_ijin','ref_ijin.id','=','ketidakhadiran.keterangan_id')
						  ->where(function($filter) use($unker, $start, $end, $peg){
						  	if(!empty($unker)){
						  		$filter->where('peg_data_induk.id_unker', $unker);
						  	}
						  	if(!empty($start) && !empty($end)){
						  		$filter->where('ketidakhadiran.start','>=',Carbon::parse($start))->where('ketidakhadiran.start','<=',Carbon::parse($end));	
						  	}
						  	if(!empty($peg)){
						  		$filter->whereIn('ketidakhadiran.peg_id', $peg);
						  	}
						  })->select('ketidakhadiran.peg_id','ketidakhadiran.start','ketidakhadiran.end','ketidakhadiran.id','ref_ijin.symbol')
						  ->get();		
	
		foreach ($ketidakhadiran as $item) {
			$date = dateRange($item->start, $item->end);			
			foreach ($date as $tanggal) {
				$tanggal = Carbon::parse($tanggal);
				$peg_jadwal = PegawaiJadwal::where('peg_jadwal.peg_id', $item->peg_id)
							  ->where('peg_jadwal.tanggal','=',$tanggal)
							  ->first();
				if(!empty($peg_jadwal)){
					if(!empty($peg_jadwal->event_id)){
						$peg_jadwal->update(['status' => 'L','ketidakhadiran_id' => 0]);			
					}else{
						$jadwal = Jadwal::find($peg_jadwal->jadwal_id);
						$hari_id = $tanggal->format('N');
						$hari = $jadwal->hari()->where('hari', $hari_id)->first();
						if(!empty($hari)){						
							$peg_jadwal->update(['ketidakhadiran_id' => $item->id,'status' => $item->symbol, 'in'=>'00:00:00','out'=>'00:00:00','jam_kerja' => '00:00:00','terlambat' => '00:00:00', 'pulang_awal' => '00:00:00','scan_1' => '00:00:00', 'scan_2' => '00:00:00']);										
						}else{
							$peg_jadwal->update(['status' => 'L']);					
						}															
					}

				}

			}						
		}

		return $ketidakhadiran;
	}

	public function sinkronisasiDispensasi($unker, $start, $end, $peg)
	{
		$dispensasi = Dispensasi::join('peg_data_induk','peg_data_induk.id','=','dispensasi.peg_id')
					  ->join('peg_jadwal', function($join){
					  	$join->on('peg_jadwal.peg_id','=','dispensasi.peg_id')
					  		->on('peg_jadwal.tanggal','=','dispensasi.tanggal');
					  })
				 	  ->where(function($filter) use($unker, $start, $end, $peg){
					  	if(!empty($unker)){
					  		$filter->where('peg_data_induk.id_unker', $unker);
					  	}
					  	if(!empty($start) && !empty($end)){
					  		$filter->where('dispensasi.tanggal','>=',Carbon::parse($start))->where('dispensasi.tanggal','<=',Carbon::parse($end));	
					  	}
					  	if(!empty($peg)){
					  		$filter->whereIn('dispensasi.peg_id', $peg);
					  	}
					  })
					  ->get(['peg_jadwal.jadwal_id','peg_jadwal.tanggal','peg_jadwal.id', DB::raw('dispensasi.id as dispensasi_id'), 'peg_data_induk.nama','dispensasi.koreksi_jam','peg_jadwal.in','peg_jadwal.out']);

		foreach ($dispensasi as $item) {
			$jadwal = Jadwal::find($item->jadwal_id);
 		  	$tanggal = Carbon::parse($item->tanggal);
 		  	$hari_id = $tanggal->format('N');
	  	  	$hari = $jadwal->hari()->where('hari', $hari_id)->first();
			if(isset($hari)){
				$koreksi_jam = Carbon::parse($item->koreksi_jam);
				$scan_in1 = Carbon::parse($hari->scan_in1);
				$scan_in2 = Carbon::parse($hari->scan_in2);
				$scan_out1 = Carbon::parse($hari->scan_out1);
				$scan_out2 = Carbon::parse($hari->scan_out2);
				$absensi_siang_out_1 = Carbon::parse($hari->absensi_siang_out_1);
				$absensi_siang_out_2 = Carbon::parse($hari->absensi_siang_out_2);
				$absensi_siang_in_1 = Carbon::parse($hari->absensi_siang_in_1);
				$absensi_siang_in_2 = Carbon::parse($hari->absensi_siang_in_2);
				$jm = Carbon::parse($hari->jam_masuk);
				$toleransi_terlambat = Carbon::parse($hari->toleransi_terlambat);
				$jm = $jm->addMinutes($toleransi_terlambat->minute);				
			    $jp = Carbon::parse($hari->jam_pulang);
 		        $toleransi_pulang = Carbon::parse($hari->toleransi_pulang);
	         	$jp = $jp->subMinutes($toleransi_pulang->minute);	
	         	$terlambat = Carbon::createFromTime(0, 0, 0);	
	         	$pulang_awal = Carbon::createFromTime(0, 0, 0);
	         	$status = '';		

				if($koreksi_jam >= $scan_in1 && $koreksi_jam <= $scan_in2){
					$terlambat = $koreksi_jam->diff($jm);
					if($koreksi_jam->gt($jm)){
						$status = 'HT';
					}
					$out = Carbon::parse($item->out);
					if($koreksi_jam->lte($jm) && $out->gte($jp)){
						$status = 'H';
					}elseif($out->lt($jp)){
						$status = 'HP';
					}
					if($item->out == '00:00:00'){
						$status = 'HP';
					}
					PegawaiJadwal::where('id', $item->id)->update([
						'in' => $koreksi_jam, 
						'dispensasi_id' => $item->dispensasi_id,
						'status' => $status,
						'jam_kerja' => $out->diff($koreksi_jam)->format('%H:%I:%S'),
						'terlambat' => $terlambat->format('%H:%I:%S')
					]);				
				}

				if($koreksi_jam >= $scan_out1  && $koreksi_jam <= $scan_out2){
					$pulang_awal = $jp->diff($koreksi_jam);				
					if($koreksi_jam->lt($jp)){
						$status = 'HP';
					}
					$in = Carbon::parse($item->in);
					if($in->lte($jm) && $koreksi_jam->gte($jp)){
						$status = 'H';
					}elseif($in->gt($jm)){
						$status = 'HT';
					}
					PegawaiJadwal::where('id', $item->id)->update([
						'out' => $koreksi_jam, 
						'dispensasi_id' => $item->dispensasi_id,
						'status' => $status,
						'jam_kerja' => $koreksi_jam->diff($in)->format('%H:%I:%S'),
						'pulang_awal' => $pulang_awal->format('%H:%I:%S')
					]);			
				}

				if($hari->is_siang_absensi == 1){
					if($koreksi_jam >= $absensi_siang_out_1 && $koreksi_jam <= $absensi_siang_out_2){
						PegawaiJadwal::where('id', $item->id)->update(['scan_1' => $koreksi_jam, 'dispensasi_id' => $item->$dispensasi_id]);
					}

					if($koreksi_jam >= $absensi_siang_in_1 && $koreksi_jam <= $absensi_siang_in_2){
						PegawaiJadwal::where('id', $item->id)->update(['scan_2' => $koreksi_jam, 'dispensasi_id' => $item->dispensasi_id]);
					}
				}				
			}
		}

		return $dispensasi;
	}

	public function deleteEvent($event_id)
	{
		PegawaiJadwal::where('event_id', $event_id)->update([
 			'event_id'  => null,
 			'status'	=> null
 		]);
	}

	public function sinkronisasiKehadiran($unker, $start, $end, $peg)
	{
		$kalkulasi = null;
		$peg_jadwal = PegawaiJadwal::join('peg_data_induk','peg_data_induk.id','=','peg_jadwal.peg_id')
					  ->leftJoin('ketidakhadiran','ketidakhadiran.id','=','peg_jadwal.ketidakhadiran_id')
					  ->leftJoin('ref_ijin','ketidakhadiran.keterangan_id','=','ref_ijin.id')
					  ->where(function($filter) use($unker, $start, $end, $peg) {
					  	if(!empty($unker)){
					  		$filter->where('peg_data_induk.id_unker', $unker);
					  	}
					  	if(!empty($start) && !empty($end)){
					  		$filter->where('peg_jadwal.tanggal','>=',Carbon::parse($start))->where('peg_jadwal.tanggal','<=',Carbon::parse($end));	
					  	}
					  	if(!empty($peg)){
					  		$filter->whereIn('peg_jadwal.peg_id', $peg);
					  	}
					  })					
					  ->get(['peg_jadwal.peg_id','peg_jadwal.jadwal_id','dispensasi_id','peg_data_induk.id_finger','peg_jadwal.tanggal','peg_jadwal.id','peg_jadwal.event_id','ref_ijin.symbol','peg_jadwal.ketidakhadiran_id']);
		
		foreach ($peg_jadwal as $item) {			
			$tanggal = Carbon::parse($item->tanggal);
			$jadwal = Jadwal::find($item->jadwal_id);
			
			if(!empty($item->event_id)){
				$item->where('id', $item->id)->update(['status' => 'L']);			
			}
			elseif(!empty($item->ketidakhadiran_id)){
				$item->where('id', $item->id)->update(['status' => $item->symbol,'in' => '00:00:00', 'out' => '00:00:00']);
			}
			else{
				if(!empty($jadwal)){
					$hari_id = $tanggal->format('N');
					$hari_kerja = $jadwal->hari()->where('hari', $hari_id)->first();
										
					if(!empty($hari_kerja)){	

						$kalkulasi = $this->kalkulasi($hari_kerja, $tanggal, $item->id_finger);
						if($kalkulasi){
							$item->where('id', $item->id)->update($kalkulasi);							
						}
					}else{
						$item->where('id', $item->id)->update(['status' => 'L']);
					}
				}
			}			
		}

		return $kalkulasi;
	}

	private function kalkulasi($hari_kerja, $tanggal, $id_finger)
	{
		$jm = Carbon::parse($hari_kerja->jam_masuk);	
		$toleransi_terlambat = Carbon::parse($hari_kerja->toleransi_terlambat);
		$jm = $jm->addMinutes($toleransi_terlambat->minute);				
		$jp = Carbon::parse($hari_kerja->jam_pulang);
	 	$toleransi_pulang = Carbon::parse($hari_kerja->toleransi_pulang);
		$jp = $jp->subMinutes($toleransi_pulang->minute);
		$cin_time = Carbon::createFromTime(0, 0, 0);
		$cout_time = Carbon::createFromTime(0, 0, 0);
		$terlambat = Carbon::createFromTime(0, 0, 0);
		$pulang_awal = Carbon::createFromTime(0, 0, 0);
		$scan_1 = Carbon::createFromTime(0, 0, 0);
		$scan_2 = Carbon::createFromTime(0, 0, 0);
		$status = '';

		$query = "select min(x.cin) cin,max(x.COUT) cout,max(x.COUT_SIANG)cout_siang, min(x.CIN_SIANG)cin_siang
				  from (
					select 
						CIN = (SELECT b.TransactionTime FROM NGAC_AUTHLOG b where a.IndexKey = b.IndexKey and Cast(b.TransactionTime as time) >= :in_start and Cast(b.TransactionTime as time) <= :in_end),
						COUT = (SELECT b.TransactionTime FROM NGAC_AUTHLOG b where a.IndexKey = b.IndexKey and Cast(b.TransactionTime as time) >= :out_start and Cast(b.TransactionTime as time) <= :out_end),
						COUT_SIANG = (SELECT b.TransactionTime FROM NGAC_AUTHLOG b where a.IndexKey = b.IndexKey and Cast(b.TransactionTime as time) >= :out_siang_start and Cast(b.TransactionTime as time) <= :out_siang_end),
						CIN_SIANG = (SELECT b.TransactionTime FROM NGAC_AUTHLOG b where a.IndexKey = b.IndexKey and Cast(b.TransactionTime as time) >= :in_siang_start and Cast(b.TransactionTime as time) <= :in_siang_end)
				  from NGAC_AUTHLOG a
				  where a.UserID = :id and cast(a.TransactionTime as date) = :tanggal 
				  )x";
		$authlog = DB::connection('sqlsrv')->select($query, [
					'id'	=> $id_finger,
					'tanggal' => $tanggal,
					'in_start'	=> date('H:i', strtotime($hari_kerja->scan_in1)),
					'in_end'	=> date('H:i', strtotime($hari_kerja->scan_in2)),
					'out_start'	=> date('H:i', strtotime($hari_kerja->scan_out1)),
					'out_end'	=> date('H:i', strtotime($hari_kerja->scan_out2)),
					'out_siang_start'	=> date('H:i', strtotime($hari_kerja->absensi_siang_out_1)),
					'out_siang_end'	=> date('H:i', strtotime($hari_kerja->absensi_siang_out_2)),
					'in_siang_start'	=> date('H:i', strtotime($hari_kerja->absensi_siang_in_1)),
					'in_siang_end'	=> date('H:i', strtotime($hari_kerja->absensi_siang_in_2)),
				]);

		foreach ($authlog as $log) {
			if(empty($log->cin) && empty($log->cout)){
				$status = 'A';
			}elseif(empty($log->cin)){
				$status = 'HT';
				$cout_date = Carbon::parse($log->cout);
				$cout_time = Carbon::parse($cout_date->toTimeString());		
				if($cout_time->lt($jp)){					
					$pulang_awal = $cin_time->diff($jm);
				}			
			}elseif(empty($log->cout)){
				$status = 'HP';
				$cin_date = Carbon::parse($log->cin);
				$cin_time = Carbon::parse($cin_date->toTimeString());
				if($cin_time->gt($jm)){
					$terlambat = $cin_time->diff($jm);
				}	
			}else{
				$status = 'H';
			
				$cin_date = Carbon::parse($log->cin);
				$cin_time = Carbon::parse($cin_date->toTimeString());
				if($cin_time->gt($jm)){
					$status = 'HT';
					$terlambat = $cin_time->diff($jm);
				}
				
				$cout_date = Carbon::parse($log->cout);
				$cout_time = Carbon::parse($cout_date->toTimeString());		
				if($cout_time->lt($jp)){	
					$status = 'HP';				
					$pulang_awal = $cin_time->diff($jm);
				}				
			}

			if(!empty($log->cout_siang)){				
				$cout_siang_date = Carbon::parse($log->cout_siang);
				$scan_1 = Carbon::parse($cout_siang_date->toTimeString());							
			}

			if(!empty($log->cin_siang)){
				$cin_siang_date = Carbon::parse($log->cin_siang);
				$scan_2 = Carbon::parse($cin_siang_date->toTimeString());				
			}


		}
		
		return [
			'in' => $cin_time->toTimeString(),
			'out' => $cout_time->toTimeString(),
			'terlambat' => $terlambat->format('%H:%I:%S'),
			'pulang_awal' => $pulang_awal->format('%H:%I:%S'),	
			'jam_kerja' => $cout_time->diff($cin_time)->format('%H:%I:%S'),		
			'status'	=> $status,
			'scan_1' => $scan_1->toTimeString(),
			'scan_2' => $scan_2->toTimeString()
		];

	}

	public function kalkulasiDispensasi($unker, $start, $end, $peg)
	{
		$dispensasi = Dispensasi::join('peg_data_induk','peg_data_induk.id','=','dispensasi.peg_id')
						->where(function($filter) use($unker, $start, $end, $peg) {
					  	if(!empty($unker)){
					  		$filter->where('peg_data_induk.id_unker', $unker);
					  	}
					  	if(!empty($start) && !empty($end)){
					  		$filter->where('dispensasi.tanggal','>=',Carbon::parse($start))->where('dispensasi.tanggal','<=',Carbon::parse($end));	
					  	}
					  	if(!empty($peg)){
					  		$filter->whereIn('dispensasi.peg_id', $peg);
					  	}
					  })
					  ->get(['dispensasi.peg_id','dispensasi.tanggal','dispensasi.id']);

		foreach ($dispensasi as $item) {
			$peg_jadwal = PegawaiJadwal::where('peg_id',$item->peg_id)->where('tanggal',$item->tanggal)->get();	

			$tanggal = Carbon::parse($item->tanggal);
			foreach ($peg_jadwal as $value) {
				$jadwal = Jadwal::find($value->jadwal_id);
				if(!empty($jadwal)){
					$hari_id = $tanggal->format('N');
					$hari_kerja = $jadwal->hari()->where('hari', $hari_id)->first();
					if(!empty($hari_kerja)){
						$jm = Carbon::parse($hari_kerja->jam_masuk);
						$toleransi_terlambat = Carbon::parse($hari_kerja->toleransi_terlambat);
						$jm = $jm->addMinutes($toleransi_terlambat->minute);				
						$jp = Carbon::parse($hari_kerja->jam_pulang);
					 	$toleransi_pulang = Carbon::parse($hari_kerja->toleransi_pulang);
						$jp = $jp->subMinutes($toleransi_pulang->minute);
						$status = '';


					}
				}
			}
		}

		return $peg_jadwal;
	}

}

?>