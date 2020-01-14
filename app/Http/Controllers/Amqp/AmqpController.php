<?php

namespace App\Http\Controllers\Amqp;

use App\Http\Controllers\Controller;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class AmqpController extends Controller
{
    private $queueName = 'task_queue8';
    private $exchange = 'router';

    public function setMq()
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $exchange = $this->exchange; // 交换器，在我理解，如果两个队列使用一个交换器就代表着两个队列是同步的，这个队列里存在的消息，在另一个队列里也会存在
        $queue = $this->queueName;// 队列名称
        $channel->queue_declare($queue, false, true, false, false);
        //$channel->exchange_declare($exchange, 'direct', false, true, false);
        //$channel->queue_bind($queue, $exchange); // 队列和交换器绑定

        if (empty($data)) $data = 'hello world ' . $queue;// 要推送的消息
        $msg = new AMQPMessage($data, [
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_NON_PERSISTENT
        ]);

       // $channel->basic_publish($msg, $exchange, $queue); // 推送消息
        $channel->basic_publish($msg, '', $queue); // 推送消息
        echo " [x] sent " . $data . "\n";

        $channel->close();
        $connection->close();
    }

    public function getMq()
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $exchange = $this->exchange;
        $queue = $this->queueName;// 队列名称
        $channel = $connection->channel();

        $channel->queue_declare($queue, false, true, false, false);
        $callback2 = function ($msg) {
            echo " [x] recived " . $msg->body . "\n";
            sleep(substr_count($msg->body, '.'));
            echo " [x] done " . "\n";
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        };
        $callback= function () {
            echo " [x] wait " . "\n";
        };

        $channel->basic_qos(null, 1, null);
        // $channel->basic_consume($queue, false, false, false, false, $callback);// 消费者
        $channel->basic_consume($queue, '', false, true, false, false, $callback);
        while (count($channel->callbacks)) {
            $channel->wait();
            echo " [x] wait " . "\n";
        }

//        $message = $channel->basic_get($queue); //取出消息
//        if (isset($message->body)) {
//            print_r($message->body);
//           // $channel->basic_ack($message->delivery_info['delivery_tag']); // 确认取出消息后会发送一个ack来确认取出来了，然后会从rabbitmq中将这个消息移除，如果删掉这段代码，会发现rabbitmq中的消息还是没有减少
//        } else {
//            print_r("未找到消息：{$queue}");
//        }
        $channel->close();
        $connection->close();
    }

    public function setMq2()
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $exchange = $this->exchange; // 交换器，在我理解，如果两个队列使用一个交换器就代表着两个队列是同步的，这个队列里存在的消息，在另一个队列里也会存在
        $queue = $this->queueName;// 队列名称

        $channel->queue_declare($queue, false, true, false, false);
        $channel->exchange_declare($exchange, 'direct', false, true, false);
        $channel->queue_bind($queue, $exchange); // 队列和交换器绑定

        if (empty($data)) $data = 'hello world ' . $queue;// 要推送的消息
        $msg = new AMQPMessage($data, [
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_NON_PERSISTENT
        ]);

        $channel->basic_publish($msg, $exchange, $queue); // 推送消息
        echo " [x] sent " . $data . "\n";

        $channel->close();
        $connection->close();
    }

    public function getMq2()
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $exchange = $this->exchange;
        $queue = $this->queueName;// 队列名称
        $channel = $connection->channel();
        $message = $channel->basic_get($queue); //取出消息
        if (isset($message->body)) {
            print_r($message->body);
            $channel->basic_ack($message->delivery_info['delivery_tag']); // 确认取出消息后会发送一个ack来确认取出来了，然后会从rabbitmq中将这个消息移除，如果删掉这段代码，会发现rabbitmq中的消息还是没有减少
        } else {
            print_r("未找到消息：{$queue}");
        }
        $channel->close();
        $connection->close();
    }
}
