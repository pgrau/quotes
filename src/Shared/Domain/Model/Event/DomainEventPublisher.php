<?php

declare(strict_types=1);

namespace Quote\Shared\Domain\Model\Event;

interface DomainEventPublisher
{
    public function publish(DomainEvent ...$events): int;
}
