<?php

declare(strict_types=1);

namespace Quote\Api\Application\Command\ImportQuotes;

use Quote\Shared\Domain\Model\Command;

final class ImportQuotesCommand implements Command
{
    public function __construct(private array $data)
    {
    }

    public function data(): array
    {
        return $this->data;
    }
}
