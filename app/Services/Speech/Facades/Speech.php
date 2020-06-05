<?php

namespace App\Services\Speech\Facades;

use App\Services\Speech\SpeechService;
use Illuminate\Support\Facades\Facade;

class Speech extends Facade
{
    protected static function getFacadeAccessor()
    {
        return app(SpeechService::class);
    }
}