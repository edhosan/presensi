<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RincianPengeluaran extends Model
{
    protected $table = 'tpp_kriteria';

  	protected $fillable = ['tpp_jenis_pengeluaran_id','kriteria_id','lokasi_biasa','lokasi_terpencil','lokasi_sangat_terpencil','tahun','keterangan','kriteria_name'];

}
