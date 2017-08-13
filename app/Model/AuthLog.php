<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AuthLog extends Model
{
    protected $connection = 'sqlsrv';

    protected $table = 'NGAC_AUTHLOG';

    public function terminal()
    {
      return $this->belongsTo('App\Model\Terminal', 'TerminalID','ID');
    }
}
