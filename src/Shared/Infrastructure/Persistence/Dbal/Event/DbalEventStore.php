<?php

declare(strict_types=1);

namespace Quote\Shared\Infrastructure\Persistence\Dbal\Event;

use Doctrine\DBAL\Connection;
use Quote\Shared\Domain\Model\Event\DomainEvent;
use Quote\Shared\Domain\Model\Event\EventStore;
use Quote\Shared\Domain\Model\Event\PublishableDomainEvent;
use Quote\Shared\Domain\Model\Event\StoredEvent;
use Quote\Shared\Domain\Model\Event\StoredEventNotFoundException;

class DbalEventStore implements EventStore
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    final public function append(DomainEvent ...$events): void
    {
        $this->connection->executeQuery(\sprintf("LOCK TABLE %s WRITE", $this->getTable()));

        foreach ($events as $event) {
            $statement = $this->connection->prepare(
                "INSERT INTO
              {$this->getTable()} (id, aggregate_id, name, payload, occurred_on, version, priority, is_publishable)
              VALUES (:id, :aggregate_id, :name, :payload, :occurred_on, :version, :priority, :is_publishable)"
            );

            $statement->bindValue(':id', $event->eventId());
            $statement->bindValue(':aggregate_id', $event->aggregateId());
            $statement->bindValue(':name', $event->eventName());
            $statement->bindValue(':payload', $event->jsonPayload());
            $statement->bindValue(':occurred_on', $event->occurredOn()->format($event::DATE_FORMAT));
            $statement->bindValue(':version', $event->version());
            $statement->bindValue(':priority', $event->priority());
            $statement->bindValue(':is_publishable', (int)($event instanceof PublishableDomainEvent));
            $statement->execute();
        }

        $this->connection->executeQuery("UNLOCK TABLES");
    }

    final public function findOne(string $id): ?StoredEvent
    {
        $statement = $this->connection->prepare(
            "SELECT id, aggregate_id, version, name, payload, priority, is_publishable, occurred_on
                 FROM {$this->getTable()} 
                 WHERE id = :uuid"
        );

        $statement->bindValue(':uuid', $id);
        $statement->execute();
        $result = $statement->fetch();

        if (!$result) {
            return null;
        }

        return StoredEvent::fromEventStore(
            $result['id'],
            $result['name'],
            json_decode($result['payload'], true),
            (int)$result['priority'],
            new \DateTimeImmutable($result['occurred_on']),
            isset($result['aggregate_id']) ? (int) $result['aggregate_id'] : null,
            (int)$result['version']
        );
    }

    final public function getOne(string $id): StoredEvent
    {
        if (!$storedEvent = $this->findOne($id)) {
            throw StoredEventNotFoundException::byId($id);
        }

        return $storedEvent;
    }

    protected function getTable(): string
    {
        return 'event_store';
    }
}
