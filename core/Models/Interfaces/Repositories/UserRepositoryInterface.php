<?php

namespace Core\Models\Interfaces\Repositories;

use Core\Models\Entities\User;
use Core\Models\ValueObjects\Email;
use Core\Models\ValueObjects\UUID;
use Core\Models\Interfaces\DatabasePaginatorInterface;

interface UserRepositoryInterface {

    public function create(User $user): ?User;

    /**
     * 
     * @param DatabasePaginatorInterface $paginator
     * @return User[]
     * 
    */

    public function findAll(?DatabasePaginatorInterface $paginator = null): array;
    public function findById(UUID $id): ?User;
    public function findByEmail(Email $email): ?User;

}