<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OPD extends Model
{
  protected $connection = 'mysql2';

  protected $table = 'ref_unker';

  protected $primaryKey = 'id_unker';

  public function subUnit()
  {
    return $this->hasMany('App\Model\SubUnit','id_unker','id_unker');
  }
}
