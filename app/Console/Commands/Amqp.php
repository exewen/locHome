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
    protected $signature = 'amqp:send';

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
        $queueName = 'task_queue8';

        // Connection： 就是一个TCP的连接
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        // ~ queue 队列的名称。~ durable: 设置是否持久化 ~ exclusive 设置是否排他 ~ autoDelete: 设置是否自动删除
        $channel->queue_declare($queueName, false, true, false, false);

        $callback = function ($msg) {
            echo " [x] recived " . $msg->body . "\n";
            sleep(substr_count($msg->body, '.'));
            echo " [x] done " . "\n";
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        };

        // 公平派遣
        $channel->basic_qos(null, 1, null);
        $channel->basic_consume($queueName, '',false, false, false, false, $callback);

        while (count($channel->callbacks)) {
            $channel->wait();
        }
        $channel->close();
        $connection->close();
    }
}
