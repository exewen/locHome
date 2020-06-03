<?php

namespace App\Services;


use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Illuminate\Log\Writer;


class MonologService
{
    private static $loggers = array();

    const LOG_ERROR = 'default';

    // 获取一个实例
    public static function getLogger($type, $day = 30)
    {
        if (empty(self::$loggers[$type])) {
            self::$loggers[$type] = new Writer(new Logger($type));
            //new Logger($channel)//一般channel用模块和控制器文件名命名

            self::$loggers[$type]->useDailyFiles(storage_path() . '/logs/' . $type . '.log', $day);
        }
        $log = self::$loggers[$type];
        return $log;
    }

    /**
     * 做错误纪录，可以在任何地方调用这个纪录错误信息
     * @param Exception $exception 异常
     * @return null
     */
    public static function logError($exception, $type = self::LOG_ERROR)
    {
        $logger = self::getLogger($type);
        $err = [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'code' => $exception->getCode(),
            'url' => \Request::url(),
            'input' => \Input::all(),
        ];
        MonologService::getLogger("error")->error($err);
    }

}