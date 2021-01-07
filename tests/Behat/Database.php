<?php

namespace Quote\Api\Tests\Behat;

use Doctrine\DBAL\Connection;

abstract class Database
{
    private static Connection $conn;

    public function __construct(Connection $conn)
    {
        self::$conn = $conn;
    }

    protected static function execFixtures(string... $fixtures): void
    {
        self::$conn->executeQuery('SET foreign_key_checks = 0');

        foreach ($fixtures as $fixture) {
            self::$conn->executeQuery($fixture);
        }

        self::$conn->executeQuery('SET foreign_key_checks = 1');
    }
}
