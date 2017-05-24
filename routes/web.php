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

});
