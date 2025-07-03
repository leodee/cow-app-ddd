<?php

namespace App\Infrastructure\Persistence;

use App\Domain\User;
use App\Domain\UserRepositoryInterface;
use Doctrine\DBAL\Connection;

class DoctrineUserRepository implements UserRepositoryInterface
{
    public function __construct(private readonly Connection $connection) {}

    public function find(string $id): ?User
    {
        $sql = 'SELECT * FROM users WHERE id = :id';
        $stmt = $this->connection->prepare($sql);
        $result = $stmt->executeQuery(['id' => $id])->fetchAssociative();

        if (!$result) {
            return null;
        }

        return User::restore(
            $result['id'],
            $result['username'],
            $result['password'],
            $result['role']
        );
    }

    public function findByUsername(string $username): ?User
    {
        $sql = 'SELECT * FROM users WHERE username = :username';
        $stmt = $this->connection->prepare($sql);
        $result = $stmt->executeQuery(['username' => $username])->fetchAssociative();

        if (!$result) {
            return null;
        }

        return User::restore(
            $result['id'],
            $result['username'],
            $result['password'],
            $result['role']
        );
    }

    public function save(User $user): void
    {
        $this->connection->insert('users', [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'password' => $user->getPasswordHash(),
            'role' => $user->getRole(),
        ]);
    }
}
