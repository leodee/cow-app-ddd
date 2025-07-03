<?php

namespace Tests\Application;

use App\Application\Service\AuthService;
use App\Domain\User;
use App\Infrastructure\Http\SessionAuth;
use PHPUnit\Framework\TestCase;
use Tests\InMemory\InMemorySessionStorage;
use Tests\InMemory\InMemoryUserRepository;

class AuthServiceTest extends TestCase
{
    public function testSuccessfulLogin(): void
    {
        $repository = new InMemoryUserRepository();
        $sessionAuth = new SessionAuth(new InMemorySessionStorage());

        $user = User::register('testuser', 'password123');
        $repository->save($user);

        $authService = new AuthService($repository, $sessionAuth);
        $result = $authService->login('testuser', 'password123');

        $this->assertTrue($result);
        $this->assertSame($user->getId(), $sessionAuth->getUserId());
    }

    public function testFailedLogin(): void
    {
        $repository = new InMemoryUserRepository();
        $sessionAuth = new SessionAuth(new InMemorySessionStorage());

        $authService = new AuthService($repository, $sessionAuth);
        $result = $authService->login('unknown', 'wrongpass');

        $this->assertFalse($result);
        $this->assertNull($sessionAuth->getUserId());
    }
}
