<?php

declare(strict_types=1);

namespace Quote\Shared\Domain\Model\Event;

class EventNotPublishedNotFoundException extends \Exception
{
    public static function byId(string $id): self
    {
        $msg = \sprintf('Event not published id: %s not found', $id);

        return new self($msg);
    }
}
