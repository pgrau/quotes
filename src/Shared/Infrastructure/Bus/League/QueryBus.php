<?php

declare(strict_types=1);

namespace Quote\Shared\Infrastructure\Bus\League;

use Quote\Shared\Domain\Model\Query;

final class QueryBus implements \Quote\Shared\Domain\Model\Bus\QueryBus
{
    private $queryBus;

    public function __construct(\League\Tactician\CommandBus $commandBus)
    {
        $this->queryBus = $commandBus;
    }

    public function ask(Query $query)
    {
        return $this->queryBus->handle($query)($query);
    }
}
