<?php

namespace Core\Services;

use Core\Models\Entities\User;
use Core\Models\Interfaces\Repositories\UserRepositoryInterface;
use Core\Models\Interfaces\Services\TokenServiceInterface;
use Core\Models\ValueObjects\UUID;
use Exception;

class AuthService {

    const HOUR_FACTOR = 60 * 60;
    const MINUTE_FACTOR = 60;
    const ACCESS_TOKEN_EXP = 15 * self::MINUTE_FACTOR;
    const REFRESH_TOKEN_EXP = 1 * self::HOUR_FACTOR;

    public function __construct(
        private UserRepositoryInterface $userRepository,
        private TokenServiceInterface $tokenService
    ) {}

    public function login(User $user): array {

        $userOnDb = $this->userRepository->findByEmail($user->getEmail());
        $passHash = $userOnDb ? $userOnDb->getPassword()->getValue() : null;
        
        if (!$userOnDb || !$user->getPassword()->verify($passHash)) {
            throw new Exception("Invalid credentials", 401);
        }

        $tokens = $this->generateTokens($userOnDb);

        return $tokens;

    }

    public function refresh(string $refreshToken): array {

        $tokenDecoded = $this->tokenService->decode($refreshToken);
        $user = $this->userRepository->findById(new UUID($tokenDecoded->sub));

        if (!$user) throw new Exception("Invalid token", 401);

        $tokens = $this->generateTokens($user);

        return $tokens;

    }

    private function generateTokens(User $user): array {

        $currentTime = time();

        $tokenId = [
            "sub" => $user->toArray(),
            "iat" => $currentTime,
            "iss" => $_ENV["APP_HOST"] . $_ENV["APP_ROOT"],
        ];

        $accessToken = [
            "sub" => $user->getId()->getValue(),
            "type" => "access",
            "exp" => $currentTime + self::ACCESS_TOKEN_EXP,
            "iat" => $currentTime,
            "iss" => $_ENV["APP_HOST"] . $_ENV["APP_ROOT"],
            "aud" => $_ENV["APP_HOST"] . $_ENV["APP_ROOT"] . "/api"
        ];

        $refreshToken = [
            "jti" => UUID::generate()->getValue(),
            "sub" => $user->getId()->getValue(),
            "type" => "refresh",
            "exp" => $currentTime + self::REFRESH_TOKEN_EXP,
            "iat" => $currentTime,
            "iss" => $_ENV["APP_HOST"] . $_ENV["APP_ROOT"],
            "aud" => $_ENV["APP_HOST"] . $_ENV["APP_ROOT"] . "/api/auth/refresh"
        ];

        return [
            "tokenId" => $this->tokenService->encode($tokenId),
            "accessToken" => $this->tokenService->encode($accessToken),
            "refreshToken" => $this->tokenService->encode($refreshToken)
        ];

    }

}