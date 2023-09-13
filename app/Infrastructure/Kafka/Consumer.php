<?php

namespace App\Infrastructure\Kafka;

use Illuminate\Support\Collection;
use Junges\Kafka\Contracts\KafkaConsumerMessage;
use Junges\Kafka\Facades\Kafka;

class Consumer
{
    public static function consume($topic)
    {
        $consumer = Kafka::createConsumer()->subscribe($topic)
            ->withHandler(function (KafkaConsumerMessage $message) {
                echo "Message: " . $message->getBody() . PHP_EOL;
            })
            ->build();
        $consumer->consume();
    }
}
