<?php

declare(strict_types=1);

namespace Quote\Shared\Infrastructure\MessageBroker\RabbitMq;

use Quote\Shared\Domain\Model\Event\DomainEvent;
use Quote\Shared\Domain\Model\Event\DomainEventPublisher;
use Quote\Shared\Domain\Model\Event\EventNotPublished;
use Quote\Shared\Domain\Model\Event\EventNotPublishedStore;
use Quote\Shared\Domain\Model\Event\PublishableDomainEvent;

final class RabbitMqPublisher implements DomainEventPublisher
{
    /** @var RabbitMqConnection */
    private $connection;

    /** @var string */
    private $exchangeName;

    /** @var EventNotPublishedStore */
    private $eventNotPublishedStore;

    public function __construct(
        RabbitMqConnection $connection,
        string $exchangeName,
        EventNotPublishedStore $eventNotPublishedStore
    ) {
        $this->connection             = $connection;
        $this->exchangeName           = $exchangeName;
        $this->eventNotPublishedStore = $eventNotPublishedStore;
    }

    public function publish(DomainEvent ...$events): int
    {
        $eventsPublished = 0;
        foreach ($events as $event) {
            if ($event instanceof PublishableDomainEvent) {
                try {
                    $exchange    = $this->connection->exchange($this->exchangeName);
                    $isPublished = $exchange->publish(
                        $event->jsonPayload(),
                        $event->eventName(),
                        AMQP_NOPARAM,
                        [
                            'headers'          => [
                                'version'      => $event->version(),
                                'aggregate_id' => $event->aggregateId()
                            ],
                            'type'             => $event->eventName(),
                            'priority'         => $event->priority(),
                            'timestamp'        => $event->occurredOn()->getTimestamp(),
                            'message_id'       => $event->eventId(),
                            'content_type'     => 'application/json',
                            'content_encoding' => 'utf-8',
                            'delivery_mode'    =>  AMQP_DURABLE
                        ]
                    );

                    $eventsPublished++;

                    if (!$isPublished) {
                        $eventsPublished--;
                        $eventNotPublished = EventNotPublished::fromDomainEvent($event);
                        $eventNotPublished->update();
                        $this->eventNotPublishedStore->persist($eventNotPublished);
                    }
                } catch (\AMQPException $exception) {
                    $eventNotPublished = EventNotPublished::fromDomainEvent($event);
                    $eventNotPublished->update($exception);
                    $this->eventNotPublishedStore->persist($eventNotPublished);
                }
            }
        }

        return $eventsPublished;
    }
}
