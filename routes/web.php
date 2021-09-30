<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['namespace' => 'Record'], function () {
    Route::get('/records', 'RecordController@index')->name('records.index');

    Route::get('/records/import', 'ImportController@index')->name('records.import.index');
    Route::post('/records/import', 'ImportController@store')->name('records.import.store');
});
