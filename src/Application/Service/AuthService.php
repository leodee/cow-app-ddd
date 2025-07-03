<?php

namespace App\Application\Service;

use App\Domain\User;
use App\Domain\UserRepositoryInterface;
use App\Infrastructure\Http\SessionAuth;

class AuthService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly SessionAuth $sessionAuth
    ) {}

    public function register(string $username, string $password): void
    {
        $user = User::register($username, $password);
        $this->userRepository->save($user);
    }

    public function login(string $username, string $password): bool
    {
        $user = $this->userRepository->findByUsername($username);

        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user->getPasswordHash())) {
            return false;
        }

        $this->sessionAuth->login($user);

        return true;
    }
}
