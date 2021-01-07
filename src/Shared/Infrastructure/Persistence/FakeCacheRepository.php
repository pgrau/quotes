<?php

declare(strict_types=1);

namespace Quote\Shared\Infrastructure\Persistence;

use Quote\Shared\Domain\Model\Cache\CacheRepository;

class FakeCacheRepository implements CacheRepository
{
    public function set(string $key, $value, int $ttl = 3600 * 24): void
    {
    }

    public function get(string $key)
    {
        return false;
    }
}
