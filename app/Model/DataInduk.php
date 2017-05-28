<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DataInduk extends Model
{
    protected $table = 'peg_data_induk';

    protected $fillable = ['type','nip','nama','gelar_depan','gelar_belakang','id_unker','nama_unker','id_subunit','nama_subunit','id_pangkat','golru',
                          'pangkat','id_jabatan','nama_jabatan','id_eselon','tmt_pangkat'];
}
