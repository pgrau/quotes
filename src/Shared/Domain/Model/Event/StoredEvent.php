<?php

declare(strict_types=1);

namespace Quote\Shared\Domain\Model\Event;

class StoredEvent extends DomainEvent
{
    const EVENT_NAME  = 'stored.event';

    /** @var int */
    protected $version;

    /** @var array */
    protected $payload;

    /** @var string */
    protected $eventName;

    public function version(): int
    {
        return $this->version;
    }

    public function payload(): array
    {
        return $this->payload;
    }

    public function eventName(): string
    {
        return $this->eventName;
    }

    public static function fromEventStore(
        string $messageId,
        string $name,
        array $body,
        int $priority,
        \DateTimeInterface $occurredOn,
        ?int $aggregateId,
        int $version
    ): self {
        $storedEvent = new self($aggregateId, $messageId, $occurredOn);
        $storedEvent->priority  = $priority;
        $storedEvent->eventName = $name;
        $storedEvent->payload   = $body;
        $storedEvent->version   = $version;

        return $storedEvent;
    }

    public static function fromMessageBroker(
        string $messageId,
        string $name,
        array $body,
        int $priority,
        \DateTimeInterface $occurredOn,
        array $headers
    ): self {
        $aggregateId = $headers['aggregate_id'] ?? null;
        $version = $headers['version'] ?? null;

        $storedEvent = new self($aggregateId, $messageId, $occurredOn);
        $storedEvent->priority  = $priority;
        $storedEvent->eventName = $name;
        $storedEvent->payload   = $body;
        $storedEvent->version   = $version;

        return $storedEvent;
    }
}
