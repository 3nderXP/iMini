<?php

namespace Core\Infra\Database\Migrations;

use PDO;

interface MigrationInterface {

    public function up(PDO $database);
    public function down(PDO $database);

}