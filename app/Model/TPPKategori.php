<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TPPKategori extends Model
{
    protected $table = 'tpp_kategori';

  	protected $fillable = ['nm_kategori'];

  	public function jenisPengeluaran()
    {
      return $this->hasMany('App\Model\JenisPengeluaran','tpp_kategori_id');
    }
}
