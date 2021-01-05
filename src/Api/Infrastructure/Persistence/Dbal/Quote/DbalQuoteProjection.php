<?php

declare(strict_types=1);

namespace Quote\Api\Infrastructure\Persistence\Dbal\Quote;

use Doctrine\DBAL\Connection;
use Quote\Api\Domain\Model\Quote\QuoteCreatedV1;
use Quote\Api\Domain\Model\Quote\QuoteProjection;
use Quote\Shared\Domain\Model\Event\StoredEvent;

class DbalQuoteProjection implements QuoteProjection
{
    public function __construct(private Connection $connection)
    {
    }

    public function projectQuoteCreated(StoredEvent|QuoteCreatedV1 $domainEvent): void
    {
        $statement = $this->connection->prepare(
            "INSERT INTO
              {$this->getTable()} (id, author_id, quote)
              VALUES (:id, :author_id, :quote)"
        );

        $statement->bindValue(':id', $domainEvent->payload()['id']);
        $statement->bindValue(':author_id', $domainEvent->payload()['author_id']);
        $statement->bindValue(':quote', $domainEvent->payload()['quote']);
        $statement->execute();
    }

    private function getTable(): string
    {
        return 'quote';
    }
}