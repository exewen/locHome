<?php

namespace App\Http\Middleware;

use Closure;
use App\Jobs\ElasticSearchLog as JElasticSearchLog;
use App\Services\Facades\ElasticSearchClient;

class ElasticSearchLog
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }


    /**
     * 可终止的中间件
     * @param $request
     * @param $response
     */
    public function terminate($request, $response)
    {
        $documents = ElasticSearchClient::getDocuments();
        // 需要判断是否有日志
        if (count($documents) > 0) {
            dispatch(new JElasticSearchLog($documents));
        }
    }
}