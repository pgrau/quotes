<?php

declare(strict_types=1);

namespace Quote\Api\Domain\Event\Author;

use Quote\Api\Domain\Model\Author\AuthorCreatedV1;
use Quote\Api\Domain\Model\Author\AuthorProjection;
use Quote\Shared\Domain\Model\Event\DomainEvent;
use Quote\Shared\Domain\Model\Event\DomainEventSubscriber;

class CreateAuthorSubscriber implements DomainEventSubscriber
{
    public function __construct(private AuthorProjection $authorProjection)
    {
    }

    public static function subscribedTo(): array
    {
        return [AuthorCreatedV1::class];
    }

    public function handle(DomainEvent $domainEvent): void
    {
        $this->authorProjection->projectAuthorCreated($domainEvent);
    }
}
