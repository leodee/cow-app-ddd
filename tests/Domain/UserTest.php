<?php

namespace Tests\Domain;

use App\Domain\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserCreation(): void
    {
        $user = User::register('testuser', 'password123');

        $this->assertSame('testuser', $user->getUsername());
        $this->assertSame('user', $user->getRole());
        $this->assertNotEmpty($user->getId());
        $this->assertTrue(password_verify('password123', $user->getPasswordHash()));
    }
}
