<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Monolog\Formatter\LineFormatter;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use PatPat\Monolog\Processor\IntrospectionProcessor;
use PatPat\Monolog\Processor\ProjectProcessor;
use Route;

class CLoggerService
{

    public function info($msg)
    {
        return $this->getLogger('info')->info($msg);
    }

    public function logger($is_error = false)
    {
        $name = $is_error ? 'error' : 'info';
        return $this->getLogger($name);
    }

    /**
     * 自定义日志
     * @param $name
     * @param null $dir
     * @return Logger
     * @throws \Exception
     */
    public function getLogger($name, $dir = null)
    {
        $module = $class = $method = '';
        $action = Route::currentRouteAction();
        if ($action) {
            list($class, $method) = explode('@', $action);
            preg_match('/^App\\\Modules\\\(\w+?)\\\.*\\\(\w+)@(\w+)$/', $action, $match);
            $module = isset($match[1]) ? $match[1] : '';
            $class = class_basename($class);
            $module && $module .= '/';
            $class && $class .= '/';
            $method && $method .= '/';
        }
        $file_name = $name . '_' . date('Ymd', time()) . '.log';
        $default_log_path = Config::get('diy_log.log_path');
        $log_path = $default_log_path . '/' . ($dir ? ($dir . '/') : $module . $class) . $file_name;
        #processors
        $processors = [
            new IntrospectionProcessor(),
            new ProjectProcessor(),
        ];
        #save local log files
        $streamHandler = new StreamHandler($log_path);
        $streamHandler->setFormatter(new LineFormatter(null, 'Y-m-d H:i:s.u', true, true));
        #handlers
        $handlers = [
            $streamHandler,
        ];
        $logger_name = $name ? $name : env('APP_ENV', 'laravel');
        if ($dir != null) {
            $logger_name = $dir . '_' . $logger_name;
        }
        $logger = new Logger($logger_name, $handlers, $processors);
        return $logger;
    }

}