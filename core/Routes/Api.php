<?php

namespace Core\Routes;

use Slim\Interfaces\RouteCollectorProxyInterface;
use Slim\Routing\RouteCollectorProxy;
use Core\Controllers\Middlewares;
use Core\Controllers\Api AS ApiController;

class Api {

    public function init(RouteCollectorProxyInterface $route) {

        $route->group("/auth", function (RouteCollectorProxy $group) {

            $group->post("/login", [ ApiController\AuthController::class, "login" ]);
            $group->post("/refresh", [ ApiController\AuthController::class, "refresh" ])
                    ->add(Middlewares\AuthMiddleware::class);

        });

        $route->group("/users", function (RouteCollectorProxy $group) {

            $group->get("", [ ApiController\UsersController::class, "getAll" ])
                    ->add(Middlewares\AuthMiddleware::class);

            $group->post("", [ ApiController\UsersController::class, "createAccount" ]);

        });

    }

}