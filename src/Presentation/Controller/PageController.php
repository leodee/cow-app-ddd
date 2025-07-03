<?php

namespace App\Presentation\Controller;

use App\Domain\Event;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PageController extends AbstractController
{
    public function pageA(): Response
    {
        $this->track(Event::ACTION_VIEW_PAGE_A);

        return new Response($this->twig->render('page_a.html.twig'));
    }

    public function pageB(): Response
    {
        $this->track(Event::ACTION_VIEW_PAGE_B);

        return new Response($this->twig->render('page_b.html.twig'));
    }

    public function buyCow(): JsonResponse
    {
        $this->track(Event::ACTION_CLICK_BUY_COW);

        return new JsonResponse(['success' => true]);
    }

    public function download(): JsonResponse
    {
        $this->track(Event::ACTION_CLICK_DOWNLOAD);

        return new JsonResponse([
            'success' => true,
            'fileUrl' => '/downloads/file.exe'
        ]);
    }
}
