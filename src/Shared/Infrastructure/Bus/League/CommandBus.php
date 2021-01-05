<?php

declare(strict_types=1);

namespace Quote\Shared\Infrastructure\Bus\League;

use Quote\Shared\Domain\Model\Command;

final class CommandBus implements \Quote\Shared\Domain\Model\Bus\CommandBus
{
    private $commandBus;

    public function __construct(\League\Tactician\CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function dispatch(Command $command)
    {
        return $this->commandBus->handle($command)($command);
    }
}
