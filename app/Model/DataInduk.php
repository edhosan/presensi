<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DataInduk extends Model
{

    protected $table = 'peg_data_induk';

    protected $fillable = ['type','id_finger','nip','nama','gelar_depan','gelar_belakang','id_unker','nama_unker','id_subunit','nama_subunit','id_pangkat','golru',
                          'pangkat','id_jabatan','nama_jabatan','id_eselon','tmt_pangkat'];

    public function pegawaiJadwal()
    {
      return $this->hasMany('App\Model\PegawaiJadwal');
    }

    public function ketidakhadiran()
    {
      return $this->hasMany('App\Model\Ketidakhadiran','peg_id','id');
    }
}
