<?php

declare(strict_types=1);

namespace Quote\Shared\Infrastructure\MessageBroker\RabbitMq;

class RabbitMqFactoryConnection
{
    public static function create(
        string $vhost,
        string $host,
        string $port,
        string $login,
        string $password
    ): RabbitMqConnection {
        $conf = [
            'vhost' => $vhost,
            'host' => $host,
            'port' => $port,
            'login' => $login,
            'password' => $password,
            'write_timeout' => 5,
            'connect_timeout' => 5
        ];

        return new RabbitMqConnection($conf);
    }
}
