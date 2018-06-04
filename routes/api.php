<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['middleware' => 'auth:api'], function() {
  Route::get('pegawai', 'Auth\RegisterController@apiGetPegawai');
  Route::post('unker', 'Auth\RegisterController@apiGetUnker');
  Route::get('dt_user', 'Auth\RegisterController@apiUser');
  Route::post('delete_user', 'Auth\RegisterController@apiDeleteUser');

  Route::get('role_list', 'Auth\RoleController@apiGetRole');
  Route::post('role_delete', 'Auth\RoleController@apiDeleteRole');

  Route::get('permission_list', 'Auth\PermissionController@apiGetPermission');
  Route::post('permission_delete', 'Auth\PermissionController@apiDeletePermission');

  Route::get('get_idfinger', 'Proses\DataIndukController@apiGetId');
  Route::post('subunit', 'Proses\DataIndukController@apiGetSubUnit');
  Route::get('jabatan', 'Proses\DataIndukController@apiGetJabatan');
  Route::post('pangkat', 'Proses\DataIndukController@apiGetPangkat');
  Route::get('datainduk_list', 'Proses\DataIndukController@apiGetDataInduk');
  Route::post('datainduk_delete', 'Proses\DataIndukController@apiDeleteDataInduk');

  Route::get('kalendar_list', 'Referensi\EventController@apiEvent');

  Route::get('jadwal_list', 'Referensi\JadwalController@apiJadwalList');
  Route::post('jadwal_delete', 'Referensi\JadwalController@apiDeleteJadwal');

  Route::post('hari', 'Referensi\HariController@apiGetHari');

  Route::post('getNamePeg', 'Proses\PegawaiJadwalController@apiNameDataInduk');
  Route::get('peg_jadwal_list', 'Proses\PegawaiJadwalController@apiGetJadwalPegawai');
  Route::post('peg_jadwal_detail', 'Proses\PegawaiJadwalController@apiGetJadwalPegawaiDetail');
  Route::post('jadwal_detail', 'Proses\PegawaiJadwalController@apiJadwalDetail');
  Route::post('peg_hari_kerja', 'Proses\PegawaiJadwalController@apiGetPegawaiHariKerja');
  Route::post('peg_jadwal_add', 'Proses\PegawaiJadwalController@apiAddJadwal');
  Route::post('peg_jadwal_delete', 'Proses\PegawaiJadwalController@apiDeleteJadwal');
  Route::get('hari_kerja_detail', 'Proses\PegawaiJadwalController@apiHariKerja');

  Route::get('ref_ijin_list', 'Referensi\RefIjinController@apiListRefIjin');
  Route::post('ref_ijin_delete', 'Referensi\RefIjinController@apiDeleteRefIjin');

  Route::get('ketidakhadiran_list', 'Proses\KetidakhadiranController@apiListKetidakhadiran');
  Route::post('ketidakhadiran_delete', 'Proses\KetidakhadiranController@apiDeleteKetidakhadiran');

  Route::get('authlog_list', 'Referensi\AuthLogController@apiAuthLogList');
  Route::post('authlog_delete', 'Referensi\AuthLogController@apiDeleteAuthLog');

  Route::post('/riwayat/get_riwayat_absensi', 'Proses\RiwayatAbsensiController@getLogFinger')->name('riwayat.absensi');

  Route::get('search_peg', 'Proses\DataIndukController@apiSearchPegawai');

  Route::get('dispensasi_list', 'Proses\DispensasiController@apiListDispensasi')->name('dispensasi.list');
  Route::post('dispensasi_delete', 'Proses\DispensasiController@apiDeleteDispensasi')->name('dispensasi.delete');  
  Route::get('/sinkronisasi/hasil', 'Proses\SinkronisasiController@apiGetHasilSinkronisasi')->name('sinkronisasi.hasil');
});

Route::group(['namespace' => 'api\v1', 'middleware' => 'auth:api'], function() {
  Route::get('/pengumuman_index','api\v1\WidgetController@pengumumanList');
});

Route::group(['namespace' => 'Referensi', 'middleware' => 'auth:api'], function() {
  Route::get('/get_kategori','MasterTPPController@apiGetKategori');
  Route::get('/get_jns_pengeluaran/{kategori_id}','MasterTPPController@apiGetJenisPengeluaran');
  Route::post('/delete_kategori','MasterTPPController@apiDeleteKategori');
  Route::post('/delete_jns_pengeluaran','MasterTPPController@apiDeleteJenisPengeluaran');
});
