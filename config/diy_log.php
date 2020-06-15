<?php

// 自定义 Monolog 配置
return [
    /**
     * 日志位置,可以自己指定
     */
    'log_path' => env('LOG_PATH',$app->storagePath().'/logs'),

    /**
     * 日志文件名称
     */
    'log_name' => env('LOG_NAME', 'laravel'),

    /**
     * 日志文件最大数
     */
    'log_max_files' => '90',
];
