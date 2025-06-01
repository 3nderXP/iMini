<?php

use Core\Routes\Router;
use Dotenv\Dotenv;
use Slim\Factory\AppFactory;

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

require_once("./vendor/autoload.php");

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$router = new Router(AppFactory::create());
$router->init();