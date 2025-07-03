<?php

namespace Tests\InMemory;

use App\Application\Dto\EventForStatistics;
use App\Domain\Event;
use App\Domain\EventRepositoryInterface;
use App\Domain\UserRepositoryInterface;

class InMemoryEventRepository implements EventRepositoryInterface
{
    /** @var Event[] */
    private array $events = [];

    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function save(Event $event): void
    {
        $this->events[] = $event;
    }

    public function findForStatistics(
        ?string $username = null,
        ?string $action = null,
        ?string $date = null
    ): array {
        $result = [];
        foreach ($this->events as $event) {
            $user = $this->userRepository->find($event->getUserId());
            if (!$user) {
                continue;
            }

            if ($username && $user->getUsername() !== $username) {
                continue;
            }
            if ($action && $event->getAction() !== $action) {
                continue;
            }
            if ($date && $event->getCreatedAt()->format('Y-m-d') !== $date) {
                continue;
            }

            $result[] = new EventForStatistics(
                $event->getId(),
                $user->getUsername(),
                $event->getAction(),
                $event->getCreatedAt()->format('Y-m-d H:i:s')
            );
        }

        return $result;
    }

    public function getAggregatedReport(): array
    {
        $result = [];

        foreach ($this->events as $event) {
            $date = $event->getCreatedAt()->format('Y-m-d');
            if (!isset($result[$date])) {
                $result[$date] = [
                    'page_a_views' => 0,
                    'page_b_views' => 0,
                    'buy_cow_clicks' => 0,
                    'download_clicks' => 0,
                ];
            }

            switch ($event->getAction()) {
                case Event::ACTION_VIEW_PAGE_A:
                    $result[$date]['page_a_views']++;
                    break;
                case Event::ACTION_VIEW_PAGE_B:
                    $result[$date]['page_b_views']++;
                    break;
                case Event::ACTION_CLICK_BUY_COW:
                    $result[$date]['buy_cow_clicks']++;
                    break;
                case Event::ACTION_CLICK_DOWNLOAD:
                    $result[$date]['download_clicks']++;
                    break;
            }
        }

        ksort($result);
        $report = [];

        foreach ($result as $date => $data) {
            $report[] = ['date' => $date] + $data;
        }

        return $report;
    }
}
