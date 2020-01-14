<?php

namespace App\Http\Controllers\Amqp;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    public $connection;

    public function __construct()
    {
        $host = config('rabbit.host');
        $port = config('rabbit.port');
        $user = config('rabbit.user');
        $password = config('rabbit.password');
        $this->connection = new AMQPStreamConnection($host, $port, $user, $password);
    }

    public function __destruct()
    {
        $this->connection->close();
    }
}
