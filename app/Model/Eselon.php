<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Eselon extends Model
{
  protected $connection = 'mysql2';

  protected $table = 'ref_eselon';

  protected $primaryKey = 'id_eselon';

  public function jabatan()
  {
    return $this->hasMany('App\Model\Jabatan','id_eselon','id_eselon');
  }
}
