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

Route::get('/', 'HomeController@index');

Route::get('/home/multiPage/{pages}', 'HomeController@multiPage')->where('pages', '.*');;

Route::get('/test', 'HomeController@test');

Route::get('/speech', 'SpeechController@index');

Route::get('/speech/api/{pageKey}', 'SpeechController@api');
