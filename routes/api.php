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
  Route::post('pegawai', 'Auth\RegisterController@apiGetPegawai');
  Route::post('unker', 'Auth\RegisterController@apiGetUnker');
  Route::get('dt_user', 'Auth\RegisterController@apiUser');
  Route::post('delete_user', 'Auth\RegisterController@apiDeleteUser');

  Route::get('role_list', 'Auth\RoleController@apiGetRole');
  Route::post('role_delete', 'Auth\RoleController@apiDeleteRole');

  Route::get('permission_list', 'Auth\PermissionController@apiGetPermission');
  Route::post('permission_delete', 'Auth\PermissionController@apiDeletePermission');

  Route::get('get_idfinger', 'Proses\DataIndukController@apiGetId');
  Route::post('subunit', 'Proses\DataIndukController@apiGetSubUnit');
  Route::post('jabatan', 'Proses\DataIndukController@apiGetJabatan');
  Route::post('pangkat', 'Proses\DataIndukController@apiGetPangkat');
  Route::get('datainduk_list', 'Proses\DataIndukController@apiGetDataInduk');
  Route::post('datainduk_delete', 'Proses\DataIndukController@apiDeleteDataInduk');

  Route::get('kalendar_list', 'Referensi\EventController@apiEvent');

  Route::get('jadwal_list', 'Referensi\JadwalController@apiJadwalList');

});
