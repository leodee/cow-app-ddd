<?php

namespace Tests\Domain;

use PHPUnit\Framework\TestCase;
use Tests\InMemory\InMemoryEventRepository;
use Tests\InMemory\InMemoryUserRepository;
use App\Domain\User;
use App\Domain\Event;
use App\Application\Dto\EventForStatistics;

class InMemoryEventRepositoryTest extends TestCase
{
    public function testFindForStatisticsReturnsCorrectDto(): void
    {
        $userRepository = new InMemoryUserRepository();
        $eventRepository = new InMemoryEventRepository($userRepository);

        $user = User::register('testuser', 'password123');
        $userRepository->save($user);

        $event = Event::record($user->getId(), Event::ACTION_VIEW_PAGE_A);
        $eventRepository->save($event);

        $results = $eventRepository->findForStatistics();

        $this->assertCount(1, $results);
        $dto = $results[0];

        $this->assertInstanceOf(EventForStatistics::class, $dto);
        $this->assertSame($event->getId(), $dto->getId());
        $this->assertSame('testuser', $dto->getUsername());
        $this->assertSame(Event::ACTION_VIEW_PAGE_A, $dto->getAction());
        $this->assertSame($event->getCreatedAt()->format('Y-m-d H:i:s'), $dto->getCreatedAt());
    }

    public function testFindForStatisticsWithFilters(): void
    {
        $userRepository = new InMemoryUserRepository();
        $eventRepository = new InMemoryEventRepository($userRepository);

        $user1 = User::register('alice', 'pass');
        $user2 = User::register('bob', 'pass');

        $userRepository->save($user1);
        $userRepository->save($user2);

        $event1 = Event::record($user1->getId(), Event::ACTION_VIEW_PAGE_A);
        $event2 = Event::record($user2->getId(), Event::ACTION_CLICK_BUY_COW);

        $eventRepository->save($event1);
        $eventRepository->save($event2);

        $results = $eventRepository->findForStatistics('bob', Event::ACTION_CLICK_BUY_COW);

        $this->assertCount(1, $results);
        $dto = $results[0];
        $this->assertSame('bob', $dto->getUsername());
        $this->assertSame(Event::ACTION_CLICK_BUY_COW, $dto->getAction());
    }
}
