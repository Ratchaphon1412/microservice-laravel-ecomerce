<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Infrastructure\Kafka\Consumer;

class KafkaConsumer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:kafka-consumer';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kafka consumer for laravel topic';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        Consumer::consume("laravel");
    }
}
