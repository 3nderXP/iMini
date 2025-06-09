<?php

namespace Core\Infra\Repositories;

use Core\Infra\Database\SqlConnection;
use Core\Models\Entities\User;
use Core\Models\Enums\UserStatus;
use Core\Models\ValueObjects\Email;
use Core\Models\ValueObjects\UUID;
use Core\Models\Interfaces\DatabasePaginatorInterface;
use Core\Models\Interfaces\Repositories\BasicRepoInterface;
use Core\Models\Interfaces\Repositories\UserRepositoryInterface;
use Core\Models\ValueObjects\Password;
use Exception;
use PDO;

class UserRepository implements UserRepositoryInterface, BasicRepoInterface {

    private PDO $database;

    public function __construct(
        private SqlConnection $sqlConnection
    ) {

        $this->database = $this->sqlConnection->connect();

    }

    public function createEntity(array $data): User {

        return new User(
            id: $data["id"] ? new UUID($data["id"]) : null,
            name: $data["name"] ?? null,
            email: $data["email"] ? new Email($data["email"]) : null,
            photo: $data["photo"] ?? null,
            banner: $data["banner"] ?? null,
            password: $data["password"] ? new Password($data["password"]) : null,
            status: UserStatus::tryFrom($data["status"] ?? null),
            createdAt: $data["created_at"] ?? null,
        );
        
    }

    public function create(User $user): ?User {

        $sql = $this->database->prepare("INSERT INTO users (id, name, email, password) VALUES (:id, :name, :email, :password)");
        $binds = [
            "id" => $user->getId(),
            "name" => $user->getName(),
            "email" => $user->getEmail()->getValue(),
            "password" => $user->getPassword()->genHash()
        ];

        $sql->execute($binds);

        return $this->findById($user->getId());

    }

    public function findAll(?DatabasePaginatorInterface $paginator = null): array {
        
        $pagination = $paginator ? "LIMIT {$paginator->getOffset()}, {$paginator->getLimit()}" : "";

        $sql = $this->database->prepare("SELECT * FROM users ORDER BY created_at DESC $pagination");
        $sql->execute();

        return array_map(fn ($u) => $this->createEntity($u), $sql->fetchAll());

    }

    public function findById(UUID $id): ?User {

        $sql = $this->database->prepare("SELECT * FROM users WHERE id = :id");
        $sql->execute([
            "id" => $id->getValue()
        ]);

        if($sql->rowCount() == 0) return null;
        
        return $this->createEntity($sql->fetch());

    }
    
    public function findByEmail(Email $email): ?User {

        $sql = $this->database->prepare("SELECT * FROM users WHERE email = :email");
        $sql->execute([
            "email" => $email->getValue()
        ]);

        if($sql->rowCount() == 0) return null;

        return $this->createEntity($sql->fetch());

    }

}