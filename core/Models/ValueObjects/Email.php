<?php

namespace Core\Models\ValueObjects;

use InvalidArgumentException;

class Email {

    public function __construct(
        private string $email
    ) {

        if(empty($this->email)) throw new InvalidArgumentException("Email cannot be empty");
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) throw new InvalidArgumentException("Invalid email");

    }

    public function getValue(): string {
        
        return $this->email;

    }

    public function __toString(): string {

        return $this->email;

    }

}