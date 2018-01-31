<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Carbon\Carbon;
use App\Model\PegawaiJadwal;
use App\Model\AuthLog;

class ProsesPerhitungan implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $opd;
    protected $start;
    protected $end;
    protected $peg;



    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($opd, $start, $end, $peg_id)
    {
        $this->opd = $opd;
        $this->start = date('Y-m-d', strtotime($start));
        $this->end =  date('Y-m-d', strtotime($end));
        $this->peg = $peg_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
         $peg_jadwal = PegawaiJadwal::join('peg_data_induk','peg_data_induk.id','=','peg_jadwal.peg_id')
                    ->leftJoin('ketidakhadiran','ketidakhadiran.id','=','peg_jadwal.ketidakhadiran_id')
                    ->leftJoin('ref_ijin','ref_ijin.id','=','ketidakhadiran.keterangan_id')
                    ->where('peg_data_induk.id_unker','=', $this->opd)
                    ->where('peg_jadwal.tanggal','>=', date('Y-m-d', strtotime($this->start)) )
                    ->where('peg_jadwal.tanggal','<=', date('Y-m-d', strtotime($this->end)) )
                    ->where(function($query) use($peg) {
                      if(!empty($peg)) {
                        $query->whereIn('peg_jadwal.peg_id', $peg);
                      }
                    })
                    ->select('peg_jadwal.id','peg_jadwal.peg_id','peg_jadwal.jadwal_id','peg_jadwal.ketidakhadiran_id','peg_jadwal.tanggal','peg_jadwal.event_id',
                             'peg_data_induk.id_finger',
                             'ref_ijin.symbol')
                    ->get();

        $total = $peg_jadwal->count();

        $i = 1;
        PegawaiJadwal::join('peg_data_induk','peg_data_induk.id','=','peg_jadwal.peg_id')
                    ->where('peg_data_induk.id_unker','=', $this->opd)
                    ->where('peg_jadwal.tanggal','>=', date('Y-m-d', strtotime($this->start)) )
                    ->where('peg_jadwal.tanggal','<=', date('Y-m-d', strtotime($this->end)) )
                    ->where(function($query) use($peg) {
                      if(!empty($peg)) {
                        $query->whereIn('peg_jadwal.peg_id', $peg);
                      }
                    })
                    ->update([
                      'peg_jadwal.ketidakhadiran_id' => 0,
                      'peg_jadwal.in'  => '00:00:00',
                      'peg_jadwal.out' => '00:00:00',
                      'peg_jadwal.terlambat' => 0,
                      'peg_jadwal.pulang_awal' => 0,
                      'peg_jadwal.jam_kerja' => '00:00:00',
                      'status'  => ''
                    ]);

        foreach ($peg_jadwal as $jadwal) {
            $log = AuthLog::where('UserID', $jadwal->id_finger)
                      ->whereDate('TransactionTime', $jadwal->tanggal)
                      ->get();

            $tanggal = Carbon::parse($jadwal->tanggal);
            $hari_id = $tanggal->format('N');
            $hari = Hari::where('hari', $hari_id)
                    ->where('jadwal_id',$jadwal->jadwal_id)
                    ->first();

             if($hari){
              $jm = Carbon::parse($hari->jam_masuk);
              $toleransi_terlambat = Carbon::parse($hari->toleransi_terlambat);
              $jm = $jm->addMinutes($toleransi_terlambat->minute);
              $jp = Carbon::parse($hari->jam_pulang);
              $toleransi_pulang = Carbon::parse($hari->toleransi_pulang);
              $jp = $jp->subMinutes($toleransi_pulang->minute);
              $in = Carbon::createFromTime(0, 0, 0);
              $out = Carbon::createFromTime(0, 0, 0);
              $jam_kerja = Carbon::createFromTime(0, 0, 0);
              $status_hadir = '';
              $dispensasi = Dispensasi::where('tanggal', date('Y-m-d', strtotime($tanggal)) )
                            ->where('peg_id', $jadwal->peg_id)
                            ->get();

              foreach ($log as $authlog) {
                $date = Carbon::parse($authlog->TransactionTime);
                $time = Carbon::parse($date->toTimeString());
                $scan_in1 = Carbon::parse($hari->scan_in1);
                $scan_in2 = Carbon::parse($hari->scan_in2);
                $scan_out1 = Carbon::parse($hari->scan_out1);
                $scan_out2 = Carbon::parse($hari->scan_out2);
                $terlambat = Carbon::createFromTime(0, 0, 0);
                $pulang_awal = Carbon::createFromTime(0, 0, 0);
                $peg_jadwal = PegawaiJadwal::find($jadwal->id);

                if($time->gte($scan_in1) && $time->lte($scan_in2)){
                  $in = $time;
                  if( $time->gt($jm) ) {
                    $terlambat = $time->diff($jm)->format('%H:%I:%S');
                    $status_hadir = 'HT';
                  }

                  $peg_jadwal->update([
                    'in'  => $time->toTimeString(),
                    'terlambat' => $terlambat,
                    'status'  => $status_hadir
                  ]);
                }

                if($time->gte($scan_out1) && $time->lte($scan_out2) ){
                  $out = $time;
                  if($in->toTimeString() != '00:00:00' && $time->toTimeString() != '00:00:00'){
                    $jam_kerja = $time->diff($in)->format('%H:%I:%S');
                  }

                  if($time->lt($jp)) {
                    $pulang_awal = $jp->diff($time)->format('%H:%I:%S');
                    $status_hadir = 'HP';
                  }

                  $peg_jadwal->update([
                    'out'  => $time->toTimeString(),
                    'pulang_awal' => $pulang_awal,
                    'jam_kerja' => $jam_kerja,
                    'status'  => $status_hadir
                  ]);
                }
              }

              foreach ($dispensasi as $dispen) {
                $time = Carbon::parse($dispen->koreksi_jam);
                $scan_in1 = Carbon::parse($hari->scan_in1);
                $scan_in2 = Carbon::parse($hari->scan_in2);
                $scan_out1 = Carbon::parse($hari->scan_out1);
                $scan_out2 = Carbon::parse($hari->scan_out2);
                $terlambat = Carbon::createFromTime(0, 0, 0);
                $pulang_awal = Carbon::createFromTime(0, 0, 0);
                $peg_jadwal = PegawaiJadwal::find($jadwal->id);

                if($time->gte($scan_in1) && $time->lte($scan_in2)){
                  $in = $time;
                  if( $time->gt($jm) ) {
                    $terlambat = $time->diff($jm)->format('%H:%I:%S');
                    $status_hadir = 'HT';
                  }

                  $peg_jadwal->update([
                    'in'  => $time->toTimeString(),
                    'terlambat' => $terlambat,
                    'status'  => $status_hadir
                  ]);
                }

                if($time->gte($scan_out1) && $time->lte($scan_out2) ){
                  $out = $time;
                  if($in->toTimeString() != '00:00:00' && $time->toTimeString() != '00:00:00'){
                    $jam_kerja = $time->diff($in)->format('%H:%I:%S');
                  }

                  if($time->lt($jp)) {
                    $pulang_awal = $jp->diff($time)->format('%H:%I:%S');
                    $status_hadir = 'HP';
                  }

                  $peg_jadwal->update([
                    'out'  => $time->toTimeString(),
                    'pulang_awal' => $pulang_awal,
                    'jam_kerja' => $jam_kerja,
                    'status'  => $status_hadir
                  ]);
                }
              }

              if($in->toTimeString() != '00:00:00' && $out->toTimeString() != "00:00:00"){
                if($status_hadir != 'HT' && $status_hadir != 'HP'){
                  PegawaiJadwal::find($jadwal->id)->update(['status' => 'H']);
                }
              }

              if($in->toTimeString() == '00:00:00' || $out->toTimeString() == '00:00:00'){
                  if(!empty($jadwal->event_id)){
                    PegawaiJadwal::where('id',$jadwal->id)->update(['status' => 'L']);
                  }
                  else{
                    PegawaiJadwal::find($jadwal->id)->update(['status' => 'A']);
                  }
              }

              if($jadwal->ketidakhadiran_id != 0) {
                PegawaiJadwal::where('id',$jadwal->id)->update(['ketidakhadiran_id' => $jadwal->ketidakhadiran_id, 'status' => $jadwal->symbol]);
              }
            }
            else{
              PegawaiJadwal::find($jadwal->id)->update(['status' => 'L']);
            }
        
            $i++;
        }
    }
}
