<?php

namespace App\Contracts\Foundation;

use Monolog\Handler\AbstractProcessingHandler;
use App\Services\Facades\ElasticSearchClient;

class ElasticSearchLogHandler extends AbstractProcessingHandler
{
    protected function write(array $record)
    {
        if ($record['level'] >= 200) {
            //$action = \Route::currentRouteAction();
            ElasticSearchClient::addDocument($record);
        }
    }
}