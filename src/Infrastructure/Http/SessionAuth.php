<?php

namespace App\Infrastructure\Http;

use App\Domain\User;

class SessionAuth
{
    public function __construct(
        private readonly SessionStorageInterface $storage
    ) {
    }

    public function login(User $user): void
    {
        $this->storage->set('user_id', $user->getId());
        $this->storage->set('username', $user->getUsername());
        $this->storage->set('role', $user->getRole());
    }

    public function logout(): void
    {
        $this->storage->remove('user_id');
        $this->storage->remove('username');
        $this->storage->remove('role');
    }

    public function isAdmin(): bool
    {
        return $this->storage->get('role') === 'admin';
    }

    public function getUserId(): ?string
    {
        return $this->storage->get('user_id');
    }

    public function getUsername(): ?string
    {
        return $this->storage->get('username');
    }
}
