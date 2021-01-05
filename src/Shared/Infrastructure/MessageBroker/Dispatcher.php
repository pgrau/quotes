<?php

declare(strict_types=1);

namespace Quote\Shared\Infrastructure\MessageBroker;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use Quote\Shared\Domain\Model\Event\DomainEvent;

final class Dispatcher
{
    public function __construct(
        private EntityManager $entityManager,
        private ContainerInterface $container,
        private SubscriberMapper $mapper
    ) {}

    public function dispatch(DomainEvent $domainEvent)
    {
        if (! $this->entityManager->isOpen()) {
            $this->entityManager = $this->entityManager->create(
                $this->entityManager->getConnection(),
                $this->entityManager->getConfiguration()
            );
        }

        $this->entityManager->transactional(function () use ($domainEvent) {
            $subscribers = $this->mapper->get($domainEvent);
            foreach ($subscribers as $serviceId) {
                $subscriber = $this->container->get($serviceId);
                $subscriber->handle($domainEvent);
            }
        });
    }
}
