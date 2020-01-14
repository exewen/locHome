<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class Amqp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'amqp:receive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //$obj = new \App\Http\Controllers\Amqp\SimpleController();
        //$obj = new \App\Http\Controllers\Amqp\WorkerController();
        //$obj = new \App\Http\Controllers\Amqp\SubscribeController();
        $obj = new \App\Http\Controllers\Amqp\RouteController();
        $obj->receive();
    }
}
