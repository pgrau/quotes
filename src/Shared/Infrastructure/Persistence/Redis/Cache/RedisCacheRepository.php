<?php

declare(strict_types=1);

namespace Quote\Shared\Infrastructure\Persistence\Redis\Cache;

use Quote\Shared\Domain\Model\Cache\CacheRepository;

class RedisCacheRepository implements CacheRepository
{
    public function __construct(private \Redis $redis)
    {
    }

    public function set(string $key, $value, int $ttl = 3600 * 24): void
    {
        $this->redis->setEx($key, $ttl, serialize($value));
    }

    public function get(string $key)
    {
        $response = false;
        if ($data = $this->redis->get($key)) {
            $response = @unserialize($key);
        }

        return $response;
    }
}
