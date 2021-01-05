<?php

declare(strict_types=1);

namespace Quote\Shared\Infrastructure\Persistence\Dbal\Event;

use Doctrine\DBAL\Connection;
use Quote\Shared\Domain\Model\Event\DomainEvent;
use Quote\Shared\Domain\Model\Event\EventNotPublished;
use Quote\Shared\Domain\Model\Event\EventNotPublishedNotFoundException;
use Quote\Shared\Domain\Model\Event\EventNotPublishedStore;

class DbalEventNotPublishedStore implements EventNotPublishedStore
{
    /** @var Connection */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    final public function persist(EventNotPublished $event): void
    {
        $this->connection->executeQuery(\sprintf("LOCK TABLE %s WRITE", $this->getTable()));

        $statement = $this->connection->prepare(
            "INSERT INTO {$this->getTable()} (event_id, name, last_error, created_at, updated_at)
                 VALUES (:event_id, :name, :last_error, :created_at, :updated_at) 
                 ON DUPLICATE KEY 
                    UPDATE last_error = values(last_error), updated_at = values(updated_at)"
        );

        $statement->bindValue(':event_id', $event->eventId());
        $statement->bindValue(':name', $event->eventName());
        $statement->bindValue(':last_error', $event->jsonLastError());
        $statement->bindValue(':created_at', $event->createdAt()->format(DomainEvent::DATE_FORMAT));
        $statement->bindValue(':updated_at', $event->updatedAt()->format(DomainEvent::DATE_FORMAT));
        $statement->execute();

        $this->connection->executeQuery("UNLOCK TABLES");
    }

    final public function remove(EventNotPublished $event): void
    {
        $this->connection->executeQuery(\sprintf("LOCK TABLE %s WRITE", $this->getTable()));

        $statement = $this->connection->prepare(
            "DELETE FROM {$this->getTable()} WHERE event_id = :event_id"
        );

        $statement->bindValue(':event_id', $event->eventId());
        $statement->execute();

        $this->connection->executeQuery("UNLOCK TABLES");
    }

    final public function findBetweenDates(
        \DateTimeImmutable $startDate,
        \DateTimeImmutable $finishDate,
        ?int $limit = 100,
        ?int $offset = 0
    ): array {
        $statement = $this->connection->prepare(
            "SELECT 
                    cenp.event_id,
                    ces.aggregate_id,
                    ces.version,
                    ces.name,
                    ces.payload,
                    ces.priority,
                    ces.is_publishable,
                    ces.occurred_on,
                    cenp.last_error,
                    cenp.created_at,
                    cenp.updated_at
                 FROM {$this->getTable()} as cenp
                 INNER JOIN core_event_store as ces ON cenp.event_id = ces.id 
                 WHERE 
                        cenp.created_at BETWEEN :startDate AND :finishDate     
                    AND
                        cenp.updated_at BETWEEN :startDate AND :finishDate 
                 GROUP BY cenp.event_id
                 ORDER BY ces.priority DESC, ces.occurred_on ASC
                 LIMIT :offset, :limit
                 LOCK IN SHARE MODE"
        );

        $statement->bindValue(':startDate', $startDate->format(DomainEvent::DATE_FORMAT));
        $statement->bindValue(':finishDate', $finishDate->format(DomainEvent::DATE_FORMAT));
        $statement->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $statement->bindValue(':limit', $limit, \PDO::PARAM_INT);

        $statement->execute();

        $events = [];

        $data = $statement->fetchAll();
        foreach ($data as $event) {
            $events[] = EventNotPublished::fromDataSource(
                $event['event_id'],
                $event['name'],
                json_decode($event['payload'], true),
                (int)$event['priority'],
                new \DateTimeImmutable($event['occurred_on']),
                isset($event['aggregate_id']) ? (int)$event['aggregate_id'] : null,
                (int)$event['version'],
                $event['last_error'] ? json_decode($event['last_error'], true) : null,
                new \DateTimeImmutable($event['created_at']),
                new \DateTimeImmutable($event['updated_at'])
            );
        }

        return $events;
    }

    final public function getOne(string $id): EventNotPublished
    {
        if (!$eventNotPublished = $this->findOne($id)) {
            throw EventNotPublishedNotFoundException::byId($id);
        }

        return $eventNotPublished;
    }

    final public function findOne(string $id): ?EventNotPublished
    {
        $statement = $this->connection->prepare(
            "SELECT 
                    cenp.event_id,
                    ces.aggregate_id,
                    ces.version,
                    ces.name,
                    ces.payload,
                    ces.priority,
                    ces.is_publishable,
                    ces.occurred_on,
                    cenp.last_error,
                    cenp.created_at,
                    cenp.updated_at
                 FROM {$this->getTable()} as cenp
                 INNER JOIN core_event_store as ces ON cenp.event_id = ces.id 
                 WHERE cenp.event_id = :uuid 
                 LOCK IN SHARE MODE"
        );

        $statement->bindValue(':uuid', $id);
        $statement->execute();

        $result = $statement->fetch();

        if (!$result) {
            return null;
        }

        return EventNotPublished::fromDataSource(
            $result['event_id'],
            $result['name'],
            json_decode($result['payload'], true),
            (int)$result['priority'],
            new \DateTimeImmutable($result['occurred_on']),
            isset($result['aggregate_id']) ? (int)$result['aggregate_id'] : null,
            (int)$result['version'],
            $result['last_error'] ? json_decode($result['last_error'], true) : null,
            new \DateTimeImmutable($result['created_at']),
            new \DateTimeImmutable($result['updated_at'])
        );
    }

    protected function getTable(): string
    {
        return 'event_not_published';
    }
}
