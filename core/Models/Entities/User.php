<?php

namespace Core\Models\Entities;

use Core\Models\Enums\UserStatus;
use Core\Models\ValueObjects\Email;
use Core\Models\ValueObjects\Password;
use Core\Models\ValueObjects\UUID;
use Core\Models\Interfaces\EntityInterface;

class User implements EntityInterface {

    public function __construct(
        private ?UUID $id = null,
        private ?string $name = null,
        private ?Email $email = null,
        private ?string $photo = null,
        private ?string $banner = null,
        private ?Password $password = null,
        private ?UserStatus $status = null,
        private ?string $createdAt = null,
    ) {}

    public function toArray(): array {

        return [
            "id" => $this->getId()->getValue(),
            "name" => $this->getName(),
            "email" => $this->getEmail()->getValue(),
            "photo" => $this->getPhoto(),
            "banner" => $this->getBanner(),
            "password" => $this->getPassword()->getValue(),
            "status" => $this->getStatus()->value,
            "createdAt" => $this->getCreatedAt()
        ];

    }

    public function toJson(): string {

        return json_encode($this->toArray());
        
    }

    public function getId(): ?UUID {
        return $this->id;
    }

    public function changeId(UUID $id): void {
        $this->id = $id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function changeName(string $name): self {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): ?Email {
        return $this->email;
    }

    public function getPhoto(): ?string {
        return $this->photo;
    }

    public function getBanner(): ?string {
        return $this->banner;
    }

    public function getPassword(): ?Password {
        return $this->password;
    }

    public function getStatus(): ?UserStatus {
        return $this->status;
    }

    public function getCreatedAt(): ?string {
        return $this->createdAt;
    }

}