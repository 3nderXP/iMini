<?php

namespace Core\Console\Commands;

use Core\Console\Interfaces\CommandInterface;
use Core\Infra\Database\SqlConnection;
use Exception;
use PDO;

class RollbackMigration implements CommandInterface {

    private PDO $database;
    public string $description;

    public function __construct() {

        $this->description = "Migrate Database";

    }

    public function handle(array $params = []) {

        echo "[Rollback] Iniciando processo de rollback da última migração...\n";

        $this->database = (new SqlConnection())->connect();

        $lastMigration = $this->database->query("SELECT migration FROM migrations ORDER BY created_at DESC, id DESC LIMIT 1")
            ->fetch(PDO::FETCH_COLUMN);
        
        if(!is_dir(MakeMigration::MIGRATION_PATH)) {

            throw new Exception("Migration folder not found", 500);

        }
        
        echo "[Rollback] Procurando última migração executada no banco de dados...\n";

        $migrationFile = MakeMigration::MIGRATION_PATH . "/$lastMigration";

        if(empty($lastMigration) || !file_exists($migrationFile)) {

            throw new Exception("Migration not found", 500);
            
        }

        echo "[Rollback] Executando rollback da migração: $lastMigration...\n";

        $this->database->beginTransaction();

        try {

            $migration = require($migrationFile);
            $migration->down($this->database);

            $sql = $this->database->prepare("DELETE FROM migrations WHERE migration = ?");
            $sql->execute([ $lastMigration ]);

            if($this->database->inTransaction()) $this->database->commit();

            echo "[Rollback] Rollback da migração $lastMigration concluído com sucesso!\n";

        } catch(Exception $e) {

            if($this->database->inTransaction()) $this->database->rollBack();

            throw $e;

        }
        
    }}