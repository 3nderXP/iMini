<?php

namespace Core\Console\Commands;

use Core\Console\Interfaces\CommandInterface;
use Core\Infra\Database\SqlConnection;
use Exception;
use PDO;

class RunMigration implements CommandInterface {

    private PDO $database;
    public string $description;

    public function __construct() {

        $this->description = "Migrate Database";

    }

    public function handle(array $params = []) {

        echo "\n[Migration] Starting database migration process...\n";

        $this->database = (new SqlConnection())->connect();
        $this->database->exec("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255) NOT NULL UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );");

        $migrationsAlreadyRun = $this->database->query("SELECT migration FROM migrations")
            ->fetchAll(PDO::FETCH_COLUMN);
        
        if(!is_dir(MakeMigration::MIGRATION_PATH)) {
            throw new Exception("Migration folder not found", 500);
        }
        
        echo "[Migration] Scanning migration files in directory...\n";

        $migrations = glob(MakeMigration::MIGRATION_PATH."/*.php");

        if(empty($migrations)) {
            throw new Exception("No migrations found", 500);
        }

        echo "[Migration] Found ".count($migrations)." migration files. Starting execution...\n";

        $this->database->beginTransaction();

        try {

            foreach($migrations as $migration) {

                $migrationName = basename($migration);

                if(in_array($migrationName, $migrationsAlreadyRun)) {
                    echo "[Migration] Skipping {$migrationName} - Already executed\n";
                    continue;
                }

                echo "[Migration] Executing migration: {$migrationName}\n";
                
                $migration = require_once($migration);
                $migration->up($this->database);

                $this->database->exec("INSERT INTO migrations (migration) VALUES ('$migrationName')");
                echo "[Migration] Successfully executed: {$migrationName}\n";
            }

            if($this->database->inTransaction()) $this->database->commit();

            echo "\n[Migration] ✓ All migrations completed successfully!\n";

        } catch(Exception $e) {

            if($this->database->inTransaction()) $this->database->rollBack();

            throw $e;

        }
        
    }
}