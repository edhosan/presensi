<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Hari extends Model
{
  protected $table = 'hari_kerja';

  protected $fillable = ['jadwal_id','hari','jam_masuk','jam_pulang','toleransi_terlambat','toleransi_pulang','scan_in1','scan_in2','scan_out1','scan_out2',
                         'is_siang_absensi','absensi_siang_out_1','absensi_siang_out_2','absensi_siang_in_1','absensi_siang_in_2'];

  protected $hari = [
    '7' =>  'Minggu',
    '1' =>  'Senin',
    '2' =>  'Selasa',
    '3' =>  'Rabu',
    '4' =>  'Kamis',
    '5' =>  'Jumat',
    '6' =>  'Saptu'
  ];

  public function jadwal()
  {
    return $this->belongsTo('App\Model\Jadwal');
  }

  public function getHari()
  {
    return $this->hari;
  }
}
