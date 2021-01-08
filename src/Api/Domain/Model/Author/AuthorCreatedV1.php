<?php

declare(strict_types=1);

namespace Quote\Api\Domain\Model\Author;

use Quote\Shared\Domain\Model\Event\DomainEvent;
use Quote\Shared\Domain\Model\Event\PublishableDomainEvent;

class AuthorCreatedV1 extends DomainEvent implements PublishableDomainEvent
{
    const EVENT_NAME  = 'quote.api.author.created.v1';

    public function __construct(
        string $aggregateId,
        private string $fullName,
        ?string $eventId = null,
        ?\DateTimeInterface $occurredOn = null
    ) {
        parent::__construct($aggregateId, $eventId, $occurredOn);
        $this->priority = parent::PRIORITY_LOW;
    }

    public function version(): int
    {
        return 1;
    }

    public function payload(): array
    {
        return [
            'id' => $this->aggregateId(),
            'full_name' => $this->fullName,
        ];
    }
}
