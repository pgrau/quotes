<?php

declare(strict_types=1);

namespace Quote\Shared\Domain\Model\Cache;

interface CacheRepository
{
    public function set(string $key, $value, int $ttl = 3600 * 24): void;

    public function get(string $key);
}
