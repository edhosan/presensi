<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Terminal extends Model
{
      protected $connection = 'sqlsrv';

      protected $table = 'NGAC_TERMINAL';

      public function users()
      {
        return $this->hasMany('App\Model\TerminalUser','TerminalID');
      }
}
