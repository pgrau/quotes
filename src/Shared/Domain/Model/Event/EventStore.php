<?php

declare(strict_types=1);

namespace Quote\Shared\Domain\Model\Event;

interface EventStore
{
    public function append(DomainEvent ...$events): void;

    public function findOne(string $id): ?StoredEvent;

    public function getOne(string $id): StoredEvent;
}
