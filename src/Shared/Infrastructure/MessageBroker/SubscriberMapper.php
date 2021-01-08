<?php

declare(strict_types=1);

namespace Quote\Shared\Infrastructure\MessageBroker;

use Quote\Api\Domain\Model\Author\AuthorCreatedV1;
use Quote\Api\Domain\Model\Quote\QuoteCreatedV1;
use Quote\Api\Domain\Model\Shout\ShoutsByAuthorRequestedV1;
use Quote\Shared\Domain\Model\Event\DomainEvent;

final class SubscriberMapper
{
    /**
     * Key => name of domain event
     * Value => array of id's of service container
     */
    protected function map(): array
    {
        return [
            AuthorCreatedV1::EVENT_NAME => ['quote.api.subscriber.create.author'],
            QuoteCreatedV1::EVENT_NAME => ['quote.api.subscriber.create.quote'],
            ShoutsByAuthorRequestedV1::EVENT_NAME => ['quote.api.subscriber.get.shouts.by.author'],
        ];
    }

    final public function get(DomainEvent $event): array
    {
        $result = [];
        $subscribers = $this->map();
        if (isset($subscribers[$event->eventName()])) {
            $result = $subscribers[$event->eventName()];
        }

        return $result;
    }
}
