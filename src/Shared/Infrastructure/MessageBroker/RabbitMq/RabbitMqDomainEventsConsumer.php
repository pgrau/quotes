<?php

declare(strict_types=1);

namespace Quote\Shared\Infrastructure\MessageBroker\RabbitMq;

use AMQPEnvelope;
use AMQPQueue;
use Quote\Shared\Domain\Model\Event\DomainEvent;
use Quote\Shared\Domain\Model\Event\EventFailed;
use Quote\Shared\Domain\Model\Event\EventFailedStore;
use Quote\Shared\Domain\Model\Event\StoredEvent;
use Quote\Shared\Infrastructure\MessageBroker\Dispatcher;
use Seld\Signal\SignalHandler;
use Throwable;
use function Lambdish\Phunctional\assoc;
use function Lambdish\Phunctional\get;

final class RabbitMqDomainEventsConsumer
{
    const STOP_CONSUMER_BY_MESSAGE_BODY = 'quit';

    public function __construct(
        private RabbitMqConnection $connection,
        private string $exchangeName,
        private int $maxRetries,
        private EventFailedStore $eventFailedStore
    ) {}

    public function consume(Dispatcher $dispatcher, string $queueName)
    {
        $queue         = $this->getQueue($queueName);
        $signalHandler = $this->getSignalHandler();

        $queue->consume($this->consumer($dispatcher, $signalHandler));
    }

    public function get(Dispatcher $dispatcher, string $queueName)
    {
        $queue         = $this->getQueue($queueName);
        $signalHandler = $this->getSignalHandler();

        while ($envelope = $queue->get()) {
            $this->handleMessage($envelope, $queue, $dispatcher, $signalHandler);
        }

        return false;
    }

    private function consumer(Dispatcher $dispatcher, SignalHandler $signalHandler): callable
    {
        return function (AMQPEnvelope $envelope, AMQPQueue $queue) use ($dispatcher, $signalHandler) {

            if ($envelope->getBody() === self::STOP_CONSUMER_BY_MESSAGE_BODY) {
                $queue->ack($envelope->getDeliveryTag());
                return false;
            }

            if (! $this->handleMessage($envelope, $queue, $dispatcher, $signalHandler)) {
                return false;
            }
        };
    }

    private function handleMessage(
        AMQPEnvelope $envelope,
        AMQPQueue $queue,
        Dispatcher $dispatcher,
        SignalHandler $signalHandler
    ): bool {
        if ($signalHandler->isTriggered()) {
            return false;
        }

        if ($envelope->getBody() === self::STOP_CONSUMER_BY_MESSAGE_BODY) {
            $queue->ack($envelope->getDeliveryTag());
            return false;
        }

        $event = StoredEvent::fromMessageBroker(
            $envelope->getMessageId(),
            $envelope->getType(),
            \json_decode($envelope->getBody(), true),
            $envelope->getPriority(),
            (new \DateTime())->setTimestamp($envelope->getTimestamp()),
            $envelope->getHeaders()
        );

        try {
            $dispatcher->dispatch($event);
            $queue->ack($envelope->getDeliveryTag());
        } catch (Throwable $error) {
            $this->handleConsumptionError($envelope, $queue, $error);
        }

        return true;
    }

    private function getQueue(string $queueName): AMQPQueue
    {
        $queue = $this->connection->queue($queueName);
        $queue->setArgument('x-max-priority', DomainEvent::PRIORITY_HIGHEST);

        return $queue;
    }

    private function getSignalHandler(): SignalHandler
    {
        return SignalHandler::create([SIGINT, SIGTERM, SIGQUIT], function ($signal, $signalName) {
            echo sprintf('Received %s (%s). Gracefully stopping...', $signalName, $signal) . PHP_EOL;
        });
    }

    private function handleConsumptionError(AMQPEnvelope $envelope, AMQPQueue $queue, Throwable $error): void
    {
        $tooMuchRetries = $this->hasBeenRedeliveredTooMuch($envelope);

        $tooMuchRetries
            ? $this->sendToDeadLetter($envelope, $error)
            : $this->sendToRetry($envelope, $queue);

        $queue->ack($envelope->getDeliveryTag());
    }

    private function hasBeenRedeliveredTooMuch(AMQPEnvelope $envelope): bool
    {
        return get('redelivery_count', $envelope->getHeaders(), 0) >= $this->maxRetries;
    }

    private function sendToDeadLetter(AMQPEnvelope $envelope, Throwable $error): void
    {
        $headers = $envelope->getHeaders();
        $retry = 0;
        if (isset($headers['redelivery_count'])) {
            $retry = (int) $headers['redelivery_count'];
        }

        $lastError = [
          'message' => $error->getMessage(),
          'file' => $error->getFile(),
          'line' => $error->getLine(),
          'trace' => $error->getTraceAsString(),
        ];

        $eventFailed = new EventFailed(
            $envelope->getMessageId(),
            $envelope->getType(),
            $retry,
            $lastError
        );

        $this->eventFailedStore->persist($eventFailed);
    }

    private function sendToRetry(AMQPEnvelope $envelope, AMQPQueue $queue): void
    {
        $this->sendMessageTo(RabbitMqExchangeNameFormatter::retry($this->exchangeName), $envelope, $queue);
    }

    private function sendMessageTo(string $exchangeName, AMQPEnvelope $envelope, AMQPQueue $queue): void
    {
        $headers = $envelope->getHeaders();

        $this->connection->exchange($exchangeName)->publish(
            $envelope->getBody(),
            $queue->getName(),
            AMQP_NOPARAM,
            [
                'type'             => $envelope->getType(),
                'message_id'       => $envelope->getMessageId(),
                'content_type'     => $envelope->getContentType(),
                'content_encoding' => $envelope->getContentEncoding(),
                'priority'         => $envelope->getPriority(),
                'headers'          => assoc($headers, 'redelivery_count', get('redelivery_count', $headers, 0) + 1),
            ]
        );
    }
}
