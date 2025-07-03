<?php

namespace App\Infrastructure\Persistence;

use App\Application\Dto\EventForStatistics;
use App\Domain\Event;
use App\Domain\EventRepositoryInterface;
use Doctrine\DBAL\Connection;

class DoctrineEventRepository implements EventRepositoryInterface
{
    public function __construct(private readonly Connection $connection) {}

    public function save(Event $event): void
    {
        $this->connection->insert('events', [
            'id' => $event->getId(),
            'user_id' => $event->getUserId(),
            'action' => $event->getAction(),
            'created_at' => $event->getCreatedAt()->format('Y-m-d H:i:s'),
        ]);
    }

    public function findForStatistics(
        ?string $username = null,
        ?string $action = null,
        ?string $date = null
    ): array {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('e.id, u.username, e.action, e.created_at')
            ->from('events', 'e')
            ->leftJoin('e', 'users', 'u', 'e.user_id = u.id')
            ->orderBy('e.created_at', 'DESC');

        if ($username) {
            $qb->andWhere('u.username LIKE :username')->setParameter('username', "%$username%");
        }
        if ($action) {
            $qb->andWhere('e.action = :action')->setParameter('action', $action);
        }
        if ($date) {
            $qb->andWhere('DATE(e.created_at) = :date')->setParameter('date', $date);
        }

        $rows = $qb->executeQuery()->fetchAllAssociative();

        return array_map(fn($row) => new EventForStatistics(
            $row['id'],
            $row['username'],
            $row['action'],
            $row['created_at']
        ), $rows);
    }

    public function getAggregatedReport(): array
    {
        $sql = "
            SELECT DATE(created_at) as date,
                SUM(action = '" . Event::ACTION_VIEW_PAGE_A . "') as page_a_views,
                SUM(action = '" . Event::ACTION_VIEW_PAGE_B . "') as page_b_views,
                SUM(action = '" . Event::ACTION_CLICK_BUY_COW . "') as buy_cow_clicks,
                SUM(action = '" . Event::ACTION_CLICK_DOWNLOAD . "') as download_clicks
            FROM events
            GROUP BY DATE(created_at)
            ORDER BY DATE(created_at) ASC
        ";

        return $this->connection
            ->executeQuery($sql)
            ->fetchAllAssociative();
    }
}
