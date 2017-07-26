<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Ketidakhadiran extends Model
{
    use SoftDeletes;

    protected $table = 'ketidakhadiran';

    protected $fillable = ['keterangan_id','start','end','jam_start','jam_end','keperluaan'];

    protected $dates = ['deleted_at'];

    public function keterangan()
    {
      return $this->belongsTo('App\Model\RefIjin');
    }

    public function pegawai()
    {
      return $this->belongsTo('App\Model\DataInduk');
    }
}
