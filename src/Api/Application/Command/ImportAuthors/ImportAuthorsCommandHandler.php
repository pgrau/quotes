<?php

declare(strict_types=1);

namespace Quote\Api\Application\Command\ImportAuthors;

use Quote\Api\Domain\Model\Author\Author;
use Quote\Shared\Domain\Model\Bus\EventBus;
use Webmozart\Assert\Assert;

final class ImportAuthorsCommandHandler
{
    public function __construct(private EventBus $eventBus)
    {
    }

    public function __invoke(ImportAuthorsCommand $command): void
    {
        $collection = [];
        foreach ($command->data() as $item) {
            Assert::keyExists($item, 'author');
            $author = Author::create($item['author']);

            if (isset($collection[$author->id()])) {
                continue;
            }

            $collection[$author->id()] = $author->id();

            $this->eventBus->publish(...$author->pullDomainEvents());
        }
    }
}
