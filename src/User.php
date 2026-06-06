<?php
declare(strict_types=1);

class User {
    public function __construct(
        private string $username,
        private string $password,
        private ?int $id = null
    ) {}

    public function getUsername(): string { return $this->username; }
    public function getPassword(): string { return $this->password; }
    public function getId(): ?int { return $this->id; }
}