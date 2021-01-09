<?php

declare(strict_types=1);

namespace Quote\Shared\Infrastructure\Bus\Custom;

use Quote\Api\Domain\Event\Author\CreateAuthorSubscriber;
use Quote\Shared\Domain\Model\Bus\EventBus;
use Quote\Shared\Domain\Model\Event\DomainEvent;
use Quote\Shared\Domain\Model\Event\DomainEventPublisher;
use Quote\Shared\Domain\Model\Event\DomainEventSubscriber;
use Quote\Shared\Domain\Model\Event\EventStore;
use Psr\Container\ContainerInterface;

final class CustomEventBus implements EventBus
{
    private array $subscribers = [];

    public function __construct(
        private EventStore $eventStore,
        private DomainEventPublisher $publisher,
        private ContainerInterface $container
    ) {}

    public function __clone()
    {
        throw new \BadMethodCallException('Clone is not supported');
    }

    public function publish(DomainEvent ...$events): void
    {
        $this->eventStore->append(...$events);

        foreach ($events as $event) {
            foreach ($this->subscribers as $subscriber) {

                /** @var $subscriber DomainEventSubscriber */
                if (in_array(get_class($event), $subscriber->subscribedTo())) {
                    $subscriber->handle($event);
                }
            }
        }

        $this->publisher->publish(...$events);
    }

    public function subscribe(DomainEventSubscriber ...$subscribers): void
    {
        foreach ($subscribers as $subscriber) {
            $this->subscribers[get_class($subscriber)] = $subscriber;
        }
    }
}
