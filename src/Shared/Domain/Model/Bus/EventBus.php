<?php

declare(strict_types=1);

namespace Quote\Shared\Domain\Model\Bus;

use Quote\Shared\Domain\Model\Event\DomainEvent;
use Quote\Shared\Domain\Model\Event\DomainEventSubscriber;

interface EventBus
{
    public function publish(DomainEvent ...$events): void;

    public function subscribe(DomainEventSubscriber ...$subscribers): void;
}
