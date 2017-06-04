<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TerminalUser extends Model
{
  protected $connection = 'sqlsrv';

  protected $table = 'NGAC_TERMINALUSER';

  public function terminal()
  {
    return $this->belongsTo('App\Model\Terminal', 'TerminalID','ID');
  }
}
