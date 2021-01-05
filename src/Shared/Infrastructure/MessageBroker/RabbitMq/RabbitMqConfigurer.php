<?php

declare(strict_types=1);

namespace Quote\Shared\Infrastructure\MessageBroker\RabbitMq;

use AMQPQueue;
use Quote\Shared\Domain\Model\Event\DomainEvent;

final class RabbitMqConfigurer
{
    /**
     * @var RabbitMqConnection
     */
    private $connection;
    /**
     * @var array
     */
    private $queues = [];

    /**
     * @var int
     */
    private static $RETRY_MESSAGE_TTL = 1000 * 5;

    public function __construct(RabbitMqConnection $connection)
    {
        $this->connection = $connection;
    }

    public function addQueue(string ...$queues): self
    {
        foreach ($queues as $queue) {
            $this->queues[$queue] = $queue;
        }

        return $this;
    }

    public function setRetryMessageTtl(int $ttl): self
    {
        self::$RETRY_MESSAGE_TTL = $ttl;

        return $this;
    }

    public function configure(string $exchangeName): void
    {
        $retryExchangeName = RabbitMqExchangeNameFormatter::retry($exchangeName);

        $this->declareExchange($exchangeName);
        $this->declareExchange($retryExchangeName);

        $this->declareQueues($exchangeName, $retryExchangeName);
    }

    private function declareExchange(string $exchangeName): void
    {
        $exchange = $this->connection->exchange($exchangeName);
        $exchange->setType(AMQP_EX_TYPE_FANOUT);
        $exchange->setFlags(AMQP_DURABLE);
        $exchange->declareExchange();
    }

    private function declareQueues(
        string $exchangeName,
        string $retryExchangeName
    ): void {

        foreach ($this->queues as $queueName) {
            $retryQueueName  = RabbitMqExchangeNameFormatter::retry($queueName);
            $queue           = $this->declareQueue($queueName, true);
            $retryQueue      = $this->declareQueue(
                $retryQueueName,
                false,
                $exchangeName,
                $queueName,
                self::$RETRY_MESSAGE_TTL
            );

            $queue->bind($exchangeName, $exchangeName);
            $retryQueue->bind($retryExchangeName, $retryExchangeName);
        }
    }

    private function declareQueue(
        string $name,
        bool $priority = false,
        string $deadLetterExchange = null,
        string $deadLetterRoutingKey = null,
        int $messageTtl = null
    ): AMQPQueue {
        $queue = $this->connection->queue($name);

        if (null !== $deadLetterExchange) {
            $queue->setArgument('x-dead-letter-exchange', $deadLetterExchange);
        }

        if (null !== $deadLetterRoutingKey) {
            $queue->setArgument('x-dead-letter-routing-key', $deadLetterRoutingKey);
        }

        if (null !== $messageTtl) {
            $queue->setArgument('x-message-ttl', $messageTtl);
        }

        if ($priority) {
            $queue->setArgument('x-max-priority', DomainEvent::PRIORITY_HIGHEST);
        }

        $queue->setFlags(AMQP_DURABLE);
        $queue->declareQueue();

        return $queue;
    }
}
