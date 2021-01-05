<?php

declare(strict_types=1);

namespace Quote\Shared\Infrastructure\Bus\League;

use Doctrine\DBAL\Connection;
use League\Tactician\Middleware;

class DbalTransactionMiddleware implements Middleware
{
    /** @var Connection */
    private $conn;

    public function __construct(Connection $connection)
    {
        $this->conn = $connection;
    }

    public function execute($command, callable $next)
    {
        try {
            $this->conn->beginTransaction();

            $returnValue = $next($command);

            $this->conn->commit();
        } catch (\Throwable $exception) {
            $this->conn->rollBack();

            throw $exception;
        }

        return $returnValue;
    }
}
