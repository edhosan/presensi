<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Validator;

class Jadwal extends Model
{
  protected $table = 'jadwal_kerja';

  protected $fillable = ['name','title','start','end'];

  private $rules = [
    'name'  => 'required'
  ];

  public function validate($data)
  {
    $v = Validator::make($data, $this->rules);
  }
}
