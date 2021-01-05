<?php

declare(strict_types=1);

namespace Quote\Shared\Domain\Model\Event;

class StoredEventNotFoundException extends \Exception
{
    public static function byId(string $id): self
    {
        $msg = \sprintf('Stored Event id: %s not found', $id);

        return new self($msg);
    }
}
