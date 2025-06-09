<?php

namespace Core\Models\Interfaces;

interface EntityInterface {

    public function toArray(): array;
    public function toJson(): string;

}