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

  Route::get('hari_create/{$id_jadwal}', 'Referensi\HariController@create')->name('hari_create');
});
