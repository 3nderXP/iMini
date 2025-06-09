<?php

namespace Core\Models\Interfaces\Repositories;

use Core\Models\Interfaces\EntityInterface;

interface BasicRepoInterface {

    public function createEntity(array $data): EntityInterface;

}