<?php

namespace App\Http\Controllers\Amqp;

use PhpAmqpLib\Message\AMQPMessage;

class SubscribeController extends Controller
{
    private $queueName = 'subscriber_4';// 队列名称
    private $exchange = 'log';// 交换机

    /**
     * 发布、订阅模式 fanout把消息投放到所有附加在此交换器上的队列
     */
    public function send()
    {
        $channel = $this->connection->channel();
        // fanout 订阅  direct 完全匹配 topic模糊匹配
        $channel->exchange_declare($this->exchange, 'fanout', false, true, false);
        $data = "info: Hello World!";
        $msg = new AMQPMessage($data);
        $channel->basic_publish($msg, $this->exchange);
        echo " [{$this->exchange}] Sent ", $data, "\n";
        $channel->close();
    }

    /**
     * 接收
     * @throws \ErrorException
     */
    public function receive()
    {
        $channel = $this->connection->channel();
        // 交换机声明
        $channel->exchange_declare($this->exchange, 'fanout', false, true, false);
        // 当我们以空字符串形式提供队列名称时，我们将使用生成的名称创建一个非持久队列：
        // $ queue_name变量包含RabbitMQ生成的随机队列名称。例如，它可能看起来像amq.gen-JzTY20BRgKO-HjmUJj0wLg。
        list($queue_name, ,) = $channel->queue_declare("", false, false, true, false);
        // 和交换机进行绑定
        $channel->queue_bind($queue_name, $this->exchange);
        echo " [{$this->exchange}] Waiting for logs. To exit press CTRL+C\n";
        $callback = function ($msg) use ($queue_name) {
            echo " [{$queue_name}] ", $msg->body, "\n";
        };
        // 消息是从服务器异步发送到客户端的
        $channel->basic_consume($queue_name, '', false, false, false, false, $callback);
        while ($channel->is_consuming()) {
            $channel->wait();
        }
        $channel->close();
    }
}
