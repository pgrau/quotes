<?php

declare(strict_types=1);

namespace Quote\Shared\Infrastructure\MessageBroker\RabbitMq;

use React\ChildProcess\Process;
use React\EventLoop\Factory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class RabbitMqUpDomainEventsConsumers extends Command
{
    protected static $defaultName = 'rabbitmq:process';

    protected function configure(): void
    {
        $this
            ->setDescription('Up n domain events consumers from the RabbitMQ ')
            ->addArgument('queue', InputArgument::REQUIRED, 'Queue name')
            ->addArgument('number', InputArgument::OPTIONAL, 'Number of Consumers', 1)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $queueName = (string) $input->getArgument('queue');
        $number = (int) $input->getArgument('number');

        $loop = Factory::create();

        $cmd = \sprintf('bin/console rabbitmq:consume %s', $queueName);
        $processes = [];
        for ($i = 1; $i <= $number; $i++) {
            $processes[$i] = new Process($cmd);
            $processes[$i]->start($loop);

            $pid = $processes[$i]->getPid();

            $processes[$i]->on('exit', function ($exitCode, $termSignal) use ($output, $pid) {
                if (! is_null($exitCode)) {
                    $msg = \sprintf('<fg=green>Process %d exited with code %d </>', (int) $pid, (int) $exitCode);
                    $output->writeln($msg);
                }

                if (! is_null($termSignal)) {
                    $msg = \sprintf('<fg=green>Process %d exited with signal %d </>', (int) $pid, (int) $termSignal);
                    $output->writeln($msg);
                }
            });
        }

        $loop->run();

        return Command::SUCCESS;
    }
}
