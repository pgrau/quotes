<?php

declare(strict_types=1);

namespace Quote\Api\Domain\Model\Quote;

use Quote\Shared\Domain\Model\Event\StoredEvent;

interface QuoteProjection
{
    public function projectQuoteCreated(QuoteCreatedV1|StoredEvent $domainEvent): void;
}