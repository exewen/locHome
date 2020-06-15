<?php
namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;

class ElasticSearchClient extends Facade {

    protected static function getFacadeAccessor()
    {
        return 'App\Contracts\ElasticSearchClient';
    }
}