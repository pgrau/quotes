<?php

namespace Quote\Api\Tests\Behat;

use Behat\Behat\Context\Context;

final class DatabaseQuoteContext extends Database implements Context
{
    /** @BeforeSuite */
    public static function configure(): void
    {
        parent::truncateTables('author', 'quote');

        $author = file_get_contents( __DIR__ . '/../fixtures/author.sql');
        $quote = file_get_contents( __DIR__ . '/../fixtures/quote.sql');

        parent::execFixtures($author, $quote);
    }
}
