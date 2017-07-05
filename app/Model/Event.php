<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
      protected $table = 'event';

      protected $fillable = ['title','start_date','end_date'];

      public function pegawaiJadwal()
      {
        return $this->hasMany('App\Model\PegawaiJadwal');
      }
}
