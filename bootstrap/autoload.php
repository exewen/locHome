<?php

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Register The Composer Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader
| for our application. We just need to utilize it! We'll require it
| into the script here so that we do not have to worry about the
| loading of any our classes "manually". Feels great to relax.
|
*/
//这就是传说中Composer的自动加载文件
require __DIR__.'/../vendor/autoload.php';
//PSR0加载方式—对应的文件就是autoload_namespaces.php
//PSR4加载方式—对应的文件就是autoload_psr4.php
//其他加载类的方式—对应的文件就是autoload_classmap.php
//加载公用方法—对应的文件就是autoload_files.php
/*
|--------------------------------------------------------------------------
| Include The Compiled Class File
|--------------------------------------------------------------------------
|
| To dramatically increase your application's performance, you may use a
| compiled class file which contains all of the classes commonly used
| by a request. The Artisan "optimize" is used to create this file.
|
*/

$compiledPath = __DIR__.'/cache/compiled.php';

if (file_exists($compiledPath)) {
    require $compiledPath;
}
