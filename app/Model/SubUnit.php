<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SubUnit extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'ref_subunit';

    protected $primaryKey = 'id_subunit';

    public function opd()
    {
      return $this->belongsToMany('App\Model\OPD','id_unker','id_unker');
    }
}
