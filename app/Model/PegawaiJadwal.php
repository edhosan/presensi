<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PegawaiJadwal extends Model
{
  use SoftDeletes;

  protected $table = 'peg_jadwal';

  protected $fillable = ['tanggal','peg_id','jadwal_id','event_id','ketidakhadiran_id','in','out','terlambat','pulang_awal','jam_kerja','status'];

  protected $dates = ['deleted_at'];

  public function jadwal()
  {
    return $this->belongsTo('App\Model\Jadwal','jadwal_id','id');
  }

  public function pegawai()
  {
    return $this->belongsTo('App\Model\DataInduk','peg_id','id');
  }

  public function event()
  {
    return $this->belongsTo('App\Model\Event');
  }



}
