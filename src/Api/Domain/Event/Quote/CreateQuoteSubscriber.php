<?php

declare(strict_types=1);

namespace Quote\Api\Domain\Event\Quote;

use Quote\Api\Domain\Model\Quote\QuoteCreatedV1;
use Quote\Api\Domain\Model\Quote\QuoteProjection;
use Quote\Shared\Domain\Model\Event\DomainEvent;
use Quote\Shared\Domain\Model\Event\DomainEventSubscriber;

class CreateQuoteSubscriber implements DomainEventSubscriber
{
    public function __construct(private QuoteProjection $quoteProjection)
    {
    }

    public static function subscribedTo(): array
    {
        return [QuoteCreatedV1::class];
    }

    public function handle(DomainEvent $domainEvent): void
    {
        $this->quoteProjection->projectQuoteCreated($domainEvent);
    }
}
