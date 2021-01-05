<?php

declare(strict_types=1);

namespace Quote\Shared\Domain\Model\Bus;

use Quote\Shared\Domain\Model\Query;

interface QueryBus
{
    public function ask(Query $query);
}
