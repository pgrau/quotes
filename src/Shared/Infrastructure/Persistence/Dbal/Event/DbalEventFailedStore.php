<?php

declare(strict_types=1);

namespace Quote\Shared\Infrastructure\Persistence\Dbal\Event;

use Doctrine\DBAL\Connection;
use Quote\Shared\Domain\Model\Event\DomainEvent;
use Quote\Shared\Domain\Model\Event\EventFailed;
use Quote\Shared\Domain\Model\Event\EventFailedNotFoundException;
use Quote\Shared\Domain\Model\Event\EventFailedStore;

class DbalEventFailedStore implements EventFailedStore
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    final public function persist(EventFailed $event): void
    {
        $this->connection->executeQuery(\sprintf("LOCK TABLE %s WRITE", $this->getTable()));

        $statement = $this->connection->prepare(
            "INSERT INTO
              {$this->getTable()} (event_id, name, retry, created_at, last_error)
              VALUES (:event_id, :name, :retry, :created_at, :last_error)"
        );

        $statement->bindValue(':event_id', $event->eventId());
        $statement->bindValue(':name', $event->name());
        $statement->bindValue(':retry', $event->retry());
        $statement->bindValue(':created_at', $event->createdAt()->format(DomainEvent::DATE_FORMAT));
        $statement->bindValue(':last_error', $event->jsonLastError());
        $statement->execute();

        $this->connection->executeQuery("UNLOCK TABLES");
    }

    final public function findOne(string $id): ?EventFailed
    {
        $statement = $this->connection->prepare(
            "SELECT event_id, name, retry, created_at, last_error
                 FROM {$this->getTable()} 
                 WHERE event_id = :uuid"
        );

        $statement->bindValue(':uuid', $id);
        $statement->execute();

        $result = $statement->fetch();

        if (!$result) {
            return null;
        }

        return EventFailed::fromEventStore(
            $result['event_id'],
            $result['name'],
            (int) $result['retry'],
            json_decode($result['last_error'], true),
            new \DateTimeImmutable($result['created_at'])
        );
    }

    final public function getOne(string $id): EventFailed
    {
        if (!$eventFailed = $this->findOne($id)) {
            throw EventFailedNotFoundException::byId($id);
        }

        return $eventFailed;
    }

    protected function getTable(): string
    {
        return 'event_failed';
    }
}
