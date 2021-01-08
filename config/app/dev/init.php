<?php

include __DIR__ . '/../../../vendor/autoload.php';

(new \Symfony\Component\Dotenv\Dotenv())->bootEnv(__DIR__.'/../../../.env', 'dev');

if ($_ENV['APP_ENV'] !== 'dev') {
    echo 'This script is only available for development environment';
    die;
}

$sqlDb = file_get_contents(__DIR__ . '/../../../resources/database/mysql/dev/database.sql');
$sqlSchema = file_get_contents(__DIR__ . '/../../../resources/database/mysql/schema.sql');

$connectionParams = [
    'url' => $_ENV['MYSQL_URL'],
];
$conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams);

$conn->executeQuery($sqlDb);
$conn->executeQuery('use quote_api;');
$conn->executeQuery($sqlSchema);

$numAuthor = (int) $conn->executeQuery("SELECT count(*) as num FROM author")->fetchAssociative()['num'];
$numQuote = (int) $conn->executeQuery("SELECT count(*) as num FROM quote")->fetchAssociative()['num'];

echo 'Database and schema created or updated correctly.' . PHP_EOL;

$commands = [
    "bin/console rabbitmq:configure",
    "bin/console rabbitmq:process quotes_api 2",
    "bin/console api:import:authors",
    "bin/console api:import:quotes",
    "echo Welcome to Quotes API. Happy code!",
    "curl -i -X GET http://localhost:8080/shout/steve-jobs",
];

$loop = \React\EventLoop\Factory::create();

$processes = [];
foreach ($commands as $i => $command) {

    if ($command === 'bin/console api:import:authors' && $numAuthor > 0) {
        continue;
    }

    if ($command === 'bin/console api:import:quotes' && $numQuote > 0) {
        continue;
    }

    $processes[$i] = new \React\ChildProcess\Process($command);
    $processes[$i]->start($loop);

    $processes[$i]->stdout->on('data', function ($chunk) {
        echo $chunk;
    });

    sleep(2);
}

$loop->run();