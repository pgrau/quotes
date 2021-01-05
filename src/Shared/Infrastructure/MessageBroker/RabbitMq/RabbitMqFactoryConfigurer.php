<?php

declare(strict_types=1);

namespace Quote\Shared\Infrastructure\MessageBroker\RabbitMq;

class RabbitMqFactoryConfigurer
{
    private const DEFAULT_RETRY_MESSAGE_TTL = 1000 * 60 * 5;

    public static function create(
        RabbitMqConnection $connection,
        string $queuesAsString,
        int $ttl = self::DEFAULT_RETRY_MESSAGE_TTL
    ): RabbitMqConfigurer {
        $queues = explode('@', $queuesAsString);
        $configurer = new RabbitMqConfigurer($connection);
        $configurer->setRetryMessageTtl($ttl);
        $configurer->addQueue(...$queues);

        return $configurer;
    }
}
