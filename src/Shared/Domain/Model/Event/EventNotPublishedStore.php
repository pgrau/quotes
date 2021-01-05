<?php

declare(strict_types=1);

namespace Quote\Shared\Domain\Model\Event;

interface EventNotPublishedStore
{
    public function persist(EventNotPublished $event): void;

    public function remove(EventNotPublished $event): void;

    public function findBetweenDates(
        \DateTimeImmutable $startDate,
        \DateTimeImmutable $finishDate,
        ?int $limit = 100,
        ?int $offset = 0
    ): array;

    public function getOne(string $id): EventNotPublished;

    public function findOne(string $id): ?EventNotPublished;
}
