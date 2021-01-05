<?php

declare(strict_types=1);

namespace Quote\Shared\Infrastructure\MessageBroker\RabbitMq;

use AMQPChannel;
use AMQPConnection;
use AMQPExchange;
use AMQPQueue;

final class RabbitMqConnection
{
    /**
     * @var AMQPConnection|null
     */
    private static $connection = null;
    /**
     * @var AMQPChannel|null
     */
    private static $channel    = null;
    /**
     * @var AMQPExchange[]
     */
    private static $exchanges = [];
    /**
     * @var AMQPQueue[]
     */
    private static $queues = [];
    /**
     * @var array
     */
    private $configuration;

    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }

    public function queue(string $name): AMQPQueue
    {
        if (!array_key_exists($name, self::$queues)) {
            $queue = new AMQPQueue($this->channel());
            $queue->setName($name);

            self::$queues[$name] = $queue;
        }

        return self::$queues[$name];
    }

    public function exchange(string $name): AMQPExchange
    {
        if (!array_key_exists($name, self::$exchanges)) {
            $exchange = new AMQPExchange($this->channel());
            $exchange->setName($name);

            self::$exchanges[$name] = $exchange;
        }

        return self::$exchanges[$name];
    }

    private function channel(): AMQPChannel
    {
        return self::$channel = self::$channel && self::$channel->isConnected()
            ? self::$channel
            : new AMQPChannel($this->connection());
    }

    private function connection(): AMQPConnection
    {
        self::$connection = self::$connection ?: new AMQPConnection($this->configuration);

        if (!self::$connection->isConnected()) {
            self::$connection->pconnect();
        }

        return self::$connection;
    }

    public function isConnected(): bool
    {
        return $this->connection()->isConnected();
    }
}
