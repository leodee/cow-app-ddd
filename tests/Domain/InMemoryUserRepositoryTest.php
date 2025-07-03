<?php

namespace Tests\Domain;

use PHPUnit\Framework\TestCase;
use Tests\InMemory\InMemoryUserRepository;
use App\Domain\User;

class InMemoryUserRepositoryTest extends TestCase
{
    public function testSaveAndFindById(): void
    {
        $repository = new InMemoryUserRepository();

        $user = User::register('testuser', 'password123');
        $repository->save($user);

        $found = $repository->find($user->getId());

        $this->assertInstanceOf(User::class, $found);
        $this->assertSame($user->getId(), $found->getId());
        $this->assertSame('testuser', $found->getUsername());
    }

    public function testFindByUsername(): void
    {
        $repository = new InMemoryUserRepository();

        $user = User::register('unique_user', 'password123');
        $repository->save($user);

        $found = $repository->findByUsername('unique_user');

        $this->assertInstanceOf(User::class, $found);
        $this->assertSame($user->getId(), $found->getId());
    }

    public function testFindReturnsNullForUnknownId(): void
    {
        $repository = new InMemoryUserRepository();

        $this->assertNull($repository->find('non-existent-id'));
    }

    public function testFindByUsernameReturnsNullIfNotFound(): void
    {
        $repository = new InMemoryUserRepository();

        $this->assertNull($repository->findByUsername('ghost'));
    }
}
