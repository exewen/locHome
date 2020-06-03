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

Route::get('/', 'LocController@index');
Route::get('/home/multiPage/{pages}', 'LocController@multiPage')->where('pages', '.*');
Route::get('/test', 'LocController@test');
Route::get('/speech', 'SpeechController@index');
Route::get('/speech/api/{pageKey}', 'SpeechController@api');
Route::get('/user/{name?}', function ($name=null) {
    return $name;
});

Route::auth();
Route::get('/home', 'HomeController@index');

Route::any('/api/business_method','LocController@params');

// MQ test
Route::any('/set_mq','Amqp\AmqpController@setMq');
Route::any('/get_mq','Amqp\AmqpController@getMq');
Route::any('/simple/send','Amqp\SimpleController@send');
Route::any('/worker/send','Amqp\WorkerController@send');
Route::any('/subscribe/send','Amqp\SubscribeController@send');
Route::any('/route/send','Amqp\RouteController@send');
Route::any('/ropic/send','Amqp\RopicController@send');

//demo events
Route::any('/events','TestController@events');
Route::any('/monologTest','MonologController@monologTest');


Route::auth();

Route::get('/home', 'HomeController@index');
