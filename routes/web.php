<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

Auth::routes();
Route::group(['middleware' => 'guest'], function(){
  Route::get('/','Auth\LoginController@index');
});

Route::group(['middleware' => 'auth'], function() {
  Route::get('home', 'HomeController@index')->name('home');
  Route::get('logout', 'Auth\LoginController@logout');
  Route::get('register', 'Auth\RegisterController@showRegister')->name('register');
  Route::get('ganti_password', 'Auth\RegisterController@changePassword')->name('change.password');
  Route::post('update_password', 'Auth\RegisterController@updatePassword')->name('password.change');
  Route::get('user_edit/{id}', 'Auth\RegisterController@showEdit')->name('user_edit');
  Route::get('user', 'Auth\RegisterController@getListUser')->name('user');
  Route::post('user_update', 'Auth\RegisterController@updateUser')->name('user_update');

  Route::get('role', 'Auth\RoleController@showRole')->name('role');
  Route::post('role_create', 'Auth\RoleController@create')->name('role_create');
  Route::get('role_list', 'Auth\RoleController@index')->name('role_list');
  Route::get('role_update/{id}', 'Auth\RoleController@showUpdate')->name('role_update');
  Route::post('save_update', 'Auth\RoleController@update')->name('save_update');

  Route::get('permission_list', 'Auth\PermissionController@index')->name('permission_list');
  Route::get('permission', 'Auth\PermissionController@showPermission')->name('permission');
  Route::get('permission_edit/{id}', 'Auth\PermissionController@showPermissionUpdate')->name('permission_edit');
  Route::post('permission_update', 'Auth\PermissionController@update')->name('permission_update');
  Route::post('permission_create', 'Auth\PermissionController@create')->name('permission_create');

  Route::get('datainduk_list', 'Proses\DataIndukController@index')->name('datainduk_list');
  Route::get('datainduk_form', 'Proses\DataIndukController@showForm')->name('datainduk_form');
  Route::post('datainduk_create', 'Proses\DataIndukController@create')->name('datainduk_create');
  Route::post('datainduk_update', 'Proses\DataIndukController@update')->name('datainduk_update');
  Route::get('datainduk_edit/{id}', 'Proses\DataIndukController@showEdit')->name('datainduk_edit');

  Route::get('kalendar_create', 'Referensi\EventController@create')->name('kalendar_create');
  Route::post('kalendar_update', 'Referensi\EventController@update')->name('kalendar_update');
  Route::post('kalendar_create', 'Referensi\EventController@store')->name('kalendar_create');
  Route::get('kalendar_list', 'Referensi\EventController@index')->name('kalendar_list');
  Route::get('kalendar_edit/{id}', 'Referensi\EventController@edit')->name('kalendar_edit');
  Route::get('kalendar_delete/{id}', 'Referensi\EventController@delete')->name('kalendar_delete');

  Route::get('jadwal_create', 'Referensi\JadwalController@create')->name('jadwal_create');
  Route::get('jadwal_list', 'Referensi\JadwalController@index')->name('jadwal_list');
  Route::post('jadwal_create', 'Referensi\JadwalController@store')->name('jadwal_create');
  Route::get('jadwal_edit/{id}', 'Referensi\JadwalController@edit')->name('jadwal_edit');
  Route::post('jadwal_update', 'Referensi\JadwalController@update')->name('jadwal.update');

  Route::get('hari_create/{id_jadwal}', 'Referensi\HariController@create')->name('hari.create');
  Route::post('hari_create', 'Referensi\HariController@store')->name('hari.store');
  Route::get('hari_edit', 'Referensi\HariController@edit')->name('hari.edit');
  Route::post('hari_update', 'Referensi\HariController@update')->name('hari.update');
  Route::get('hari_delete', 'Referensi\HariController@delete')->name('hari.delete');

  Route::get('peg_jadwal_list', 'Proses\PegawaiJadwalController@index')->name('peg_jadwal.list');
  Route::get('peg_jadwal_create', 'Proses\PegawaiJadwalController@create')->name('peg_jadwal.create');
  Route::post('peg_jadwal_save', 'Proses\PegawaiJadwalController@saveCreate')->name('peg_jadwal.save');
  Route::get('peg_jadwal_detail/{id_peg}', 'Proses\PegawaiJadwalController@detail')->name('peg_jadwal.detail');
  Route::get('peg_jadwal_edit/{id_peg}/{id_jadwal}', 'Proses\PegawaiJadwalController@edit')->name('peg_jadwal.edit');
  Route::get('peg_jadwal_delete_jadwal/{id_jadwal}', 'Proses\PegawaiJadwalController@deleteJadwal')->name('peg_jadwal.delete.jadwal');

  Route::get('ref_ijin_list', 'Referensi\RefIjinController@index')->name('ref_ijin.list');
  Route::get('ref_ijin_create', 'Referensi\RefIjinController@create')->name('ref_ijin.form');
  Route::post('ref_ijin_store', 'Referensi\RefIjinController@store')->name('ref_ijin.store');
  Route::get('ref_ijin_edit/{id}', 'Referensi\RefIjinController@edit')->name('ref_ijin.edit');
  Route::post('ref_ijin_update', 'Referensi\RefIjinController@update')->name('ref_ijin.update');

  Route::get('ketidakhadiran_list', 'Proses\KetidakhadiranController@index')->name('ketidakhadiran.list');
  Route::get('ketidakhadiran_create', 'Proses\KetidakhadiranController@create')->name('ketidakhadiran.create');
  Route::get('ketidakhadiran_edit/{id}', 'Proses\KetidakhadiranController@edit')->name('ketidakhadiran.edit');
  Route::post('ketidakhadiran_save', 'Proses\KetidakhadiranController@saveCreate')->name('ketidakhadiran.save');
  Route::post('ketidakhadiran_update', 'Proses\KetidakhadiranController@saveUpdate')->name('ketidakhadiran.update');

  Route::get('kalkulasi_form', 'Proses\KalkulasiController@index')->name('kalkulasi.form');
  Route::post('kalkulasi_proses', 'Proses\KalkulasiController@prosesKalkulasi')->name('kalkulasi.proses');
  Route::get('kalkulasi_progress', 'Proses\KalkulasiController@apiGetProgress')->name('kalkulasi.progress');

  Route::get('authlog_list', 'Referensi\AuthLogController@index')->name('authlog.list');

  Route::get('lap_bulanan', 'Laporan\RekapOPDController@index')->name('laporan.bulanan');
  Route::post('lap_bulanan_view', 'Laporan\RekapOPDController@viewReport')->name('laporan.bulanan.report');
  Route::get('cetak_laporan_bulanan', 'Laporan\RekapOPDController@cetak')->name('cetak.laporan.bulanan');

  Route::get('laporan_harian', 'Laporan\LapHarianController@index')->name('laporan.harian');
  Route::post('cetak_laporan_harian', 'Laporan\LapHarianController@cetak')->name('cetak.laporan.harian');

  Route::get('laporan_ketidakhadiran', 'Laporan\LapKetidakhadiranController@index')->name('laporan.ketidakhadiran');
  Route::post('cetak_laporan_ketidakhadiran', 'Laporan\LapKetidakhadiranController@cetak')->name('cetak.laporan.ketidakhadiran');

  Route::get('dispensasi_list', 'Proses\DispensasiController@index')->name('dispensasi.list');
  Route::get('dispensasi_create', 'Proses\DispensasiController@create')->name('dispensasi.create');
  Route::get('dispensasi_edit/{id}', 'Proses\DispensasiController@edit')->name('dispensasi.edit');
  Route::post('dispensasi_save', 'Proses\DispensasiController@saveCreate')->name('dispensasi.save');
  Route::post('dispensasi_update', 'Proses\DispensasiController@saveEdit')->name('dispensasi.update');
});
