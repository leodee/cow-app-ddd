<?php

namespace Tests\Domain;

use App\Domain\Event;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class EventTest extends TestCase
{
    public function testEventRecord(): void
    {
        $userId = Uuid::v4()->toRfc4122();
        $event = Event::record($userId, Event::ACTION_LOGIN);

        $this->assertSame($userId, $event->getUserId());
        $this->assertSame(Event::ACTION_LOGIN, $event->getAction());
        $this->assertNotEmpty($event->getId());
        $this->assertInstanceOf(\DateTimeImmutable::class, $event->getCreatedAt());
    }

    public function testEventRestore(): void
    {
        $id = Uuid::v4()->toRfc4122();
        $userId = Uuid::v4()->toRfc4122();
        $action = Event::ACTION_LOGOUT;
        $createdAt = '2024-01-01 12:00:00';

        $event = Event::restore($id, $userId, $action, $createdAt);

        $this->assertSame($id, $event->getId());
        $this->assertSame($userId, $event->getUserId());
        $this->assertSame($action, $event->getAction());
        $this->assertSame($createdAt, $event->getCreatedAt()->format('Y-m-d H:i:s'));
    }
}
