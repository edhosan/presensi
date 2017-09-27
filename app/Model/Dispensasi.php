<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dispensasi extends Model
{
      use SoftDeletes;

      protected $table = 'Dispensasi';

      protected $fillable = ['id','peg_id','tanggal','koreksi_jam_masuk','koreksi_jam_pulang','alasan','filename'];

      protected $dates = ['deleted_at'];

      public function pegawai()
      {
        return $this->belongsTo('App\Model\DataInduk','peg_id','id');
      }
}
