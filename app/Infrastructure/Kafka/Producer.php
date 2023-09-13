<?php

namespace App\Infrastructure\Kafka;

use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;


class Producer
{
    public static function produce($topic, $message)
    {
        $message = new Message(body: $message);
        $producer = Kafka::publishOn($topic)->withMessage($message);
        $producer->send();
        return "it's working";
    }
}
