<?php

namespace App\Infrastructure\Http;

interface SessionStorageInterface
{
    public function get(string $key): mixed;

    public function set(string $key, mixed $value): void;

    public function remove(string $key): void;
}