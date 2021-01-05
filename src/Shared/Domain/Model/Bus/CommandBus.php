<?php

declare(strict_types=1);

namespace Quote\Shared\Domain\Model\Bus;

use Quote\Shared\Domain\Model\Command;

interface CommandBus
{
    public function dispatch(Command $command);
}
