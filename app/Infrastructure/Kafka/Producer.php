<?php
namespace App\Infrastructure\Kafka;

use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Producers\MessageBatch;
use Junges\Kafka\Message\Message;


class Producer
{
    public static function produce($topic, $message)
    {
        // $producer = Kafka::publishOn($topic)->withBodyKey('key', $message);
        // $producer->send();
        return "it's working";
    }
}
