<?php

use Core\Infra\Database\Migrations\MigrationInterface;

return new class implements MigrationInterface {

    public function up(PDO $database): void {

        $database->exec("CREATE TABLE IF NOT EXISTS users (
            id VARCHAR(255) NOT NULL PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            photo VARCHAR(255),
            banner VARCHAR(255),
            password VARCHAR(255) NOT NULL,
            status ENUM('INACTIVE', 'ACTIVE') NOT NULL DEFAULT 'ACTIVE',
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        );");

    }

    public function down(PDO $database): void {
        
        $database->exec("DROP TABLE IF EXISTS users;");

    }

};