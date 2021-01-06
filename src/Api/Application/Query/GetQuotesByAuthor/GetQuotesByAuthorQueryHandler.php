<?php

declare(strict_types=1);

namespace Quote\Api\Application\Query\GetQuotesByAuthor;

use Quote\Api\Domain\Model\Author\AuthorRepository;
use Quote\Api\Domain\Model\Quote\QuoteRepository;
use Quote\Api\Domain\Model\Quote\QuotesByAuthorRequestedV1;
use Quote\Shared\Domain\Model\Cache\CacheRepository;
use Quote\Shared\Domain\Model\Event\DomainEventPublisher;
use Quote\Shared\Domain\Model\Exception\BadRequestException;
use Quote\Shared\Domain\Model\Serializer\Serializer;

final class GetQuotesByAuthorQueryHandler
{
    public function __construct(
        private DomainEventPublisher $domainEventPublisher,
        private AuthorRepository $authorRepository,
        private QuoteRepository $quoteRepository,
        private CacheRepository $cacheRepository,
        private Serializer $serializer
    ) {}

    public function __invoke(GetQuotesByAuthorQuery $query): GetQuotesByAuthorQueryResponse
    {
        if ($query->limit() <= 0 || $query->limit() > 10) {
            $msg = \sprintf('Limit must be between 1 and 10. Got(%d)', $query->limit());
            throw new BadRequestException($msg);
        }

        $keyCache = QuotesByAuthorRequestedV1::EVENT_NAME.$query->authorId().$query->limit();

        if (! $quotes = $this->cacheRepository->get($keyCache)) {
            $author = $this->authorRepository->getOne($query->authorId());
            $quotes = $this->quoteRepository->findByAuthor($author->id(), $query->limit());

            $this->domainEventPublisher->publish(
                new QuotesByAuthorRequestedV1($this->serializer->serialize($quotes), $author->id(), $query->limit())
            );
        }

        $response = new GetQuotesByAuthorQueryResponse($quotes);

        return $response;
    }
}
