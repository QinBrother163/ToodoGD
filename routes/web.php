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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//管理员接口
Route::get('/components/Example','\App\Http\Controllers\Toodo\ToodoController@order');

Route::get('/components/mgpayrecord','\App\Http\Controllers\Toodo\mgpayrecordController@order');

Route::get('/components/gd_order','\App\Http\Controllers\Toodo\GdOrderController@order');