<?php

use Core\Infra\Database\SqlConnection;
use Dotenv\Dotenv;

require_once(__DIR__ . "/../../../vendor/autoload.php");

$dotenv = Dotenv::createImmutable(__DIR__ . "/../../../");
$dotenv->load();

$database = SqlConnection::connect();

try {

    echo "Starting migration...\n";
    $database->beginTransaction();
    
    $version = $_ENV["APP_VERSION"];
    $migrationFolder = __DIR__ . "/Migrations/Sql/$version";
    
    if(!is_dir($migrationFolder)) {
        throw new Exception("Migration folder not found", 500);
    }
    
    echo "Searching for migrations...\n";

    $migrations = array_map(function ($fileSrc) use ($migrationFolder) {

        $file = file_get_contents("$migrationFolder/$fileSrc");

        if(empty($file)) return;

        return $file;

    }, array_diff(scandir($migrationFolder), [".", ".."]));
    
    if(empty($migrations)) {
        
        echo "No migrations found\n";
        return;
        
    }

    echo "Migrations found: " . count($migrations) . "\n";

    foreach($migrations as $migration) {

        echo  "Executing migration:\r\n" . $migration . "\n";
        $database->exec($migration);

    }

    if($database->inTransaction()) $database->commit();

    echo "Migration finished\n";

} catch(Exception $e) {

    if($database->inTransaction()) $database->rollBack();

    throw $e;

}