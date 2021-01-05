<?php

declare(strict_types=1);

namespace Quote\Shared\Domain\Model\Event;

interface EventFailedStore
{
    public function persist(EventFailed $event): void;

    public function findOne(string $id): ?EventFailed;

    public function getOne(string $id): EventFailed;
}
