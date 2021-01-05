<?php

declare(strict_types=1);

namespace Quote\Api\Application\Command\ImportAuthors;

use Quote\Shared\Domain\Model\Command;

final class ImportAuthorsCommand implements Command
{
    public function __construct(private array $data)
    {
    }

    public function data(): array
    {
        return $this->data;
    }
}
