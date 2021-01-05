<?php

declare(strict_types=1);

namespace Quote\Shared\Infrastructure\Bus\League;

use Quote\Api\Application\Command\ImportAuthors\ImportAuthorsCommandHandler;
use Quote\Api\Application\Command\ImportQuotes\ImportQuotesCommandHandler;
use Symfony\Component\DependencyInjection\Container;

final class CommandBusProvider
{
    public function __invoke(Container $container)
    {
        $this->importAuthors($container);
        $this->importQuotes($container);
    }

    private function importAuthors(Container $container): void
    {
        $container->set(ImportAuthorsCommandHandler::class, function () use ($container): ImportAuthorsCommandHandler {
            return $container->get('quote.api.command.import.authors');
        });
    }

    private function importQuotes(Container $container): void
    {
        $container->set(ImportQuotesCommandHandler::class, function () use ($container): ImportQuotesCommandHandler {
            return $container->get('quote.api.command.import.quotes');
        });
    }
}
