<?php

declare(strict_types=1);

namespace Quote\Api\Domain\Model\Author;

use Quote\Shared\Domain\Model\Event\StoredEvent;

interface AuthorProjection
{
    public function projectAuthorCreated(AuthorCreatedV1|StoredEvent $domainEvent): void;
}