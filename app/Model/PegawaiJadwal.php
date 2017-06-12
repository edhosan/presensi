<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PegawaiJadwal extends Model
{
  use SoftDeletes;

  protected $table = 'peg_jadwal';

  protected $fillable = ['tanggal','peg_id','jadwal_id','hari_id'];

  protected $dates = ['deleted_at'];

  public function jadwal()
  {
    return $this->belongsTo('App\Model\Jadwal');
  }

  public function pegawai()
  {
    return $this->belongsTo('App\Model\DataInduk');
  }

  public function hari()
  {
    return $this->belongsTo('App\Model\Hari');
  }

}
