<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class JenisPengeluaran extends Model
{
   	protected $table = 'tpp_jenis_pengeluaran';

  	protected $fillable = ['tpp_kategori_id','jns_pengeluaran','kriteria'];

  	public function kategori()
	{
		return $this->belongsTo('App\Model\TPPKategori','tpp_kategori_id');
	}
}
