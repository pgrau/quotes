<?php

declare(strict_types=1);

namespace Quote\Shared\Infrastructure\Bus\League;

use Quote\Api\Application\Query\GetShoutsByAuthor\GetShoutsByAuthorQueryHandler;
use Symfony\Component\DependencyInjection\Container;

final class QueryBusProvider
{
    public function __invoke(Container $container)
    {
        $this->getShoutsByAuthor($container);
    }

    private function getShoutsByAuthor(Container $container): void
    {
        $container->set(GetShoutsByAuthorQueryHandler::class, function () use ($container): GetShoutsByAuthorQueryHandler {

            return $container->get('quote.api.query.get.shouts.by.author');
        });
    }
}
