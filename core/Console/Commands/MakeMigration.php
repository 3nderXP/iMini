<?php

namespace Core\Console\Commands;

use Core\Console\Interfaces\CommandInterface;
use Exception;

class MakeMigration implements CommandInterface {

    public const MIGRATION_PATH = __DIR__."/../../Infra/Database/Migrations/Sql";
    private const EXAMPLE_MIGRATION_PATH = __DIR__."/../../Infra/Database/Migrations/migration.example";

    public string $name = "make:migration";
    public readonly string $description;

    public function __construct() {

        $this->description = "Make Database Migration File";
        
    }

    public function handle(array $params = []) {

        @[ $filename ] = $params;

        if(empty($filename)) {
            throw new Exception("Missing migration filename", 1);
        }
        
        $this->generateMigration($filename);
        
    }
    
    private function generateMigration(string $filename) {

        echo "[Migration] Iniciando criação do arquivo de migração...\r\n";

        $migration = file_get_contents(self::EXAMPLE_MIGRATION_PATH);
        $filename = time() . "_".$filename.".php";

        if(!file_put_contents(self::MIGRATION_PATH."/".$filename, $migration)) {
            throw new \Exception("Error creating migration file");
        }

        echo "[Migration] Arquivo de migração criado com sucesso!\r\n";
        echo "[Migration] Nome do arquivo: $filename" . "\r\n";

    }

}