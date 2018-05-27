<?php 
namespace App\Library;

use App\Model\PegawaiJadwal;
use App\Model\Event;
use Carbon\Carbon;
use App\Model\Ketidakhadiran;
use App\Model\Dispensasi;
use App\Model\Jadwal;
use DB;

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
			$peg_jadwal = PegawaiJadwal::where('peg_jadwal.peg_id', $item->peg_id)
						  ->where('peg_jadwal.tanggal','>=',Carbon::parse($item->start))
  						  ->where('peg_jadwal.tanggal','<=',Carbon::parse($item->end))
  						  ->update([
  						  	'ketidakhadiran_id' => $item->id,
  						  	'status' => $item->symbol
  						  ]);
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
					  ->get(['peg_jadwal.jadwal_id','peg_jadwal.tanggal','peg_jadwal.id', DB::raw('dispensasi.id as dispensasi_id'), 'peg_data_induk.nama','dispensasi.koreksi_jam']);

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

				if($koreksi_jam >= $scan_in1 && $koreksi_jam <= $scan_in2){
					PegawaiJadwal::where('id', $item->id)->update(['in' => $koreksi_jam, 'dispensasi_id' => $item->dispensasi_id]);
				}

				if($koreksi_jam >= $scan_out1  && $koreksi_jam <= $scan_out2){
					PegawaiJadwal::where('id', $item->id)->update(['out' => $koreksi_jam, 'dispensasi_id' => $item->dispensasi_id]);
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
		$peg_jadwal = PegawaiJadwal::whereNull('ketidakhadiran_id')->orWhere('ketidakhadiran_id',0)
					  ->whereNull('event_id')->orWhere('event_id',0)
					  ->where('status','<>','L')
	}
}

?>