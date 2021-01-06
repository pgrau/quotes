<?php

declare(strict_types=1);

namespace Quote\Shared\Infrastructure\Bus\League;

use League\Tactician\Container\ContainerLocator;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\MethodNameInflector\InvokeInflector;
use Psr\Container\ContainerInterface;
use Quote\Api\Application\Query\GetQuotesByAuthor\GetQuotesByAuthorQuery;
use Quote\Api\Application\Query\GetQuotesByAuthor\GetQuotesByAuthorQueryHandler;

final class QueryBusFactory
{
    public static function create(ContainerInterface $container): QueryBus
    {
        $provider = new QueryBusProvider();
        $provider($container);

        $commandHandlerMiddleware = new CommandHandlerMiddleware(
            new ClassNameExtractor(),
            new ContainerLocator($container, self::map()),
            new InvokeInflector()
        );

        $commandBus = new \League\Tactician\CommandBus(
            [
                $commandHandlerMiddleware
            ]
        );

        return new QueryBus($commandBus);
    }

    private static function map(): array
    {
        return [
            GetQuotesByAuthorQuery::class => GetQuotesByAuthorQueryHandler::class,
        ];
    }
}
