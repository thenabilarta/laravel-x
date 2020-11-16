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

Route::get('/', 'XiboController@index');
Route::get('/media', 'XiboController@media');
Route::get('/image', 'XiboController@image');
Route::post('/edit/store', 'XiboController@editstore');
Route::get('/edit/{id}', 'XiboController@edit');
Route::get('/delete/{id}', 'XiboController@delete');
Route::post('/store', 'XiboController@store');

Route::get('upload', 'ImageUploadController@upload');
Route::post('upload/store', 'ImageUploadController@store');
Route::post('delete', 'ImageUploadController@delete');