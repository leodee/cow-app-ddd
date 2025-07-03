<?php

namespace App\Application\Dto;

class EventForStatistics
{
    public function __construct(
        private readonly string $id,
        private readonly string $username,
        private readonly string $action,
        private readonly string $createdAt
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
}