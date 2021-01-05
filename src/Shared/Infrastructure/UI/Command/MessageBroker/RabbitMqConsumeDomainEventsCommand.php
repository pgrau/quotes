<?php

declare(strict_types=1);

namespace Quote\Shared\Infrastructure\UI\Command\MessageBroker;

use Quote\Shared\Infrastructure\MessageBroker\Dispatcher;
use Quote\Shared\Infrastructure\MessageBroker\RabbitMq\RabbitMqDomainEventsConsumer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class RabbitMqConsumeDomainEventsCommand extends Command
{
    protected static $defaultName = 'rabbitmq:consume';

    public function __construct(
        private RabbitMqDomainEventsConsumer $consumer,
        private Dispatcher $dispatcher
    ) {
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Consume domain events from the RabbitMQ')
            ->addArgument('queue', InputArgument::REQUIRED, 'Queue name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $queueName = (string) $input->getArgument('queue');

        if ($this->consumer->consume($this->dispatcher, $queueName) == false) {
            $output->writeln('<fg=green>Consumer stopped gracefully</>');

            return Command::SUCCESS;
        };

        return Command::SUCCESS;
    }
}
