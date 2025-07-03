<?php

namespace App\Domain;

use Symfony\Component\Uid\Uuid;

class Event
{
    public const ACTION_VIEW_PAGE_A = 'view_page_a';
    public const ACTION_VIEW_PAGE_B = 'view_page_b';
    public const ACTION_CLICK_BUY_COW = 'click_buy_cow';
    public const ACTION_CLICK_DOWNLOAD = 'click_download';
    public const ACTION_LOGIN = 'login';
    public const ACTION_LOGOUT = 'logout';
    public const ACTION_REGISTER = 'register';

    private function __construct(
        private readonly string $id,
        private readonly string $userId,
        private readonly string $action,
        private readonly \DateTimeImmutable $createdAt
    ) {
    }

    public static function record(string $userId, string $action): self
    {
        return new self(Uuid::v4()->toRfc4122(), $userId, $action, new \DateTimeImmutable());
    }

    public static function restore(string $id, string $userId, string $action, string $createdAt): self
    {
        return new self($id, $userId, $action, new \DateTimeImmutable($createdAt));
    }

    public static function getAvailableActions(): array
    {
        return [
            self::ACTION_VIEW_PAGE_A => 'Page A Viewed',
            self::ACTION_VIEW_PAGE_B => 'Page B Viewed',
            self::ACTION_CLICK_BUY_COW => 'Buy a Cow Clicked',
            self::ACTION_CLICK_DOWNLOAD => 'File Downloaded',
            self::ACTION_LOGIN => 'User Logged In',
            self::ACTION_LOGOUT => 'User Logged Out',
            self::ACTION_REGISTER => 'User Registered',
        ];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
