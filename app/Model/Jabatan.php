<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
  protected $connection = 'mysql2';

  protected $table = 'ref_jabatan';

  protected $primaryKey = 'id_jabatan';

  public function eselon()
  {
    return $this->belongsTo('App\Model\Eselon','id_eselon');
  }
}
