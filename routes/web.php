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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix'=>'api'],function(){

    Route::group(['prefix'=>'vehicles'],function(){
        Route::put('update','VehiclesController@update');
        Route::put('update/bulk','VehiclesController@bulkUpdate');
    });

    Route::get('vehicles', 'VehiclesController@index');

});