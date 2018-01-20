<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jadwal extends Model
{
  use SoftDeletes;

  protected $table = 'jadwal_kerja';

  protected $fillable = ['name','title','start','end','id_unker','nama_unker','aktif'];

  protected $dates = ['deleted_at'];

  public function hari()
  {
     return $this->hasMany('App\Model\Hari');
  }

  protected $casts = ['aktif' => 'boolean' ];

  public function scopeAktif($query) 
  {
  	return $query->where('aktif', true);
  }
}
