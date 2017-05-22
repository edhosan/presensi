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


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:api'], function() {
  Route::post('pegawai', 'Auth\RegisterController@apiGetPegawai');
  Route::post('unker', 'Auth\RegisterController@apiGetUnker');
  Route::get('dt_user', 'Auth\RegisterController@apiUser');
});
