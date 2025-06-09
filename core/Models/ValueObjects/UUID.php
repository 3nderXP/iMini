<?php

namespace Core\Models\ValueObjects;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid AS RUuid;

class UUID {

    public function __construct(
        private string $id
    ) {
        
        if(!RUuid::isValid($this->id)) {

            throw new InvalidArgumentException("Invalid User Id");
            
        }

    }

    public function getValue(): string {

        return $this->id;

    }

    public static function generate(): self {

        return new self(RUuid::uuid4()->toString());

    }

    public function __toString() {
        
        return $this->getValue();

    }

}