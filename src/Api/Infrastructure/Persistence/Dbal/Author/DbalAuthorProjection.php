<?php

declare(strict_types=1);

namespace Quote\Api\Infrastructure\Persistence\Dbal\Author;

use Doctrine\DBAL\Connection;
use Quote\Api\Domain\Model\Author\AuthorCreatedV1;
use Quote\Api\Domain\Model\Author\AuthorProjection;
use Quote\Shared\Domain\Model\Event\StoredEvent;

class DbalAuthorProjection implements AuthorProjection
{
    public function __construct(private Connection $connection)
    {
    }

    public function projectAuthorCreated(StoredEvent|AuthorCreatedV1 $domainEvent): void
    {
        $statement = $this->connection->prepare(
            "REPLACE INTO
              {$this->getTable()} (id, full_name)
              VALUES (:id, :full_name)"
        );

        $statement->bindValue(':id', $domainEvent->payload()['id']);
        $statement->bindValue(':full_name', $domainEvent->payload()['full_name']);
        $statement->execute();
    }

    private function getTable(): string
    {
        return 'author';
    }
}