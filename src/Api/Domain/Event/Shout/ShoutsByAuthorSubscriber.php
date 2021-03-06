<?php

declare(strict_types=1);

namespace Quote\Api\Domain\Event\Shout;

use Quote\Api\Domain\Model\Shout\ShoutsByAuthorRequestedV1;
use Quote\Shared\Domain\Model\Cache\CacheRepository;
use Quote\Shared\Domain\Model\Event\DomainEvent;
use Quote\Shared\Domain\Model\Event\DomainEventSubscriber;
use Quote\Shared\Domain\Model\Serializer\Serializer;

class ShoutsByAuthorSubscriber implements DomainEventSubscriber
{
    public function __construct(private CacheRepository $cacheRepository, private Serializer $serializer)
    {
    }

    public static function subscribedTo(): array
    {
        return [ShoutsByAuthorRequestedV1::class];
    }

    public function handle(DomainEvent $domainEvent): void
    {
        $payload = $domainEvent->payload();
        $key = ShoutsByAuthorRequestedV1::EVENT_NAME.$payload['author_id'].$payload['limit'];
        $value = $this->serializer->unserialize($domainEvent->payload()['shouts']);

        $this->cacheRepository->set($key, $value, 3600 * 24);
    }
}
