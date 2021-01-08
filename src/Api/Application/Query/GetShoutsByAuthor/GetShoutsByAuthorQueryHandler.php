<?php

declare(strict_types=1);

namespace Quote\Api\Application\Query\GetShoutsByAuthor;

use Quote\Api\Domain\Model\Author\AuthorRepository;
use Quote\Api\Domain\Model\Quote\QuoteRepository;
use Quote\Api\Domain\Model\Shout\ShoutCollection;
use Quote\Api\Domain\Model\Shout\ShoutsByAuthorRequestedV1;
use Quote\Shared\Domain\Model\Cache\CacheRepository;
use Quote\Shared\Domain\Model\Event\DomainEventPublisher;
use Quote\Shared\Domain\Model\Exception\BadRequestException;
use Quote\Shared\Domain\Model\Serializer\Serializer;

final class GetShoutsByAuthorQueryHandler
{
    public function __construct(
        private DomainEventPublisher $domainEventPublisher,
        private AuthorRepository $authorRepository,
        private QuoteRepository $quoteRepository,
        private CacheRepository $cacheRepository,
        private Serializer $serializer
    ) {}

    public function __invoke(GetShoutsByAuthorQuery $query): GetShoutsByAuthorQueryResponse
    {
        if ($query->limit() <= 0 || $query->limit() > 10) {
            $msg = \sprintf('Limit must be between 1 and 10. Got(%d)', $query->limit());
            throw new BadRequestException($msg);
        }

        $keyCache = ShoutsByAuthorRequestedV1::EVENT_NAME.$query->authorId().$query->limit();

        if (! $shouts = $this->cacheRepository->get($keyCache)) {

            $author = $this->authorRepository->getOne($query->authorId());
            $shouts = $this->quoteRepository->findByAuthor($author->id(), $query->limit());
            $shouts = ShoutCollection::fromQuotes(...$shouts);

            $this->domainEventPublisher->publish(
                new ShoutsByAuthorRequestedV1($this->serializer->serialize($shouts), $author->id(), $query->limit())
            );
        }

        $response = new GetShoutsByAuthorQueryResponse($shouts);

        return $response;
    }
}
