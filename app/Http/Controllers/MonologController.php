<?php

namespace App\Http\Controllers;

use App\Services\MonologService;
use Monolog\Formatter\LineFormatter;
use PatPat\Monolog\Logger\CLogger;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Illuminate\Support\Facades\Config;

class MonologController extends Controller
{
    public $monologService;

    public function __construct(MonologService $monologService)
    {
        $this->monologService = $monologService;
    }


    public function monologTest()
    {
        // 1
        MonologService::getLogger('model-controller')->error([1=> '错误']);

        //2 patpat
        dd(storage_path() );
        $log = CLogger::getLogger('m-c', 'dir');
        $res = $log->info('[12, 234]');
        dd($res);

        //3
        // create a log channel
//        $log = new Logger('name');
//        $log->pushHandler(new StreamHandler(storage_path() . '/logs/' . 'your.log'));
//        // add records to the log
//        $log->warning('Foo1');
//        // add records to the log
//        $res = $log->info('Foo2');
//        dd($res);
    }

}