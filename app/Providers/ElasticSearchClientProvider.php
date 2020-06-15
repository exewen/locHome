<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use App\Contracts\Foundation\ElasticSearchLogHandler;

class ElasticSearchClientProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     */
    public function boot()
    {
        /**
         * 修改 Laravel 默认的 Log 存储方式为 Elasticsearch。
         */
        {
            $monolog = Log::getMonolog();
            $elasticSearchLogHandler = new ElasticSearchLogHandler();
            // $monolog->popHandler(); // 把默认的文件存储去掉，否则会将日志同时存储到文件和ElasticSearch
            $monolog->pushHandler($elasticSearchLogHandler); // 添加 ElasticSearch 日志存储句柄
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('App\Contracts\ElasticSearchClient', function ($app) {
            return new \App\Contracts\Foundation\ElasticSearchClient();
        });
    }
}