<?php

declare(strict_types=1);

namespace Quote\Shared\Domain\Model\Event;

use Ramsey\Uuid\Uuid;

abstract class DomainEvent
{
    const EVENT_NAME  = '';
    const DATE_FORMAT = 'Y-m-d H:i:s.u';

    const PRIORITY_HIGHEST = 10;
    const PRIORITY_HIGH    = 8;
    const PRIORITY_MEDIUM  = 6;
    const PRIORITY_LOW     = 4;
    const PRIORITY_LOWEST  = 1;

    /** @var int */
    protected $priority;

    public function __construct(
        private string $aggregateId,
        private ?string $eventId = null,
        private ?\DateTimeInterface $occurredOn = null)
    {
        $this->eventId     = $eventId ?: Uuid::uuid4()->toString();
        $this->occurredOn  = $occurredOn ?: new \DateTimeImmutable();
        $this->priority    = self::PRIORITY_LOWEST;
    }

    abstract public function version(): int;

    abstract public function payload(): array;

    public function eventName(): string
    {
        return static::EVENT_NAME;
    }

    public function priority(): int
    {
        return $this->priority;
    }

    public function aggregateId(): ?string
    {
        return $this->aggregateId;
    }

    public function eventId(): string
    {
        return $this->eventId;
    }

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }

    public function jsonPayload(): string
    {
        return \json_encode($this->payload());
    }
}
