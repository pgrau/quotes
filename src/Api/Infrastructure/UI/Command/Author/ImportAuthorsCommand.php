<?php

declare(strict_types=1);

namespace Quote\Api\Infrastructure\UI\Command\Author;

use Quote\Shared\Domain\Model\Bus\CommandBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ImportAuthorsCommand extends Command
{
    private const JSON_PATH = __DIR__ . '/../../../../../../resources/migration/20210104/data.json';

    protected static $defaultName = 'api:import:authors';

    public function __construct(private CommandBus $commandBus)
    {
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Import authors from JSON')
            ->addArgument('jsonPathFile', InputArgument::OPTIONAL, 'Path of Json', self::JSON_PATH);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $jsonPathFile = $input->getArgument('jsonPathFile');
        $data = json_decode(file_get_contents($jsonPathFile), true)['quotes'];

        $command = new \Quote\Api\Application\Command\ImportAuthors\ImportAuthorsCommand($data);

        $this->commandBus->dispatch($command);

        $output->writeln('<fg=green>Import authors done</>');

        return Command::SUCCESS;
    }
}
