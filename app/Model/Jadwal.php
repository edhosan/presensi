<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
  protected $table = 'jadwal_kerja';

  protected $fillable = ['name','title','start','end','id_unker','nama_unker'];

  public function hari()
  {
     return $this->hasMany('App\Model\Hari');
  }
}
