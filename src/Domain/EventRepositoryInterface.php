<?php

namespace App\Domain;

interface EventRepositoryInterface
{
    public function save(Event $event): void;

    public function findForStatistics(
        ?string $username = null,
        ?string $action = null,
        ?string $date = null
    ): array;

    public function getAggregatedReport(): array;
}
