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

use App\Jobs\ElasticSearchLog;
use App\Services\Facades\ElasticSearchClient;

Route::get('/', 'LocController@index');
Route::get('/home/multiPage/{pages}', 'LocController@multiPage')->where('pages', '.*');
Route::get('/test', 'LocController@test');
Route::get('/speech', 'SpeechController@index');
Route::get('/speech/api/{pageKey}', 'SpeechController@api');
Route::get('/user/{name?}', function ($name = null) {
    return $name;
});

Route::auth();
Route::get('/home', 'HomeController@index');

Route::any('/api/business_method', 'LocController@params');

// MQ test
Route::any('/set_mq', 'Amqp\AmqpController@setMq');
Route::any('/get_mq', 'Amqp\AmqpController@getMq');
Route::any('/simple/send', 'Amqp\SimpleController@send');
Route::any('/worker/send', 'Amqp\WorkerController@send');
Route::any('/subscribe/send', 'Amqp\SubscribeController@send');
Route::any('/route/send', 'Amqp\RouteController@send');
Route::any('/ropic/send', 'Amqp\RopicController@send');

//demo events
Route::any('/events', 'TestController@events');
Route::any('/monologTest', 'MonologController@monologTest');



Route::auth();

Route::any('/post_log', 'ApiTestController@post');
Route::get('/get_log', 'ApiTestController@get');

Route::get('/home', 'HomeController@index');

Route::any('/test/log', 'EsController@Log');
//
//Route::get('/test/log', function () {
//    // 日志同时写入 文件系统 和 ElasticSearch 系统
//    Log::info('写入成功啦3，日志同时写入 文件系统 和 ElasticSearch 系统', ['code' => 0, 'msg' => '成功了，日志同时写入 文件系统 和 ElasticSearch 系统', 'data' => [1,2,3,4,5]]);
////    $documents = ElasticSearchClient::getDocuments();
////    // 需要判断是否有日志
////    if (count($documents) > 0) {
////        dispatch(new ElasticSearchLog($documents));
////    }
//});

Route::any('/label_test_down', function () {
    $url = 'https://patpat-wms.s3-us-west-2.amazonaws.com/wms_label/2020-07-20/yun-YT2020221266138414.pdf';
    $pdfData = file_get_contents($url);
    return $pdfData;
});

Route::get('/test/es', function () {
    $hosts = [
        env('ELASTIC_HOST'),
    ];
    $client = \Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
    try {
        $response = $client->info();
        return $response;
    } catch (\Exception $e) {
        return 'error: ' . $e->getMessage();
    }
});

Route::get('/test/transaction', function () {

    try {
        DB::beginTransaction();
        DB::table('test')->insert(['name' => 'transaction']);
        //$job = (new App\Jobs\Transaction())->onQueue('transaction');
        $job = (new App\Jobs\Transaction());
        dispatch($job);
        DB::commit();
        return 'ok';
    } catch (\Exception $e) {
        DB::rollBack();
        $msg = $e->getMessage();
        Log::error($msg);
        return 'error: ' . $msg;
    }
});
