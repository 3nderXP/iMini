<?php

namespace Core\Routes;

use Core\Helpers\ApiResponse;
use Core\Controllers\Middlewares;
use Core\Infra\Repositories\UserRepository;
use Core\Models\Interfaces\Repositories\UserRepositoryInterface;
use Core\Models\Interfaces\Services\TokenServiceInterface;
use Core\Routes\ApiRoutes;
use Core\Services\JWTService;
use DI\ContainerBuilder;
use Error;
use Exception;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

use function DI\autowire;

class Router {

    private App $app;

    public function __construct() {

        $containerBuilder = new ContainerBuilder();
        $containerBuilder->addDefinitions([
            Middlewares\AuthMiddleware::class => autowire(Middlewares\AuthMiddleware::class),
            UserRepositoryInterface::class => autowire(UserRepository::class),
            TokenServiceInterface::class => autowire(JWTService::class),
        ]);

        $container = $containerBuilder->build();
        
        $this->app = AppFactory::create(container: $container);
        $this->app->setBasePath($_ENV["APP_ROOT"]);
        $this->app->addRoutingMiddleware();
        $this->app->addBodyParsingMiddleware();

        $this->app->options("/{routes:.+}", function (Request $req, Response $res) {
            return $res;
        });
        
    }

    public function init() {

        try {

            $this->app->group("/api", [ Api::class, "init" ]);

            $this->app->run();

        } catch(Error|Exception $e) {

            echo ApiResponse::return(
                code: $e->getCode(),
                message: $e->getMessage()
            );

        }

    }
    
}