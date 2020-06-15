<?php

namespace App\Services\Facades;

use App\Services\CLoggerService;
use Illuminate\Support\Facades\Facade;

class CLogger extends Facade
{
    /**
     * @return string|void
     */
    protected static function getFacadeAccessor()
    {
        return app(CLoggerService::class);
    }
}