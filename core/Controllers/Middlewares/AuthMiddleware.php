<?php

namespace Core\Controllers\Middlewares;

use Core\Models\Enums\UserStatus;
use Exception;
use Core\Models\Interfaces\Repositories\UserRepositoryInterface;
use Core\Models\Interfaces\Services\TokenServiceInterface;
use Core\Models\ValueObjects\UUID;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteContext;

class AuthMiddleware implements MiddlewareInterface {

    public function __construct(
        private UserRepositoryInterface $userRepository,
        private TokenServiceInterface $tokenService
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {

        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        $endpoint = $_ENV["APP_HOST"] . $routeContext->getBasePath() . $route->getPattern();
        $authorization = $request->getHeaderLine("Authorization");
        $reqClaims = [
             "type" => [ "access", "refresh"],
             "iss" => $_ENV["APP_HOST"] . $routeContext->getBasePath(),
             "aud"
        ];

        @[ $prefix, $token ] = explode(" ", $authorization);

        if(empty($token) || $prefix !== "Bearer") {
            throw new Exception(code: 401);
        }

        if(!$this->tokenService->isValid($token, $reqClaims)) {
            throw new Exception(code: 401);
        }
        
        $tokenDecoded = $this->tokenService->decode($token);
        
        if(!str_contains($endpoint, $tokenDecoded->aud)) {
            throw new Exception(code: 401);
        }

        $user = $this->userRepository->findById(
            new UUID($tokenDecoded->sub)
        );

        if(!$user || $user->getStatus() == UserStatus::INACTIVE) throw new Exception(code: 401);

        return $handler->handle(
            $request->withAttribute("token", $token)
                    ->withAttribute("tokenDecoded", $tokenDecoded)
                    ->withAttribute("user", $user)
        );

    }

}