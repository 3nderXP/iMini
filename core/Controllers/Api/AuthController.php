<?php

namespace Core\Controllers\Api;

use Core\Helpers\ApiResponse;
use Core\Models\Entities\User;
use Core\Models\ValueObjects\Email;
use Core\Models\ValueObjects\Password;
use Core\Services\AuthService;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class AuthController {

    public function __construct(
        private AuthService $authService
    ) {}

    public function login(Request $req, Response $res) {

        @[ "email" => $email, "password" => $password ] = $req->getParsedBody();

        $authData = $this->authService->login(new User(
            email: new Email($email),
            password: new Password($password)
        ));

        $res->getBody()->write(ApiResponse::return(
            data: $authData,
            code: 200,
            message: "Authenticated successfully!"
        ));

        return $res;

    }

    public function refresh(Request $req, Response $res) {
        
        $token = $req->getAttribute("token");
        $authData = $this->authService->refresh($token);

        $res->getBody()->write(ApiResponse::return(
            data: $authData,
            code: 200,
            message: "Tokens refreshed successfully!"
        ));

        return $res;

    }

}