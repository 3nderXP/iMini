<?php

require_once("./vendor/autoload.php");

use Core\Routes\Router;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$router = new Router();
$router->init();