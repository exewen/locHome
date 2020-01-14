<?php

return [
    'driver'        => 'rabbitmq',
    'host'          => env('MQ_HOST', 'localhost'),
    'port'          => env('MQ_PORT', 5672),  // 5672
    'user'          => env('MQ_USERNAME', 'guest'),
    'password'      => env('MQ_PASSWORD', 'guest'),
];
