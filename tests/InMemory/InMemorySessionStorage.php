<?php

namespace Tests\InMemory;

use App\Infrastructure\Http\SessionStorageInterface;

class InMemorySessionStorage implements SessionStorageInterface
{
    private array $storage = [];

    public function get(string $key): mixed
    {
        return $this->storage[$key] ?? null;
    }

    public function set(string $key, mixed $value): void
    {
        $this->storage[$key] = $value;
    }

    public function remove(string $key): void
    {
        unset($this->storage[$key]);
    }
}
