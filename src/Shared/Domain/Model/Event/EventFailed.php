<?php

declare(strict_types=1);

namespace Quote\Shared\Domain\Model\Event;

final class EventFailed
{
    /** @var string */
    private $eventId;

    /** @var string */
    private $name;

    /** @var int */
    private $retry;

    /** @var string */
    private $lastError;

    /** @var \DateTimeImmutable */
    private $createdAt;

    public function __construct(
        string $eventId,
        string $name,
        int $retry,
        array $lastError,
        ?\DateTimeImmutable $createdAt = null
    ) {
        $this->eventId = $eventId;
        $this->name = $name;
        $this->retry = $retry;
        $this->lastError = $lastError;
        $this->createdAt = $createdAt ?: new \DateTimeImmutable();
    }

    public function eventId(): string
    {
        return $this->eventId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function retry(): int
    {
        return $this->retry;
    }

    public function lastError(): array
    {
        return $this->lastError;
    }

    public function jsonLastError(): string
    {
        return (string) \json_encode($this->lastError);
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public static function fromEventStore(
        string $eventId,
        string $name,
        int $retry,
        array $lastError,
        \DateTimeImmutable $date
    ): self {

        return new self(
            $eventId,
            $name,
            $retry,
            $lastError,
            $date
        );
    }
}
