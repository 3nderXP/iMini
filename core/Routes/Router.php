<?php

namespace Core\Routes;

use Core\Helpers\ApiResponse;
use Exception;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\App;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class Router {

    public function __construct(
        private App $app
    ) {
        
        $app->setBasePath($_ENV["APP_ROOT"]);
        $app->addRoutingMiddleware();
        $app->addBodyParsingMiddleware();

    }

    public function init() {

        try {

            $this->app->options("/{routes:.+}", function (Request $req, Response $res) {
                return $res;
            });

            $this->app->run();

        } catch(Exception $e) {

            echo ApiResponse::return(
                code: $e->getCode(),
                message: $e->getMessage()
            );

        }

    }
    
}