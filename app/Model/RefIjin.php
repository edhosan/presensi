<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RefIjin extends Model
{
  protected $table = 'ref_ijin';

  protected $fillable = ['name'];

  public function ketidakhadiran()
  {
    return $this->hasMany('App\Model\Ketidakhadiran','keterangan_id');
  }
}
