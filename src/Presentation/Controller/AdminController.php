<?php

namespace App\Presentation\Controller;

use App\Domain\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends AbstractController
{
    public function statistics(Request $request): Response
    {
        if (!$this->sessionAuth->isAdmin()) {
            return new Response('Access denied', 403);
        }

        $username = $request->query->get('username');
        $action = $request->query->get('action');
        $date = $request->query->get('date');

        $events = $this->eventRepository->findForStatistics($username, $action, $date);

        return new Response($this->twig->render('statistics.html.twig', [
            'events' => $events,
            'username' => $username,
            'action' => $action,
            'date' => $date,
            'available_actions' => Event::getAvailableActions()
        ]));
    }

    public function report(): Response
    {
        if (!$this->sessionAuth->isAdmin()) {
            return new Response('Access denied', 403);
        }

        $rawData = $this->eventRepository->getAggregatedReport();

        if (empty($rawData)) {
            return new Response('No data for report.');
        }

        $tableData = $rawData;
        $graphData = $rawData;

        $firstDate = new \DateTimeImmutable($graphData[0]['date']);
        $preDate = $firstDate->modify('-1 day')->format('Y-m-d');

        array_unshift($graphData, [
            'date' => $preDate,
            'page_a_views' => 0,
            'page_b_views' => 0,
            'buy_cow_clicks' => 0,
            'download_clicks' => 0,
        ]);

        return new Response($this->twig->render('report.html.twig', [
            'graph_data' => $graphData,
            'table_data' => $tableData
        ]));
    }
}
