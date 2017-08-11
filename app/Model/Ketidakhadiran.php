<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ketidakhadiran extends Model
{
    use SoftDeletes;

    protected $table = 'ketidakhadiran';

    protected $fillable = ['keterangan_id','start','end','jam_start','jam_end','keperluan','peg_id','filename'];

    protected $dates = ['deleted_at'];

    public function keterangan()
    {
      return $this->belongsTo('App\Model\RefIjin');
    }

    public function pegawai()
    {
      return $this->belongsTo('App\Model\DataInduk','peg_id','id');
    }
}
