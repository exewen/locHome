<?php

namespace App\Http\Controllers\Amqp;

use PhpAmqpLib\Message\AMQPMessage;

class RouteController extends Controller
{
    private $exchange = 'direct_logs';// 交换机

    /**
     * 路由模式  路由键完全匹配
     */
    public function send()
    {
        $channel = $this->connection->channel();
        // fanout 订阅  direct 完全匹配 topic模糊匹配
        $channel->exchange_declare($this->exchange, 'direct', false, false, false);

        $severity = 'info';// 绑定键

        $data = "Hello World!";
        $msg = new AMQPMessage($data);
        $channel->basic_publish($msg, $this->exchange, $severity);
        echo " [{$this->exchange}] Sent ", $severity, ':', $data, "\n";
        $channel->close();
    }

    /**
     * 接收
     * @throws \ErrorException
     */
    public function receive()
    {
        $channel = $this->connection->channel();

        $channel->exchange_declare($this->exchange, 'direct', false, false, false);
        list($queue_name, ,) = $channel->queue_declare("", false, false, true, false);
        $severities = ['info'];
        foreach ($severities as $severity) {
            $channel->queue_bind($queue_name, $this->exchange, $severity);
        }
        echo " [*] Waiting for logs. To exit press CTRL+C\n";
        $callback = function ($msg) {
            echo ' [x] ', " binding_key:", $msg->delivery_info['routing_key'], ':', $msg->body, "\n";
        };
        // 消息是从服务器异步发送到客户端的
        $channel->basic_consume($queue_name, '', false, true, false, false, $callback);
        while ($channel->is_consuming()) {
            $channel->wait();
        }

        $channel->close();
    }
}
