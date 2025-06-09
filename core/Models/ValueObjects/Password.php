<?php

namespace Core\Models\ValueObjects;

class Password {

    public function __construct(
        private ?string $value
    ) {

        if(empty($this->value)) throw new \Exception("Password cannot be empty");

    }

    public function getValue(): string {
        return $this->value;
    }

    public function isHashed(): bool {
        return password_get_info($this->value)["algoName"] !== "unknown";
    }

    public function genHash(string $alg = PASSWORD_DEFAULT, int $cost = 12): string {

        return password_hash($this->value, PASSWORD_DEFAULT, [ "cost" => $cost ]);

    }

    public function verify(string $hash): bool {
        return password_verify($this->value, $hash);
    }

    public function __toString() {
        return $this->value;
    }
    
}