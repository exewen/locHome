<?php

namespace App\Http\Controllers\Amqp;

use PhpAmqpLib\Message\AMQPMessage;

class WorkerController extends Controller
{
    private $queueName = 'worker_3';// 队列名称

    /**
     * 工作队列模式
     */
    public function send()
    {
        $channel = $this->connection->channel();
        // 1 声明队列
        //  标记为持久队列 RabbitMQ不允许您使用不同的参数重新定义现有队列
        $channel->queue_declare($this->queueName, false, true, false, false);

        $data = "Hello World!";
        $msg = new AMQPMessage($data, [
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT // 将消息标记为持久消息
        ]);
        // 2 发送消息到队列
        $channel->basic_publish($msg, '', $this->queueName);
        echo " [{$this->queueName}] Sent ", $data, "\n";

        $channel->close();
    }

    /**
     * 接收
     * @throws \ErrorException
     */
    public function receive()
    {
        $channel = $this->connection->channel();
        //1 打开通道并声明要消耗匹配队列 确保队列在
        $channel->queue_declare($this->queueName, false, true, false, false);
        echo " [*] Waiting for messages. To exit press CTRL+C\n";

        $callback = function ($msg) {
            echo " [{$this->queueName}] Received ", $msg->body, "\n";
            sleep(substr_count($msg->body, '.'));
            echo " [{$this->queueName}] Done\n";
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        };
        //2 告诉服务器将队列中的消息发送过来，消息是从服务器异步发送到客户端的。
        // 2.1公平派遣
        $channel->basic_qos(null, 1, null);
        // 2.2消息确认默认为关闭。现在是时候通过将第四个参数
        $channel->basic_consume($this->queueName, '', false, false, false, false, $callback);
        while ($channel->is_consuming()) {
            $channel->wait();
        }
        $channel->close();
    }
}
