<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'HomeController@index');
Route::get('/home/multiPage/{pages}', 'HomeController@multiPage')->where('pages', '.*');
Route::get('/test', 'HomeController@test');
Route::get('/speech', 'SpeechController@index');
Route::get('/speech/api/{pageKey}', 'SpeechController@api');
Route::get('/user/{name?}', function ($name=null) {
    return $name;
});