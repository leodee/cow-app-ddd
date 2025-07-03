<?php

namespace App\Domain;

use Symfony\Component\Uid\Uuid;

class User
{
    private readonly string $id;
    private string $username;
    private string $passwordHash;
    private string $role;

    private function __construct(string $id, string $username, string $passwordHash, string $role)
    {
        $this->id = $id;
        $this->setUsername($username);
        $this->passwordHash = $passwordHash;
        $this->setRole($role);
    }

    public static function register(string $username, string $plainPassword): self
    {
        if (strlen($username) < 3) {
            throw new \InvalidArgumentException('Username is too short.');
        }

        $passwordHash = password_hash($plainPassword, PASSWORD_DEFAULT);
        return new self(Uuid::v4()->toRfc4122(), $username, $passwordHash, 'user');
    }

    public static function restore(string $id, string $username, string $passwordHash, string $role): self
    {
        return new self($id, $username, $passwordHash, $role);
    }

    private function setUsername(string $username): void
    {
        if (strlen($username) < 3) {
            throw new \InvalidArgumentException('Username is too short.');
        }
        $this->username = $username;
    }

    private function setRole(string $role): void
    {
        $allowed = ['user', 'admin'];
        if (!in_array($role, $allowed, true)) {
            throw new \InvalidArgumentException('Invalid role.');
        }
        $this->role = $role;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getRole(): string
    {
        return $this->role;
    }
}
