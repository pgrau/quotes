<?php

declare(strict_types=1);

namespace Quote\Shared\Domain\Model\Serializer;

interface Serializer
{
    public function serialize($value): string;

    public function unserialize(string $string, array $options = []);
}
