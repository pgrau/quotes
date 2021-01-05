<?php

declare(strict_types=1);

namespace Quote\Shared\Infrastructure\MessageBroker\RabbitMq;

final class RabbitMqExchangeNameFormatter
{
    public static function retry(string $exchangeName): string
    {
        return "retry-$exchangeName";
    }
}
