<?php

namespace Core\Models\Interfaces\Services;

interface TokenServiceInterface {

    public function encode(array $payload): string;
    public function decode(string $token): object;
    public function isValid(string $token, ?array $claims = null): bool;

}