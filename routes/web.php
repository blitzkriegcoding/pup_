<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::get('/', 'HomeController@index');

Route::group(['prefix' => 'admin' , 'middleware' => ['role:admin']], function(){
	Route::get('new_enterprise',['as' => 'admin.new_enterprise', 'uses' => 'EnterpriseController@newEnterprise']);
	Route::get('edit_enterprise', ['as' => 'admin.edit_enterprise', 'uses' => 'EnterpriseController@editEnterprise']);
	Route::post('create_enterprise', ['as' => 'admin.create_enterprise', 'uses' => 'EnterpriseController@createEnterprise']);

});
