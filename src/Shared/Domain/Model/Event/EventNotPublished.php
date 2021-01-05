<?php

declare(strict_types=1);

namespace Quote\Shared\Domain\Model\Event;

use Cassandra\Date;

final class EventNotPublished extends StoredEvent implements PublishableDomainEvent
{
    /** @var array */
    private $lastError;

    /** @var \DateTimeImmutable */
    private $createdAt;

    /** @var \DateTimeImmutable */
    private $updatedAt;

    public static function fromDataSource(
        string $eventId,
        string $name,
        array $body,
        int $priority,
        \DateTimeInterface $date,
        ?int $aggregateId,
        int $version,
        ?array $lastError,
        \DateTimeInterface $createdAt,
        \DateTimeInterface $updatedAt
    ): self {
        $occurredOn = new \DateTimeImmutable($date->format(DomainEvent::DATE_FORMAT));

        $eventNotPublished = new static($aggregateId, $eventId, $occurredOn);

        $eventNotPublished->payload   = $body;
        $eventNotPublished->eventName = $name;
        $eventNotPublished->version   = $version;
        $eventNotPublished->priority  = $priority;
        $eventNotPublished->lastError = $lastError;
        $eventNotPublished->createdAt = $createdAt;
        $eventNotPublished->updatedAt = $updatedAt;

        return $eventNotPublished;
    }

    public static function fromDomainEvent(DomainEvent $event): EventNotPublished
    {
        $eventNotPublished = new static($event->aggregateId(), $event->eventId(), $event->occurredOn());

        $eventNotPublished->payload   = $event->payload();
        $eventNotPublished->eventName = $event->eventName();
        $eventNotPublished->version   = $event->version();
        $eventNotPublished->priority  = $event->priority();
        $eventNotPublished->createdAt = new \DateTimeImmutable();

        return $eventNotPublished;
    }

    public function update(?\Throwable $error = null): self
    {
        $this->lastError = $error
            ? [
                'file'    => $error->getFile(),
                'line'    => $error->getLine(),
                'message' => $error->getMessage()
            ]
            : null;

        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    public function lastError(): ?array
    {
        return $this->lastError;
    }

    public function jsonLastError(): string
    {
        return (string)\json_encode($this->lastError);
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
