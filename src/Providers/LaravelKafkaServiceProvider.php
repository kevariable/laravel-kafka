<?php

namespace Junges\Kafka\Providers;

use Illuminate\Support\ServiceProvider;
use Junges\Kafka\Contracts\KafkaConsumerMessage;
use Junges\Kafka\Contracts\KafkaProducerMessage;
use Junges\Kafka\Contracts\MessageDecoder;
use Junges\Kafka\Contracts\MessageEncoder;
use Junges\Kafka\Message\ConsumedMessage;
use Junges\Kafka\Message\Decoders\JsonDecoder;
use Junges\Kafka\Message\Encoders\JsonEncoder;
use Junges\Kafka\Message\Message;

class LaravelKafkaServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishesConfiguration();
    }

    public function register()
    {
        $this->app->bind(MessageEncoder::class, function() {
            return new JsonEncoder();
        });

        $this->app->bind(MessageDecoder::class, function() {
            return new JsonDecoder();
        });

        $this->app->bind(KafkaProducerMessage::class, function() {
            return new Message('');
        });

        $this->app->bind(KafkaConsumerMessage::class, ConsumedMessage::class);
    }

    private function publishesConfiguration()
    {
        $this->publishes([
            __DIR__."/../../config/kafka.php" => config_path('kafka.php'),
        ], 'laravel-kafka-config');
    }
}
