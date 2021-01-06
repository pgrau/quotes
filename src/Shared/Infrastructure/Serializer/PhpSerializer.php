<?php

declare(strict_types=1);

namespace Quote\Shared\Infrastructure\Serializer;

use Quote\Shared\Domain\Model\Serializer\Serializer;

class PhpSerializer implements Serializer
{
    public function serialize($value): string
    {
        return \serialize($value);
    }

    public function unserialize(string $string, array $options = [])
    {
        return @\unserialize($string, $options);
    }
}
