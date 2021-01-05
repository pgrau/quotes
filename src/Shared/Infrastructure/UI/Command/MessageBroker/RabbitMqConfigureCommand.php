<?php

declare(strict_types=1);

namespace Quote\Shared\Infrastructure\UI\Command\MessageBroker;

use Quote\Shared\Infrastructure\MessageBroker\RabbitMq\RabbitMqConfigurer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RabbitMqConfigureCommand extends Command
{
    protected static $defaultName = 'rabbitmq:configure';

    public function __construct(private RabbitMqConfigurer $configurer)
    {
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Configure exchanges / queues RabbitMQ');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->configurer->configure($_ENV['RABBITMQ_EXCHANGE']);

        return Command::SUCCESS;
    }
}
