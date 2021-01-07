<?php

use React\ChildProcess\Process;
use React\EventLoop\Factory;

(new \Symfony\Component\Dotenv\Dotenv())->bootEnv(__DIR__.'/../../.env.test', 'test');

$sqlDb = file_get_contents(__DIR__ . '/../../resources/database/mysql/test/database.sql');
$sqlSchema = file_get_contents(__DIR__ . '/../../resources/database/mysql/schema.sql');

$connectionParams = array(
    'url' => $_ENV['MYSQL_URL'],
);
$conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams);

$conn->executeQuery($sqlDb);
$conn->executeQuery('use quote_api_test;');
$conn->executeQuery($sqlSchema);

$loop = Factory::create();

$process = new Process('bin/console rabbitmq:configure --env=test');
$process->start($loop);

$loop->run();