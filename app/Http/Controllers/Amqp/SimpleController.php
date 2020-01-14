<?php

namespace App\Http\Controllers\Amqp;

use PhpAmqpLib\Message\AMQPMessage;

class SimpleController extends Controller
{
    private $queueName = 'simple_2';// 队列名称

    /**
     * 简单队列模式
     */
    public function send()
    {
        $channel = $this->connection->channel();

        // 1 声明队列
        $channel->queue_declare($this->queueName, false, false, false, false);

        $msg = new AMQPMessage('Hello World!');
        // 2 发送消息到队列
        $channel->basic_publish($msg, '', $this->queueName);
        echo " [{$this->queueName}] Sent 'Hello World!'\n";
        $channel->close();
    }

    /**
     * 接受
     * @throws \ErrorException
     */
    public function receive()
    {
        $channel = $this->connection->channel();
        //1 打开通道并声明要消耗匹配队列 确保队列在
        $channel->queue_declare($this->queueName, false, false, false, false);
        echo " [*] Waiting for messages. To exit press CTRL+C\n";
        $callback = function ($msg) {
            echo " [{$this->queueName}] Received ", $msg->body, "\n";
        };
        //2 告诉服务器将队列中的消息发送过来，消息是从服务器异步发送到客户端的。
        $channel->basic_consume($this->queueName, '', false, true, false, false, $callback);
        while ($channel->is_consuming()) {
            $channel->wait();
        }
        $channel->close();
    }
}
