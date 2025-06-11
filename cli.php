<?php

require_once(__DIR__."/vendor/autoload.php");

use Core\Console\Commands;
use Dotenv\Dotenv;

Dotenv::createImmutable(__DIR__)->load();

$argsLength = $_SERVER["argc"];
$args = array_slice($_SERVER["argv"], 1);

$commands = [
    "make:migration" => Commands\MakeMigration::class,
    "run:migration" => Commands\RunMigration::class,
    "rollback:migration" => Commands\RollbackMigration::class,
];

if(empty($args)) {
    
    $help = new Commands\Help;
    $help->handle(["commands" => $commands]);

    exit;

}

try {

    $commandName = $args[0] ?? null;
    $class = $commands[$commandName] ?? null;

    if(empty($commandName) || empty($class)) {
        throw new Exception("Command not found");
    }

    $params = array_slice($args, 1);

    /**
     * 
     * @var \Core\Console\Interfaces\CommandInterface $command
     * 
    */
    
    $command = new $class;
    $command->handle($params);

} catch(Exception $e) {

    echo "Error: {$e->getMessage()}\r\n";

    exit(1);

}