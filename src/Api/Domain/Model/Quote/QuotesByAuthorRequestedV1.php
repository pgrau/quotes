<?php

declare(strict_types=1);

namespace Quote\Api\Domain\Model\Quote;

use Quote\Shared\Domain\Model\Event\DomainEvent;
use Quote\Shared\Domain\Model\Event\PublishableDomainEvent;

class QuotesByAuthorRequestedV1 extends DomainEvent implements PublishableDomainEvent
{
    const EVENT_NAME  = 'quote.api.quotes.by.author.requested.v1';

    public function __construct(
        private string $quotes,
        private string $authorId,
        private int $limit,
        ?string $eventId = null,
        ?\DateTimeInterface $occurredOn = null
    ) {
        parent::__construct('collection', $eventId, $occurredOn);
    }

    public function version(): int
    {
        return 1;
    }

    public function payload(): array
    {
        return [
            'quotes' => $this->quotes,
            'author_id' => $this->authorId,
            'limit' => $this->limit,
        ];
    }
}
