<?php

declare(strict_types=1);

namespace Quote\Api\Domain\Model\Quote;

use Quote\Shared\Domain\Model\Event\DomainEvent;
use Quote\Shared\Domain\Model\Event\PublishableDomainEvent;

class QuoteCreatedV1 extends DomainEvent implements PublishableDomainEvent
{
    const EVENT_NAME  = 'quote.api.quote.created.v1';

    public function __construct(
        string $aggregateId,
        private string $quote,
        private string $authorId,
        ?string $eventId = null,
        ?\DateTimeInterface $occurredOn = null
    ) {
        parent::__construct($aggregateId, $eventId, $occurredOn);
    }

    public function version(): int
    {
        return 1;
    }

    public function payload(): array
    {
        return [
            'id' => $this->aggregateId(),
            'author_id' => $this->authorId,
            'quote' => $this->quote,
        ];
    }
}
