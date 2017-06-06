<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Hari extends Model
{
  protected $table = 'hari_kerja';

  protected $fillable = ['jadwal_id','hari','jam_masuk','jam_pulang','toleransi_terlambat','toleransi_pulang'];

  public function jadwal()
  {
    return $this->belongsTo('App\Model\Jadwal');
  }
}
