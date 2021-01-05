<?php

declare(strict_types=1);

namespace Quote\Shared\Infrastructure\Bus\League;

use League\Tactician\Container\ContainerLocator;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\MethodNameInflector\InvokeInflector;;
use Psr\Container\ContainerInterface;
use Quote\Api\Application\Command\ImportAuthors\ImportAuthorsCommand;
use Quote\Api\Application\Command\ImportAuthors\ImportAuthorsCommandHandler;
use Quote\Api\Application\Command\ImportQuotes\ImportQuotesCommand;
use Quote\Api\Application\Command\ImportQuotes\ImportQuotesCommandHandler;

final class CommandBusFactory
{
    public static function create(ContainerInterface $container): CommandBus
    {
        $provider = new CommandBusProvider();
        $provider($container);

        $commandHandlerMiddleware = new CommandHandlerMiddleware(
            new ClassNameExtractor(),
            new ContainerLocator($container, self::map()),
            new InvokeInflector()
        );

        $commandBus = new \League\Tactician\CommandBus(
            [
                new DbalTransactionMiddleware($container->get('doctrine.dbal.default_connection')),
                $commandHandlerMiddleware
            ]
        );

        return new CommandBus($commandBus);
    }

    private static function map(): array
    {
        return [
            ImportAuthorsCommand::class => ImportAuthorsCommandHandler::class,
            ImportQuotesCommand::class => ImportQuotesCommandHandler::class,
        ];
    }
}
