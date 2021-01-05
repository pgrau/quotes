<?php

declare(strict_types=1);

namespace Quote\Shared\Domain\Model\Event;

interface DomainEventSubscriber
{
    public static function subscribedTo(): array;

    public function handle(DomainEvent $domainEvent): void;
}
