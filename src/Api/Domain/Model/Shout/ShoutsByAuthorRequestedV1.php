<?php

declare(strict_types=1);

namespace Quote\Api\Domain\Model\Shout;

use Quote\Shared\Domain\Model\Event\DomainEvent;
use Quote\Shared\Domain\Model\Event\PublishableDomainEvent;

class ShoutsByAuthorRequestedV1 extends DomainEvent implements PublishableDomainEvent
{
    const EVENT_NAME  = 'quote.api.shouts.by.author.requested.v1';

    public function __construct(
        private string $shouts,
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
            'shouts' => $this->shouts,
            'author_id' => $this->authorId,
            'limit' => $this->limit,
        ];
    }
}
