<?php

declare(strict_types=1);

namespace Quote\Api\Application\Command\ImportQuotes;

use Quote\Api\Domain\Model\Author\Author;
use Quote\Api\Domain\Model\Author\AuthorRepository;
use Quote\Api\Domain\Model\Quote\Quote;
use Quote\Shared\Domain\Model\Bus\EventBus;
use Webmozart\Assert\Assert;

final class ImportQuotesCommandHandler
{
    public function __construct(private EventBus $eventBus, private AuthorRepository $authorRepository)
    {
    }

    public function __invoke(ImportQuotesCommand $command): void
    {
        foreach ($command->data() as $item) {
            Assert::keyExists($item, 'author');
            Assert::keyExists($item, 'quote');

            $author = $this->authorRepository->getOne(Author::getIdFromFullName($item['author']));

            $quote = Quote::create($author, $item['quote']);

            $this->eventBus->publish(...$quote->pullDomainEvents());
        }
    }
}
