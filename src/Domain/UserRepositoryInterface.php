<?php

namespace App\Domain;

interface UserRepositoryInterface
{
    public function save(User $user): void;
    public function find(string $id): ?User;
    public function findByUsername(string $username): ?User;
}
