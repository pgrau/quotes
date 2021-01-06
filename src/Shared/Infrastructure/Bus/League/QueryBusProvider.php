<?php

declare(strict_types=1);

namespace Quote\Shared\Infrastructure\Bus\League;

use Quote\Api\Application\Query\GetQuotesByAuthor\GetQuotesByAuthorQueryHandler;
use Symfony\Component\DependencyInjection\Container;

final class QueryBusProvider
{
    public function __invoke(Container $container)
    {
        $this->getQuotesByAuthor($container);
    }

    private function getQuotesByAuthor(Container $container): void
    {
        $container->set(GetQuotesByAuthorQueryHandler::class, function () use ($container): GetQuotesByAuthorQueryHandler {
            return $container->get('quote.api.query.get.quotes.by.author');
        });
    }
}
