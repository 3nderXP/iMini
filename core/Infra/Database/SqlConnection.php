<?php

namespace Core\Infra\Database;

use Core\Models\Interfaces\DatabaseConnectionInterface;
use PDO;

class SqlConnection {

    public function connect() {

        return new PDO(
            $_ENV["DB_DRIVER"] . ":host=" . $_ENV["DB_SERVER"] . ";dbname=" . $_ENV["DB_NAME"] . ";charset=" . $_ENV["DB_CHARSET"],
            $_ENV["DB_USER"],
            $_ENV["DB_PASSWORD"],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );

    }

}